<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\PostStatus;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    public function index()
    {
        try { 
            $posts = Post::orderBy('created_at', 'asc')->paginate(9);
        } catch (\Exception $commonException) {
            session()->flash('error', __('messages.listPostFail'));
        }

        return view('frontend.blog.index', [
            'posts' => $posts
        ]);
    }

    public function show($slug)
    {
        $post = Post::where(['slug' => $slug])->first();

        $postRelated = Post::whereHas('post_categories', function ($query) use ($post) {
            $query->whereIn('post_categories_id', $post->post_categories->pluck('post_categories_id'));
        })
        ->orderBy('created_at', 'desc')
        ->limit(5)
        ->get();

        return view('frontend.blog.single_post', [
            'post' => $post,
            'postRelated' => $postRelated
        ]);
    }
}
