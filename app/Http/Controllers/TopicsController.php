<?php

namespace App\Http\Controllers;

use App\Handlers\ImageUploadHandler;
use App\Models\Category;
use App\Models\Topic;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\TopicRequest;

use Auth;

class TopicsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth', ['except' => ['index', 'show']]);
    }


    //新建话题
    public function create(Topic $topic)
    {
        $categories = Category::all();
        return view('topics.create_and_edit', compact('topic', 'categories'));
    }

    public function index(Request $request, Topic $topic)
    {
//		$topics = Topic::with('user','category')->paginate(15);
        //$request->order 是获取 URI http://larabbs.test/topics?order=recent 中的 order 参数
        $topics = $topic->withOrder($request->order)->paginate(15);
        return view('topics.index', compact('topics'));
    }

    public function show(Topic $topic)
    {
        return view('topics.show', compact('topic'));
    }


    public function store(TopicRequest $request, Topic $topic)
    {
        // fill 方法会将传参的键值数组填充到模型的属性中
        $topic->fill($request->all());
//		$topic = Topic::create($request->all());
        $topic->user_id = Auth::id();
        $topic->save();
        return redirect()->route('topics.show', $topic->id)->with('message', '创建话题成功');
    }

    public function edit(Topic $topic)
    {

        try{
            $this->authorize('update', $topic);
        }catch (AuthorizationException $e){
            $result= "无权访问该页面";
            return view('errors.403',compact('result'));
        }

        $categories=Category::all();
        return view('topics.create_and_edit', compact('topic','categories'));
    }

    public function update(TopicRequest $request, Topic $topic)
    {
        try{
            $this->authorize('update', $topic);
        }catch (AuthorizationException $e){
            $result="无权访问该页面";
            view('errors.403',compact('result'));
        }

        $topic->update($request->all());
        return redirect()->route('topics.show', $topic->id)->with('message', '更新话题成功.');
    }

    public function destroy(Topic $topic)
    {
        $this->authorize('destroy', $topic);
        $topic->delete();

        return redirect()->route('topics.index')->with('message', '删除话题成功');
    }



    //创建话题图片上传图片处理
    public function uploadImage(Request $request,ImageUploadHandler $uploader)
    {
        // 初始化返回数据，默认是失败的
        $data = [
            'success'   => false,
            'msg'       => '上传失败!',
            'file_path' => ''
        ];

        // 判断是否有上传文件，并赋值给 $file
        if ($file = $request->upload_file) {
            // 保存图片到本地
            $result = $uploader->save($request->upload_file, 'topics', Auth::id(), 1024);
            // 图片保存成功的话
            if ($result) {
                $data['file_path'] = $result['path'];
                $data['msg']       = "上传成功!";
                $data['success']   = true;
            }
        }
        return $data;

    }
}