<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Auth;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{

    //使用 laravel-permission 提供的 Trait —— HasRoles此举能获取到扩展包提供的所有权限和角色的操作方法
    use HasRoles;

    //消息通知
    use Notifiable {
        notify as protected laravelNotify;
    }

    public function Notify($instance)
    {
        //如果通知人是当前用户，就不需要通知
        if ($this->id == Auth::id()) {
            return;
        }
        $this->increment('notification_count');
        $this->laravelNotify($instance);

    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'introduction', 'avatar',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];


    //用户与话题中间的关系是 一对多 的关系，一个用户拥有多个主题
    public function topics()
    {
        return $this->hasMany(Topic::class);
    }

//    权限控制优化
    public function isAuthorOf($model)
    {
        return $this->id == $model->user_id;
    }

    /**
     * 一个用户可以拥有多条评论
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */

    public function replies()
    {
        return $this->hasMany(Reply::class);
    }


    //清除未读消息标示
    public function markAsRead()
    {
        $this->notification_count = 0;
        $this->save();
        $this->unreadNotifications->markAsRead();
    }

}
