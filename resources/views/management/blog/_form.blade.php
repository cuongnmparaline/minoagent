  <?php $selectedValues = []; ?>
  @if(Request::is('management/post/edit/*'))
      {{ Form::model($post, ['route' => ['management.post.update', $post['id']], 'method' => 'POST', 'enctype' => "multipart/form-data"]) }}
      @foreach ($post->post_categories as $categories)
        @foreach ($categories->post_categories as $category)
          {{ $selectedValues[] = $category->id }}
        @endforeach
      @endforeach
  @else
      {{ Form::open(['route' => 'management.post.store', 'enctype' => "multipart/form-data"]) }}
  @endif
      <div class="form-group mb-3">
          {{ Form::label('title', 'Tiêu đề', array('class' => $errors->has('title') ? ' is-invalid' : '') )}}
            @error('title')
              <div class="invalid-feedback">
                {{ $message }}
              </div>
            @enderror
          {{ Form::text('title', null, array('class' => 'form-control')) }}
      </div>
      <div class="form-group mb-3">
          {{ Form::label('summary', null, array('class' => $errors->has('summary') ? ' is-invalid' : '') )}}
          @if($errors->has('summary'))
              <div class="invalid-feedback">
                  {{ $errors->first('summary') }}
              </div>
          @endif
          {{ Form::text('summary', null, array('class' => 'form-control')) }}
      </div>
      <div class="form-group mb-3">
          {{ Form::label('content', 'Nội dung', array('class' => $errors->has('content') ? ' is-invalid' : '') )}}
          @if($errors->has('content'))
              <div class="invalid-feedback">
                  {{ $errors->first('content') }}
              </div>
          @endif
          {{ Form::textarea('content', null, array('class' => 'form-control', 'id' => 'ckeditor')) }}
      </div>
      <div class="form-group mb-3">
        {{ Form::label('content', 'Ảnh đại diện', array('for' => 'image', 'class' => $errors->has('image') ? ' is-invalid' : '')) }}
        @error('image')
            <div class="invalid-feedback">
            {{ $message }}
            </div>
        @enderror
        @if(isset($post->image))
            <img src="{{ $post->image ? asset('storage/images/'.$post->image) : '' }}" style="width: 100px;height: 80px;object-fit: cover;"/><br><br>
        @endif
        <input type="file" id="image" name="image" class="form-control cursor-pointer"/>
      </div>
      <div class="form-group mb-3">
          {{ Form::label('content', 'Danh mục', array('for' => 'category', 'class' => $errors->has('category_id') ? ' is-invalid' : '')) }}
          @if($errors->has('category_id'))
              <div class="invalid-feedback">
                  {{ $errors->first('category_id') }}
              </div>
          @endif
          {{ Form::select('category_id[]', $post_categories, $selectedValues, array('class' => 'form-control', 'id' => 'select-category', 'multiple' => 'multiple')) }}
      </div>
      <div class="form-group mb-3">
          {{ Form::label('content', 'Trạng thái', array('for' => 'status', 'class' => $errors->has('category_id') ? ' is-invalid' : '')) }}
          @if($errors->has('status_id'))
              <div class="invalid-feedback">
                  {{ $errors->first('status_id') }}
              </div>
          @endif
          {{ Form::select('status_id', $post_status, null, array('class' => 'form-control')) }}
      </div>
  @if(Request::is('management/post/edit/*'))
  {{ Form::submit('Update', ['name' => 'submit', 'class' => 'btn btn-success']) }}
  @else
  {{ Form::submit('Save', ['name' => 'submit', 'class' => 'btn btn-success']) }}
  @endif
  
  {{ Form::close() }}
