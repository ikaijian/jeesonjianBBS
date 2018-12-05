<?php

namespace App\Observers;

use App\Handlers\SlugTranslateHandler;
use App\Jobs\TranslateSlug;
use App\Models\Topic;
use App\Notifications\TopicReplied;

// creating, created, updating, updated, saving,
// saved,  deleting, deleted, restoring, restored

class TopicObserver
{
//    public function creating(Topic $topic)
//    {
//        //
//    }
//
//    public function updating(Topic $topic)
//    {
//        //
//    }

    public function saving(Topic $topic)
    {
        //数据入库前进行过滤:XSS 过滤
        $topic->body = clean($topic->body,'uesr_topic_body');

//       生成话题摘录  make_excerpt()是自定义的辅助方法
        $topic->excerpt = make_excerpt($topic->body);

//        $topic->slug = app(SlugTranslateHandler::class)->translate($topic->title);

    }

    /**
     * 模型监控器的 saved()方法对应 Eloquent 的 saved 事件，此事件发生在创建和编辑时、数据入库以后
     * @param Topic $topic
     */
    public function saved(Topic $topic)
    {
        // 如 slug 字段无内容，即使用翻译器对 title 进行翻译
        if ( ! $topic->slug) {
//            $topic->slug = app(SlugTranslateHandler::class)->translate($topic->title);
            //将 Slug 翻译的调用修改为队列执行的方式
            // 推送任务到队列
            dispatch(new TranslateSlug($topic));

        }
    }

    /**
     * 话题被删除的时,数据库里的回复信息也删除
     * 话题连带删除
     * 在模型监听器中，数据库操作需要避免再次 Eloquent 事件，这里我们使用了 DB 类进行操作
     * @param Topic $topic
     */
    public function deleted(Topic $topic)
    {
        \DB::table('replies')->where('topic_id',$topic->id)->delete();
    }
}