  @if(Request::is('management/postStatus/edit/*'))
      {{ Form::model($postStatus, ['route' => ['management.postStatus.update', $postStatus['id']], 'method' => 'POST', 'enctype' => "multipart/form-data"]) }}
  @else
      {{ Form::open(['route' => 'management.postStatus.store', 'enctype' => "multipart/form-data"]) }}
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
  @if(Request::is('management/postStatus/edit/*'))
  {{ Form::submit('Update', ['name' => 'submit', 'class' => 'btn btn-success']) }}
  @else
  {{ Form::submit('Save', ['name' => 'submit', 'class' => 'btn btn-success']) }}
  @endif
  
  {{ Form::close() }}
