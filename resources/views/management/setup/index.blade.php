@extends('layouts.main')

@section('content')
    <div class="container-fluid pt-4 px-4">
        <div class="row">
            @if (Session::has('success'))
                <div class="alert alert-success" style="text-align: center;">
                    <strong>{{ session()->get('success') }}</strong>
                </div>
            @endif
            @if (Session::has('error'))
                <div class="alert alert-danger" style="text-align: center;">
                    <strong>{{ session()->get('error') }}</strong>
                </div>
            @endif
        </div>
        <div class="row">
          <div class="col-md-4 col-sm-12  p-1">
            <div class="bg-light text-center rounded p-4">
              <h6 class="mb-0">
                  <a href="{{ route('management.postStatus') }}"><i class="bi bi-plus-circle-fill"></i> Management Post Status</a>
              </h6>
            </div>
          </div>

          <div class="col-md-4 col-sm-12  p-1">
            <div class="bg-light text-center rounded p-4">
              <h6 class="mb-0">
                  <a href="{{ route('management.postCategories') }}"><i class="bi bi-plus-circle-fill"></i> Management Post Categories</a>
              </h6>
            </div>
          </div>

        </div>
            
    </div>
@endsection

