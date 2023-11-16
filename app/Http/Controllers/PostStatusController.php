<?php

namespace App\Http\Controllers;

use App\Http\Requests\PostStatus\CreateRequest;
use App\Http\Requests\PostStatus\UpdateRequest;
use App\Models\PostStatus;
use Illuminate\Http\Request;

class PostStatusController extends Controller
{
    public function index()
    {
        try {
            $postStatus = PostStatus::when(!empty(request('name')), function ($query) {
                return $query->where('name', 'LIKE', '%' . request('name') . '%');
            })->when(!empty(request('date')), function ($query) {
                return $query->where('created_at', 'LIKE', '%' . request('date') . '%');
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        } catch (\Exception $e) {
            session()->flash('error', __('messages.listPostFail'));
        }

        return view('management.postStatus.index', [
            'postStatus' => $postStatus
        ]);
    }

    public function create()
    {
        return view('management.postStatus.create');
    }

    public function store(CreateRequest $request)
    {
        $data = $request->validated();

        try {
            PostStatus::create($data);

            session()->flash('success', __('messages.createPostSuccess'));
        } catch (\Exception $e) {
            session()->flash('error', __('messages.createPostFail'));
        }

        return redirect()->route('management.postStatus');
    }

    public function edit($id)
    {
        try { 
            $postStatus = PostStatus::find($id);
        } catch (\Exception $commonException) {
            session()->flash('error', __('messages.listPostFail'));
            // Email cho dev
        }

        return view('management.postStatus.edit', [
            'postStatus' => $postStatus,
        ]);
    }

    public function update(UpdateRequest $request, $id)
    {
        $data = $request->validated();

        try {
            $postStatus = PostStatus::find($id);

            if ($postStatus) {
                $postStatus->update($data);
                session()->flash('success', __('messages.updatePostSuccess'));
            } else {
                session()->flash('error', __('messages.updatePostFailNotFound'));
            }
        } catch (\Exception $e) {
            session()->flash('error', __('messages.updatePostFail'));
        }
        
        return redirect()->route('management.postStatus');
    }

    public function show()
    {

    }

    public function delete($id)
    {
        $postStatus = PostStatus::find($id);

        if ($postStatus->delete()){
            session()->flash('success', __('messages.deletePostSuccess'));
        } else {
            session()->flash('error', __('messages.deletePostFail'));
        }

        return redirect()->route('management.postStatus');
    }
}
