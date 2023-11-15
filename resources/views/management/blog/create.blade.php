@extends('layouts.main')

@section('style')
<link href="{{ asset('select2/css/select2.min.css') }}" rel="stylesheet" />
@endsection

@section('content')
      <div class="container-fluid pt-4 px-4">
        <div class="row g-12">
            <div class="col-sm-12 col-xl-12">
                <div class="bg-light rounded h-100 p-4">
                    <h6 class="mb-4">Add New Post</h6>
                    @include('management.blog._form')
                </div>
            </div>
        </div>
    </div>
@endsection
@section('javascript')
    @parent
    <script src="{{ asset('select2/js/select2.min.js') }}"></script>
    <script src="{{ asset('ckeditor/ckeditor.js') }}"></script>
    <script src="{{ asset('ckeditor/adapters/jquery.js') }}"></script>
    <script>
        $('#ckeditor-post').ckeditor();
        CKEDITOR.replace('ckeditor', {
            filebrowserUploadUrl: "{{route('management.post.ckeditorUpload', ['_token' => csrf_token()])}}",
            filebrowserUploadMethod: 'form'
        });

        CKEDITOR.config.width = '100%';
        CKEDITOR.config.height = 600;

        $(document).ready(function() {
            $('#select-category').select2({
              placeholder: "Chọn Danh mục"
            });
        });
    </script>
@endsection
