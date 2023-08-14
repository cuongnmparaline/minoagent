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
                        <p class="mb-2">Total Account</p>
                        <h6 class="mb-0">{{ $accounts->count() }}</h6>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3">
                <div class="bg-light rounded d-flex align-items-center justify-content-between p-4">
                    <i class="fa fa-chart-line fa-3x text-primary"></i>
                    <div class="ms-3">
                        <p class="mb-2">Balance</p>
                        <h6 class="mb-0">{{ $customer->balance }}</h6>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3">
                <div class="bg-light rounded d-flex align-items-center justify-content-between p-4">
                    <i class="fa fa-heart fa-3x text-primary"></i>
                    <div class="ms-3">
                        <p class="mb-2">Live</p>
                        <h6 class="mb-0">{{ $accounts->where('status', 'Live')->count() }}</h6>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3">
                <div class="bg-light rounded d-flex align-items-center justify-content-between p-4">
                    <i class="fa fa-chart-area fa-3x text-primary"></i>
                    <div class="ms-3">
                        <p class="mb-2">Die</p>
                        <h6 class="mb-0">{{ $accounts->where('status', 'Die')->count() }}</h6>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3">
                <div class="bg-light rounded d-flex align-items-center justify-content-between p-4">
                    <i class="fa fa-arrow-left fa-3x text-primary"></i>
                    <div class="ms-3">
                        <p class="mb-2">Comeback</p>
                        <h6 class="mb-0">{{ $accounts->where('status', 'Comeback')->count() }}</h6>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container-fluid pt-4 px-4">
        <form action="" method="get">
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
                        <input type="text" class="form-control"
                               placeholder="Name" name="code" value="{{ request('code') }}" >
                        <label for="floatingInput">Code</label>
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
                    </div>
                    <div class="form-floating mb-3">
                        <a href="{{ route('customer.account') }}" type="submit" class="btn btn-sm btn-primary" >Clear</a>
                    </div>
                </div>
            </div>
        </form>
        <div class="bg-light text-center rounded p-4">
            <div class="table-responsive">
                <table class="table text-start align-middle table-bordered table-hover mb-0">
                    <thead>
                    <tr class="text-dark">
                        <th scope="col">STT</th>
                        <th scope="col">Name</th>
                        <th scope="col">Code</th>
                        <th scope="col">Status</th>
                        <th scope="col">Recent Date</th>
                        <th scope="col">Recent Amount</th>
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
                            <a class="btn btn-sm btn-primary" href="{{ route('customer.account.show', ['id' => $account->id]) }}">Show</a>
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
            </div>
        </div>
    </div>
@endsection

