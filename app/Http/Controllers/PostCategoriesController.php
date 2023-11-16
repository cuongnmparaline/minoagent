<?php

namespace App\Http\Controllers;

use App\Http\Requests\PostCategories\CreateRequest;
use App\Http\Requests\PostCategories\UpdateRequest;
use App\Models\PostCategories;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostCategoriesController extends Controller
{
    public function index()
    {
        try {
            $postCategories = PostCategories::when(!empty(request('name')), function ($query) {
                return $query->where('name', 'LIKE', '%' . request('name') . '%');
            })->when(!empty(request('date')), function ($query) {
                return $query->where('created_at', 'LIKE', '%' . request('date') . '%');
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        } catch (\Exception $e) {
            session()->flash('error', __('messages.listPostFail'));
        }

        return view('management.postCategories.index', [
            'postCategories' => $postCategories
        ]);
    }

    public function create()
    {
        return view('management.postCategories.create');
    }

    public function store(CreateRequest $request)
    {
        $data = $request->validated();

        try {
            $data['author_id'] = Auth::guard('admin')->id();
            PostCategories::create($data);

            session()->flash('success', __('messages.createPostSuccess'));
        } catch (\Exception $e) {
            session()->flash('error', __('messages.createPostFail'));
        }

        return redirect()->route('management.postCategories');
    }

    public function edit($id)
    {
        try { 
            $postCategories = PostCategories::find($id);
        } catch (\Exception $commonException) {
            session()->flash('error', __('messages.listPostFail'));
            // Email cho dev
        }

        return view('management.postCategories.edit', [
            'postCategories' => $postCategories,
        ]);
    }

    public function update(UpdateRequest $request, $id)
    {
        $data = $request->validated();

        try {
            $postCategories = PostCategories::find($id);

            if ($postCategories) {
                $postCategories->update($data);
                session()->flash('success', __('messages.updatePostSuccess'));
            } else {
                session()->flash('error', __('messages.updatePostFailNotFound'));
            }
        } catch (\Exception $e) {
            session()->flash('error', __('messages.updatePostFail'));
        }
        
        return redirect()->route('management.postCategories');
    }

    public function delete($id)
    {
        $postCategories = PostCategories::find($id);

        if ($postCategories->delete()){
            session()->flash('success', __('messages.deletePostSuccess'));
        } else {
            session()->flash('error', __('messages.deletePostFail'));
        }

        return redirect()->route('management.postCategories');
    }
}
