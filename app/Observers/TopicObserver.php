<?php

namespace App\Observers;

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
        //数据入库前进行过滤
        $topic->body = clean($topic->body,'uesr_topic_body');

//        make_excerpt()是自定义的辅助方法
        $topic->excerpt = make_excerpt($topic->body);


    }
}