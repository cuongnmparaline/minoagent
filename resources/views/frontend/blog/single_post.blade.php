@extends('frontend.layouts.main')
@section('style')
<style>
  .single-post-sidebar ul li {
    margin-bottom: 5px;
  }
  .single-post-sidebar ul li a {
    color: black;
  }
  .single-post-sidebar ul li a:hover {
    color: #fd8127;
  }
</style>
@endsection
@section('content')
<div class="container mt-5 pt-5 mb-5">
  <div class="row justify-content-center mt-5">
    <div class="col-8 center-block">
      <div class="label-detail-post mb-4">
          <div class="label-detail-post-author">
              <a href="#" class="">{{ $post->author->name ?? '' }}</a>
              <span class="fa-regular fa-clock"> <small>Created on {{ $post->created_at ?? '' }}</small></span>
          </div>
          <div class="label-detail-post-command">
            @foreach ($post->post_categories as $categories)
              @foreach ($categories->post_categories as $category)
              <button class="btn-secondary btn-sm">{{ $category->name }}</button>
              @endforeach
            @endforeach
          </div>
      </div>
      <h2 class="mb-2">{{ $post->title }}</h2>
      <p class="mb-3">{{ $post->summary }}</p>
      {!! $post->content !!}
    </div>
    <div class="col-3 center-block">
      <div class="single-post-sidebar">
        <span class="title" style="background-color: #fd8127;
    padding: 10px 10px 10px 10px;
    border-radius: 3px;
    color: white;">RELATED POSTS</span>
        <ul style="margin-top: 15px;">
          @foreach ($postRelated as $post_related)
          <li><a href="{{ route('frontend.blog.show', ['slug' => $post->slug]) }}">{{ $post_related->title }}</a></li>
          @endforeach
        </ul>
      </div>
    </div>
  </div>
</div>
@endsection