@extends('layouts.main')
@section('content')
    <div class="container-fluid pt-4 px-4">
        <div class="bg-light text-center rounded p-4">
            @if (Session::has('success'))
                <div class="alert alert-success" style="text-align: center;">
                    <strong>{{ session()->get('success') }}</strong>
                </div>
            @endif
            <div class="table-responsive">
                <table class="table text-start align-middle table-bordered table-hover mb-0">
                    <thead>
                    <tr class="text-dark">
                        <th scope="col">STT</th>
                        <th scope="col">Name</th>
                        <th scope="col">Code</th>
                        <th scope="col">Status</th>
                        <th scope="col">Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($accounts as $account)
                        <tr>
                            <td>{{ ++$loop->index }}</td>
                            <td>{{ $account['name'] }}</td>
                            <td>{{ $account['code'] }}</td>
                            <td>{{ $account['status'] }}</td>
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
{{--                                <a class="btn btn-sm btn-primary" href="{{ route('management.account.edit', ['id' => $account->id]) }}">Edit</a>--}}
                                <button data-url="{{route('customer.group.remove', ['id'=> $groupId, 'accountId' => $account->id])}}"
                                        onclick="showDeleteModal(this)" data-toggle="modal" data-target="#myModal" class="btn btn-sm btn-danger">Remove
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
{{--                <div class="d-flex align-items-center justify-content-center mt-4">--}}
{{--                <div class="d-flex align-items-center justify-content-center mt-4">--}}
{{--                    {{ $accounts->links() }}--}}
{{--                </div>--}}
            </div>
        </div>
    </div>
@endsection

