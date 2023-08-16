@extends('layouts.main')
@section('content')
    <div class="container-fluid pt-4 px-4">
        <table class="table table-borderless">
            <tr>
                <td><b>Name:</b></td>
                <td>{{ $customer->name }}</td>
                <td></td>
                <td><b>Email:</b></td>
                <td>{{ $customer->email }}</td>
            </tr>
            <tr>
                <td><b>Balance:</b></td>
                <td>{{ $customer->balance }}</td>
                <td></td>
                <td><b>Fee:</b></td>
                <td>{{ $customer->fee }}</td>
            </tr>
        </table>
    </div>
    <div class="container-fluid pt-4 px-4">
        <div class="bg-light text-center rounded p-4">
            <div class="d-flex align-items-center justify-content-between mb-4">
                <h6 class="mb-0">
                </h6>
                <a href="{{ route('management.customer.exportAccount', ['customer' => $customer->id]) }}">Export Excel</a>
            </div>
            <div class="table-responsive">
                <table class="table text-start align-middle table-bordered table-hover mb-0">
                    <thead>
                    <tr class="text-dark">
                        <th scope="col">STT</th>
                        <th scope="col">Name</th>
                        <th scope="col">Code</th>
                        <th scope="col">Customer</th>
                        <th scope="col">Status</th>
                        <th scope="col">Recent Date</th>
                        <th scope="col">Recent Amount</th>
                        <th scope="col">Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($customer->accounts as $account)
                        <tr>
                            <td>{{ ++$loop->index }}</td>
                            <td>{{ $account['name'] }}</td>
                            <td>{{ $account['code'] }}</td>
                            <td>{{ $account->customer->name }}</td>
                            <td>{{ $account['status'] }}</td>
                            <td>@if(!empty($account->reports->last())) {{ $account->reports->last()->date }} @endif</td>
                            <td>@if(!empty($account->reports->last())) {{ $account->reports->last()->amount }} @endif</td>
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
                                <a class="btn btn-sm btn-primary" href="{{ route('management.account.edit', ['id' => $account->id]) }}">Edit</a>
                                <button data-url="{{route('management.account.delete', ['id'=>$account->id])}}"
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
{{--                <div class="d-flex align-items-center justify-content-center mt-4">--}}
{{--                <div class="d-flex align-items-center justify-content-center mt-4">--}}
{{--                    {{ $accounts->links() }}--}}
{{--                </div>--}}
            </div>
        </div>
    </div>
@endsection

