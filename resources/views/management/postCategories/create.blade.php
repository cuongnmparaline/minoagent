@extends('layouts.main')

@section('content')
      <div class="container-fluid pt-4 px-4">
        <div class="row g-12">
            <div class="col-sm-12 col-xl-12">
                <div class="bg-light rounded h-100 p-4">
                    <h6 class="mb-4">Create Post Categories</h6>
                    @include('management.postCategories._form')
                </div>
            </div>
        </div>
    </div>
@endsection
