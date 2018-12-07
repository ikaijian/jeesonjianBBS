<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

//首页视图
Route::get('/', 'PagesController@root')->name('root');


//Auth::routes();
//同下面路由
//\larabbs\vendor\laravel\framework\src\Illuminate\Routing\Router.php
// 登陆退出
Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('login', 'Auth\LoginController@login');
Route::post('logout', 'Auth\LoginController@logout')->name('logout');

// 注册
Route::get('register', 'Auth\RegisterController@showRegistrationForm')->name('register');
Route::post('register', 'Auth\RegisterController@register');

// 重置密码
Route::get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
Route::post('password/reset', 'Auth\ResetPasswordController@reset');
//end同下面


//个人设置资源路由
Route::resource('users', 'UsersController', ['only' => ['show', 'update', 'edit']]);

/**
 * 等同于：
 *Route::get('/users/{user}', 'UsersController@show')->name('users.show'); //显示用户个人信息界面
 *Route::get('/users/{user}/edit', 'UsersController@edit')->name('users.edit'); //编辑个人资料页面
 *Route::patch('/users/{user}', 'UsersController@update')->name('users.update');//处理edit页面提交的更新
 */

//帖子
Route::resource('topics', 'TopicsController', ['only' => ['index', 'create', 'store', 'update', 'edit', 'destroy']]);

//分类话题
Route::resource('categories', 'CategoriesController', ['only' => ['show']]);


//上传图片
Route::post('upload_image', 'TopicsController@uploadImage')->name('topics.upload_image');

//{slug?} ，? 意味着参数可选
Route::get('topics/{topic}/{slug?}', 'TopicsController@show')->name('topics.show');

//话题回复
Route::resource('replies', 'RepliesController', ['only' => ['store', 'destroy']]);

//消息通知显示
Route::resource('notifications', 'NotificationsController', ['only' => ['index']]);

//无权限提醒页面
Route::get('permission-denied', 'PagesController@permissionDenied')->name('permission-denied');