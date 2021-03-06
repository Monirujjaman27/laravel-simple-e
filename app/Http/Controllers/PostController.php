<?php

namespace App\Http\Controllers;

use App\Category;
use App\tag;
use App\post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Session;
use Illuminate\Support\str;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $allpost = post::select([
            'id',
            'slug',
            'image',
            'title',
            'category_id',
            'user_id',
            'created_at'
        ])->with('category', 'tags', 'user')->orderBy('id', 'desc')->paginate(20);
        // $tags = tag::orderBy('id', 'desc')->paginate(20);
        // $category = Category::orderBy('id', 'desc')->paginate(20);
        return view('admin.posts.index', compact('allpost'));
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $tags = tag::orderBy('id', 'desc')->paginate(20);
        $category = Category::orderBy('id', 'desc')->paginate(20);
        return view('admin.posts.addPost', compact(['category', 'tags']));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'title' => 'required|unique:posts,title',
            'image' => 'required',
            'description' => 'required',
            'category_id' => 'required',
        ]);

        $post = post::create([
            'title'       => ucfirst($request->title),
            'image'       => ' ',
            'slug'        => str::slug($request->title, '-'),
            'description' => $request->description,
            'category_id' => $request->category_id,
            'user_id'     => auth()->user()->id,
        ]);
        $post->tags()->attach($request->tags);

        if ($request->has('image')) {
            $image = $request->image;
            $newImgName = time() . '-' . $image->getClientOriginalName();
            $image->move('storage/post/', $newImgName);
            $post->image = $newImgName;
            $post->save();
        }
        $post->save();
        Session::flash('success', 'Post added successfully');
        return redirect()->back();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\post  $post
     * @return \Illuminate\Http\Response
     */
    public function show(post $post)
    {
        return view('admin.posts.viewPost', compact('post'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\post  $post
     * @return \Illuminate\Http\Response
     */
    public function edit(post $post)
    {
        $tags = tag::orderBy('id', 'desc')->paginate(20);
        $category = Category::orderBy('id', 'desc')->paginate(20);
        return view('admin.posts.edit', compact(['post', 'category', 'tags']));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\post  $post
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, post $post)
    {
        $this->validate($request, [
            'title' => 'required|unique:posts,title,' . $post->id,
            'description' => 'required',
            'category_id' => 'required',
        ]);

        $post->title       = ucfirst($request->title);
        $post->slug        = str::slug($request->title, '-');
        $post->description = $request->description;
        $post->category_id = $request->category_id;
        $post->user_id     = auth()->user()->id;
        $post->tags()->sync($request->tags);

        $unlinkImgName = $post->image;

        if ($request->hasFile('image')) {
            $image = $request->image;
            $newImgName = time() . '-' . $image->getClientOriginalName();
            if (file_exists(public_path("/storage/post/'.$unlinkImgName"))) {
                unlink('storage/post/' . $unlinkImgName);
            }
            $image->move('storage/post/', $newImgName);
            $post->image = $newImgName;
        }
        $post->save();
        return redirect()->route('post.index');
        Session::flash('success', 'Post Updated Successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\post  $post
     * @return \Illuminate\Http\Response
     */

    public function delete($id)
    {
        $delpostId = post::find($id);

        $delpostId->delete();
        return ['success' => true, 'message' => 'Delete successfully'];
    }
}
