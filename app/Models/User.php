<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Auth;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{

    use Traits\ActiveUserHelper;
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

    /**
     * 访问器和修改器最大的区别是『发生修改的时机』，访问器是 访问属性时 修改，
     * 修改器是在 写入数据库前 修改。修改器是数据持久化，访问器是临时修改。
     * 访问器的使用场景是当数据因为特殊原因存在不一致性时，可以使用访问器进行矫正处理。
     * 在我们的密码加密的场景里，我们会使用修改器在密码即将入库前，对其进行加密
     * @param $value
     */

    public function setPasswordAttribute($value)
    {
        // 如果值的长度等于 60，即认为是已经做过加密的情况
        if (strlen($value) != 60) {
            $value = bcrypt($value);
        }
        $this->attributes['password'] = $value;
    }


    public function setAvatarAttribute($path)
    {

        //// 如果不是 `http` 子串开头，那就是从后台上传的，需要补全 URL
        if(!starts_with($path,'http')){
            // 拼接完整的 URL
            $path = config('app.url') . "/uploads/images/avatars/$path";
        }


        $this->attributes['avatar'] = $path;
    }
}
