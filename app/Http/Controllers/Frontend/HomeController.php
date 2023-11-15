<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $posts = Post::where(['status_id' => 1])->orderBy('posts.created_at', 'asc')->limit(3)->get();

        return view('frontend.home.index', [
            'posts' => $posts
        ]);
    }
}
