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
                        <p class="mb-2">Total Not Pay</p>
                        <h6 class="mb-0">{{ $notpays->count() }}</h6>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3">
                <div class="bg-light rounded d-flex align-items-center justify-content-between p-4">
                    <i class="fa fa-chart-line fa-3x text-primary"></i>
                    <div class="ms-3">
                        <p class="mb-2">Total Amount</p>
                        <h6 class="mb-0">{{ $notpays->sum('amount') }}</h6>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container-fluid pt-4 px-4">
{{--        <form action="{{ route('management.customer.export') }}" method="get">--}}
{{--            <div class="row">--}}
{{--                <div class="col-3 col-xl-3">--}}
{{--                    <div class="form-floating mb-3">--}}
{{--                        <input type="date" class="form-control"--}}
{{--                               placeholder="Name" name="month" value="" >--}}
{{--                        <label for="floatingInput">Date to Export</label>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--                <div class="col-3 col-xl-3">--}}
{{--                    <div class="form-floating mb-3">--}}
{{--                        <input type="submit" class="btn btn-sm btn-primary" value="Export">--}}
{{--                    </div>--}}
{{--                </div>--}}

{{--            </div>--}}
{{--        </form>--}}
        <div class="bg-light text-center rounded p-4">
            <div class="d-flex align-items-center justify-content-between mb-4">
                <h6 class="mb-0">
                    <a href="{{ route('management.notpay.create') }}"><i class="bi bi-plus-circle-fill"></i> Add</a>
                </h6>
            </div>
            <div class="table-responsive">
                <table class="table text-start align-middle table-bordered table-hover mb-0">
                    <thead>
                    <tr class="text-dark">
                        <th scope="col">ID</th>
                        <th scope="col">Tech</th>
                        <th scope="col">Customer</th>
                        <th scope="col">Account</th>
                        <th scope="col">Amount</th>
                        <th scope="col">Link Image</th>
                        <th scope="col">Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($notpays as $notpay)
                    <tr>
                        <td>{{ $notpay['id'] }}</td>
                        <td>{{ $notpay->admin->name }}</td>
                        <td>{{ $notpay->customer->name }}</td>
                        <td>{{ $notpay->account->name }}</td>
                        <td>{{ sprintf("%.2f",  $notpay['amount']) }}</td>
                        <td>{{ $notpay['link_image'] }}</td>
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
                            <div class="justify-content-around">
                                <a class="btn btn-sm btn-warning" href="">Show</a>
                                <a class="btn btn-sm btn-primary" href="">Edit</a>
                                <button data-url=""
                                        onclick="showDeleteModal(this)" data-toggle="modal" data-target="#myModal" class="btn btn-sm btn-danger">Delete
                                </button>
                                <script>
                                    function showDeleteModal(e){
                                        let url = $(e).data('url');
                                        $('#myModal').find('.btn-agree').attr('href', url);
                                    }
                                </script>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                    </tbody>
                </table>
                <div class="d-flex align-items-center justify-content-center mt-4">
                    {{ $notpays->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection

