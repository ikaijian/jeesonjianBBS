<?php

namespace App\Http\Controllers;

use App\Handlers\ImageUploadHandler;
use App\Http\Requests\UserRequest;
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    //身份验证（Auth）中间

    public function __construct()
    {
        //第一个为中间件的名称，第二个为要进行过滤的动作
        $this->middleware('auth',['except'=>['show']]);
    }

    //个人页面的视图
    public function show(User $user)
    {
        return view('users.show',compact('user'));
    }

    //编辑edit()
    public function edit(User $user)
    {

        try {
            $this->authorize('update', $user);
        }catch (AuthorizationException $e){
            $result = '无权限访问该页面';
            return view("errors.403",compact('result'));
        }
        $this->authorize('update',$user);
        return view('users.edit',compact('user'));
    }

    public function update(UserRequest $request,ImageUploadHandler $uploader,User $user)
    {

        $this->authorize('update',$user);
//        dd($request->avatar);
        $data=$request->all();
        if ($request->avatar){
            $result=$uploader->save($request->avatar,'avatars',$user->id,362);
            if ($result){
                $data['avatar']=$result['path'];
            }
        }
        $user->update($data);
        return redirect()->route('users.show',$user->id)->with('success','个人资料更新成功');
    }
}
