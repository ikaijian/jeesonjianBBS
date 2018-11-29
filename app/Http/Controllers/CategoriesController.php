<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Topic;
use Illuminate\Http\Request;

class CategoriesController extends Controller
{
    //话题分类列表
    //调用方法 withOrder()
    public function show(Category $category,Request $request,Topic $topic)
    {
        // 读取分类 ID 关联的话题，并按每 20 条分页
//        $topics = Topic::where('category_id', $category->id)->paginate(10);
        $topics=$topic->withOrder($request->order)
            ->where('category_id',$category->id)
            ->paginate(10);
//        dd($topic);
        // 传参变量话题和分类到模板中
        return view('topics/index', compact('topics', 'category'));
    }

}
