<?php

namespace App\Observers;

use App\Handlers\SlugTranslateHandler;
use App\Models\Topic;

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

        // 如 slug 字段无内容，即使用翻译器对 title 进行翻译
        if ( ! $topic->slug) {
            $topic->slug = app(SlugTranslateHandler::class)->translate($topic->title);
        }


    }


}