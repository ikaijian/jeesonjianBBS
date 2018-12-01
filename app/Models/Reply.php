<?php

namespace App\Models;

class Reply extends Model
{
    protected $fillable = [ 'content'];


    /**
     * 话题model关联
     * 据模型的关联，一条回复属于一个话题，
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function topic()
    {
        return $this->belongsTo(Topic::class);
    }

    /**
     * 一个条回复属于一个作者所有
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
