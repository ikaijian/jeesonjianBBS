<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;


class PagesController extends Controller
{
    //首页
    public function root()
    {
        return view('pages.root');
    }
}
