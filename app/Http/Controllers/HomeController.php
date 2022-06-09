<?php

namespace App\Http\Controllers;

use App\Category;
use App\post;
use App\tag;
use App\User;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $postCount = post::select('id')->count();
        $categoryCount = Category::select('id')->count();
        $allpost = post::take(10)->get();
        $tagCount = tag::select('id')->count();
        $userCount = User::select('id')->count();
        return view('admin.dashboard.index', compact(['postCount', 'categoryCount', 'tagCount', 'userCount', 'allpost']));
    }
}
