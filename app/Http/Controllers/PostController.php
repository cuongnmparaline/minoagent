<?php

namespace App\Http\Controllers;

use App\Http\Requests\Blog\CreateRequest;
use App\Http\Requests\Blog\UpdateRequest;
use App\Models\Post;
use App\Models\PostCategories;
use App\Models\PostHasCategories;
use App\Models\PostStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
// use Illuminate\Support\Facades\File;

class PostController extends Controller
{
    public function index()
    {
        try { 
            $posts = Post::when(!empty(request('title')), function ($query) {
                return $query->where('title', 'LIKE', '%' . request('title') . '%');
            })->when(!empty(request('date')), function ($query) {
                return $query->where('created_at', 'LIKE', '%' . request('date') . '%');
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);
            
        } catch (\Exception $commonException) {
            session()->flash('error', __('messages.listPostFail'));
        }

        return view('management.blog.index', [
            'posts' => $posts
        ]);
    }

    public function create()
    {
        try {
            $post_categories = PostCategories::all()->pluck('name', 'id')->toArray();
            $post_status = PostStatus::all()->pluck('name', 'id')->toArray();
        } catch (\Exception $commonException) {
            session()->flash('error', __('messages.listPostFail'));
            // Email cho dev
        }

        return view('management.blog.create', [
            'post_categories' => $post_categories,
            'post_status' => $post_status
        ]);
    }

    public function store(CreateRequest $request)
    {
        $data = $request->validated();

        try {
            if (isset($data['image'])) {
                $file = self::saveImageByStore($request->file('image'));
                $data['image'] = $file;
            }
            
            $data['user_id'] = Auth::guard('admin')->id();
            $data['slug'] = convertToSlug($request->title);
            $post = Post::create($data);

            if (isset($data['category_id']) && count($data['category_id'])) {
                foreach ($data['category_id'] as $category_id) {
                    $post_has_categories = new PostHasCategories();
                    $post_has_categories->post_id = $post->id;
                    $post_has_categories->post_categories_id = $category_id;
                    $post_has_categories->save();
                }
            }
            session()->flash('success', __('messages.createPostSuccess'));
        } catch (\Exception $e) {
            session()->flash('error', __('messages.createPostFail'));
        }

        return redirect()->route('management.post');
    }

    public function edit($id)
    {
        try { 
            $post = Post::find($id);

            $post_categories = PostCategories::all()->pluck('name', 'id')->toArray();
            $post_status = PostStatus::all()->pluck('name', 'id')->toArray();
        } catch (\Exception $commonException) {
            session()->flash('error', __('messages.listPostFail'));
            // Email cho dev
        }

        return view('management.blog.edit', [
            'post' => $post,
            'post_categories' => $post_categories,
            'post_status' => $post_status
        ]);
    }

    public function update(UpdateRequest $request, $id)
    {
        $data = $request->validated();

        try {

            if (isset($data['image'])) {
                $relativePath = self::saveImageByStore($request->file('image'));
                $data['image'] = $relativePath;
            }
    
            $post = Post::find($id);
            if ($post->image) {
                Storage::delete('public/'.$post->image);
            }
            
            $data['slug'] = convertToSlug($request->title);
            $post->update($data);

            $post_has_categories = PostHasCategories::where(['post_id' => $post->id])->delete();
            
            if (isset($data['category_id']) && count($data['category_id'])) {
                foreach ($data['category_id'] as $category_id) {
                    $post_has_categories = new PostHasCategories();
                    $post_has_categories->post_id = $post->id;
                    $post_has_categories->post_categories_id = $category_id;
                    $post_has_categories->save();
                }
            }
            session()->flash('success', __('messages.updatePostSuccess'));
        } catch (\Exception $e) {
            session()->flash('error', __('messages.updatePostFail'));
        }
        
        return redirect()->route('management.post');
    }

    public function delete($id)
    {
        $post = Post::find($id);

        if ($post->delete()){
            if ($post->image) {
                Storage::delete('public/'.$post->image);
            }

            session()->flash('success', __('messages.deletePostSuccess'));
        } else {
            session()->flash('error', __('messages.deletePostFail'));
        }

        return redirect()->route('management.post');
    }

    public function show()
    {

    }

    public function ckeditorUpload(Request $request)
    {
        $response = [
            'message' => 'Something Wrong!',
            'status' => False,
            'script' => ''
        ];

        if ($request->hasFile('upload')) {
            $file = $request->file('upload');
            $type = $file->extension();
            $fileName = Str::random() . '.' . $type;

            $absolutePath = 'public/images/';
            $relativePath = 'storage/images/';
            $file->storeAs($absolutePath, $fileName);

            $ckeditorFunction = $request->input('CKEditorFuncNum');
            $url = asset($relativePath . $fileName);

            $request['message'] = 'Upload Image Success!';
            $response['script'] = "<script>window.parent.CKEDITOR.tools.callFunction($ckeditorFunction, '$url', 'Upload Image Success!')</script>";

            @header('Content-type: text/html; charset=utf-8');
            echo $response['script'];
        }
    }

    private static function saveImageByStore($image)
    {
        $type = $image->extension();
        $file = Str::random() . '.' . $type;
        $absolutePath = 'public/images/';

        // if (!File::exists($absolutePath)) {
        //     File::makeDirectory($absolutePath, 0755, true);
        // }

        $image->storeAs($absolutePath, $file);
        return $file;
    }
}
