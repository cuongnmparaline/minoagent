<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    public function index()
    {

    }

    public function show($id)
    {
        $post = Post::find($id);

        return view('frontend.blog.single_post', [
            'post' => $post
        ]);
    }
}
