@extends('frontend.layouts.main')
@section('style')
<style>
    .latest-news-area .pagination {
        justify-content: center;
    }
</style>
@endsection
@section('content')
<div id="blog" class="latest-news-area section">
<div class="container" style="margin-top: 30px;">
    <div class="row justify-content-center mt-5">
        <div class="col-lg-4 col-md-6 col-12 text-center">
            <a href="{{ route('frontend.blog') }}" class="btn btn-primary">Blog</a>
        </div>
    </div>
    <div class="row">
        @foreach ($posts as $key => $post)
        <div class="col-lg-4 col-md-6 col-12">
            <!-- Single News -->
            <div class="single-news">
                <div class="image">
                    <a href="{{ route('frontend.blog.show', ['slug' => $post->slug]) }}"><img class="thumb" style="height: 300px;" src="{{ $post->image ? asset('/storage/images/'.$post->image) : asset('storage/images/noimg.png') }}" alt="{{ $post->slug }}"/></a>
                    <div class="meta-details">
                        <img class="thumb" src="{{ $post->image ? asset('/storage/images/'.$post->image) : asset('storage/images/noimg.png') }}" alt="Author"/>
                        <span>BY <?= strtoupper($post->author->name); ?></span>
                    </div>
                </div>
                <div class="content-body">
                    <h4 class="title">
                        <a href="{{ route('frontend.blog.show', ['slug' => $post->slug]) }}"> {{ $post->title ?? '' }} </a>
                    </h4>
                    <p>
                    {{ $post->summary ?? '' }}
                    </p>
                </div>
            </div>
            <!-- End Single News -->
        </div>
        @endforeach
    </div>
    <div class="row justify-content-center mt-5">
        <div class="col-lg-4 col-md-6 col-12 text-center">
        {{ $posts->links() }}
        </div>
    </div>
</div>
</div>
@endsection