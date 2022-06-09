<?php

namespace App\Http\Controllers;

use App\Category;
use App\tag;
use App\User;
use App\post;
use Illuminate\Http\Request;

class DashboardController extends Controller
{


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
