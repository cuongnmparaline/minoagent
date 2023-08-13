@extends('layouts.main')

@section('content')
    <div class="container-fluid pt-4 px-4">
        <div class="row g-4">
            @if (Session::has('success'))
                <div class="alert alert-success" style="text-align: center;">
                    <strong>{{ session()->get('success') }}</strong>
                </div>
            @endif
            <div class="col-sm-6 col-xl-3">
                <div class="bg-light rounded d-flex align-items-center justify-content-between p-4">
                    <i class="fa fa-chart-line fa-3x text-primary"></i>
                    <div class="ms-3">
                        <p class="mb-2">Total History</p>
                        <h6 class="mb-0">{{ $histories->count() }}</h6>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid pt-4 px-4">
        <div class="bg-light text-center rounded p-4">
            <div class="d-flex align-items-center justify-content-between mb-4">
                <h6 class="mb-0">
                    <a href="{{ route('management.history.create') }}"><i class="bi bi-plus-circle-fill"></i> Add</a>
                </h6>
                <a href="">Export Excel</a>
            </div>
            <div class="table-responsive">
                <table class="table text-start align-middle table-bordered table-hover mb-0">
                    <thead>
                    <tr class="text-dark">
                        <th scope="col">STT</th>
                        <th scope="col">Customer Name</th>
                        <th scope="col">@sortablelink('date', 'Date')</th>
                        <th scope="col">@sortablelink('amount', 'Amount')</th>
                        <th scope="col">Hash Code</th>
                        <th scope="col">Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($histories as $history)
                    <tr>
                        <td>{{ ++$loop->index }}</td>
                        <td>{{ $history->customer->name }}</td>
                        <td>{{ $history['date'] }}</td>
                        <td>{{ $history['amount'] }}</td>
                        <td>{{ $history['hashcode'] }}</td>
                        <td>
                            <div class="modal" id="myModal">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h4 class="modal-title">Confirm</h4>
                                        </div>
                                        <div class="modal-body">
                                            <p class="text-left">Are you sure?</p>
                                        </div>
                                        <div class="modal-footer justify-between">
                                            <button type="button" class="btn btn-default"
                                                    data-dismiss="modal">Cancel
                                            </button>
                                            <a href="" class="btn btn-danger btn-agree">OK</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <a class="btn btn-sm btn-primary" href="{{ route('management.history.edit', ['id' => $history['id'] ]) }}">Edit</a>
                            <button data-url="{{route('management.history.delete', ['id'=>$history->id])}}"
                                    onclick="showDeleteModal(this)" data-toggle="modal" data-target="#myModal" class="btn btn-sm btn-danger">Delete
                            </button>
                            <script>
                                function showDeleteModal(e){
                                    let url = $(e).data('url');
                                    $('#myModal').find('.btn-agree').attr('href', url);
                                }
                            </script>
                        </td>
                    </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            <div class="d-flex align-items-center justify-content-center mt-4">
                {{ $histories->links() }}
            </div>
        </div>
    </div>
@endsection

