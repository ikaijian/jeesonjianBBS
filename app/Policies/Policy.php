<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;

class Policy
{
    use HandlesAuthorization;



    /**
     * 使用授权策略的 策略过滤器 机制来实现统一授权的目的
     * 在策略中定义一个 before() 方法。before 方法会在策略中其它所有方法之前执行
     *
     * 返回三种值
     * 返回 true 是直接通过授权；
     *返回 false，会拒绝用户所有的授权；
     *如果返回的是 null，则通过其它的策略方法来决定授权通过与否
     */
    public function before($user, $ability)
	{
	    //// 如果用户拥有管理内容的权限的话，即授权通过
	    if($user->can('manage_contents')){
            return true;
        }
	}
}


