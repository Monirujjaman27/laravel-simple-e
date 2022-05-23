<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Category;
use App\tag;
use App\post;

class FrontendController extends Controller
{
    //    index page
    public function index()
    {
        $allPost = post::select([
            'id',
            'slug',
            'image',
            'title',
            'category_id',
            'created_at'
        ])->with('category', 'tags')->orderBy('id', 'desc')->paginate(9);
        $headerPost = post::orderBy('id', 'desc')->take(5)->get();

        $firstPost  = $headerPost->splice(0, 2);
        $middlePost = $headerPost->splice(0, 1);
        $lastPost   = $headerPost->splice(0);

        $footerpost = Post::orderBy('id', 'DESC')->take(5)->get();
        $footerFirstPost  = $footerpost->splice(0, 1);
        $footerMiddlePost = $footerpost->splice(0, 2);
        $footerLastPost   = $footerpost->splice(0, 1);
        return view('website.home', compact(['allPost', 'firstPost', 'middlePost', 'lastPost', 'footerFirstPost', 'footerMiddlePost', 'footerLastPost']));
    }

    public function about()
    {
        return view('website.about');
    }

    // show post by category
    public function category($slug)
    {
        $category = Category::where('slug', $slug)->first();
        if ($category) {
            $post = post::where('category_id', $category->id)->paginate(3);
            return view('website.category', compact(['category', 'post']));
        } else return redirect()->route('website.index');
    }
    // show post by tag page
    public function tag($slug)
    {
        $tags = tag::where('slug', $slug)->first();

        if ($tags) {
            $post = $tags->posts()->orderBy('created_at', 'desc')->paginate(9);
            return view('website.tag', compact(['tags', 'post']));
        } else return redirect()->route('website.index');
    }


    public function contact()
    {
        return view('website.contact');
    }

    public function post($slug)
    {
        if ($slug) {
            $allcategory = Category::select('id', 'name', 'slug')->paginate(5);
            $alltags = tag::select('id', 'name', 'slug')->paginate(5);
            $relatedPost = post::select([
                'id',
                'slug',
                'title',
                'category_id',
                'user_id',
                'image',
                'created_at'
            ])->with('category')->inRandomOrder()->paginate(3);
            $post = post::with('category', 'tags')->where('slug', $slug)->orderBy('category_id', 'desc')->first();
            // dd($post);
            return view('website.post', compact(['post', 'relatedPost', 'allcategory', 'alltags']));
        } else {
            return redirect('/');
        }
    }
}
