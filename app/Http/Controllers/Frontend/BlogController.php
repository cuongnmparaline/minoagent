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

    }

    public function show($id)
    {
        $post = Post::find($id);

        $postRelated = Post::whereHas('post_categories', function ($query) use ($post) {
            $query->whereIn('post_categories_id', $post->post_categories->pluck('post_categories_id'));
        })
        ->orderBy('created_at', 'asc')
        ->limit(5)
        ->get();

        return view('frontend.blog.single_post', [
            'post' => $post,
            'postRelated' => $postRelated
        ]);
    }
}
