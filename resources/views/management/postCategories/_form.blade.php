<?php
use App\Models\PostCategories;

$status = [
  1 => PostCategories::ACTIVE,
  2 => PostCategories::DEACTIVE
];

?>
@if(Request::is('management/postCategories/edit/*'))
      {{ Form::model($postCategories, ['route' => ['management.postCategories.update', $postCategories['id']], 'method' => 'POST', 'enctype' => "multipart/form-data"]) }}
  @else
      {{ Form::open(['route' => 'management.postCategories.store', 'enctype' => "multipart/form-data"]) }}
  @endif
      <div class="form-group mb-3">
          {{ Form::label('name', 'Name', array('class' => $errors->has('name') ? ' is-invalid' : '') )}}
          @if($errors->has('name'))
              <div class="invalid-feedback">
                  {{ $errors->first('name') }}
              </div>
          @endif
          {{ Form::text('name', null, array('class' => 'form-control')) }}
      </div>
      <div class="form-group mb-3">
          {{ Form::label('description', 'Description', array('class' => $errors->has('description') ? ' is-invalid' : '') )}}
          @if($errors->has('description'))
              <div class="invalid-feedback">
                  {{ $errors->first('description') }}
              </div>
          @endif
          {{ Form::text('description', null, array('class' => 'form-control')) }}
      </div>
      <div class="form-group mb-3">
          {{ Form::label('content', 'Status', array('for' => 'status', 'class' => $errors->has('status') ? ' is-invalid' : '')) }}
          @if($errors->has('status'))
              <div class="invalid-feedback">
                  {{ $errors->first('status') }}
              </div>
          @endif
          {{ Form::select('status', $status, null, array('class' => 'form-control')) }}
      </div>
  @if(Request::is('management/postCategories/edit/*'))
  {{ Form::submit('Update', ['name' => 'submit', 'class' => 'btn btn-success']) }}
  @else
  {{ Form::submit('Save', ['name' => 'submit', 'class' => 'btn btn-success']) }}
  @endif
  
  {{ Form::close() }}
