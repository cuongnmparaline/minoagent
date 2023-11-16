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
        <form action="{{ route('management.postStatus') }}" method="get">
            <div class="row">
                <div class="col-3 col-xl-3">
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control"
                            placeholder="Name" name="name" value="{{ request('name') }}">
                        <label for="floatingInput">Name</label>
                    </div>
                </div>
                <div class="col-3 col-xl-3">
                    <div class="form-floating mb-3">
                        <input type="date" class="form-control"
                            placeholder="Name" name="date" value="{{ request()->get('date') }}" >
                        <label for="floatingInput">Date</label>
                    </div>
                </div>
                <div class="col-3 col-xl-3">
                    <div class="form-floating mb-3">
                        <input type="submit" class="btn btn-sm btn-primary" value="Search">
                        <a href="{{ route('management.post') }}" type="submit" class="btn btn-sm btn-primary">Clear</a>
                    </div>
                </div>
            </div>
        </form>
        <div class="bg-light text-center rounded p-4">
            <div class="d-flex align-items-center justify-content-between mb-4">
                <h6 class="mb-0">
                    <a href="{{ route('management.postStatus.create') }}"><i class="bi bi-plus-circle-fill"></i> Add</a>
                </h6>
            </div>
            <div class="row">
                <div class="col-md-6">
                    {{ $postStatus->links() }}
                </div>
            </div>
            <div class="table-responsive">
              <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0" style="text-align: center;">
                  <thead>
                  <tr>
                      <th>STT</th>
                      <th>Name</th>
                      <th>Created Date</th>
                      <th>Action</th>
                  </tr>
                  </thead>
                  <tfoot>
                  <tr>
                      <th>STT</th>
                      <th>Name</th>
                      <th>Created Date</th>
                      <th>Action</th>
                  </tr>
                  </tfoot>
                  <tbody>
                  @forelse ($postStatus as $key => $status)
                      <tr data-entry-id="{{ $status->id }}">
                          <td>{{ $postStatus->firstItem() + $key }}</td>
                          <td>{{ $status->name }}</td>
                          <td>{{ $status->created_at ?? ''}}</td>
                          <td>
                              <div class="btn-group" role="group">
                              <a href="{{ route('management.postStatus.edit', ['id' => $status->id]) }}" class="btn btn-warning btn-edit-post btn-sm"><i class="fa fa-pen"></i></a>
                              <a href="{{ route('management.postStatus.delete', ['id' => $status->id]) }}" class="btn btn-danger btn-delete-post btn-sm"><i class="fa fa-trash" aria-hidden="true"></i></a>
                              </div>
                          </td>
                      </tr>
                  @empty
                      <tr>
                          <p>No Data</p>
                      </tr>
                  @endforelse
                  </tbody>
              </table>
          </div>
        </div>
    </div>
@endsection

