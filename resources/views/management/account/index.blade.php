@extends('layouts.main')
@section('content')
    <style>
        .navbar {
            height: 50px;
        }
        table.floatThead-table {
            border-top: none;
            border-bottom: none;
            background-color: #fff;
        }
    </style>
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
                        <h6 class="mb-0">{{ $totalAccount->count() }}</h6>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3">
                <div class="bg-light rounded d-flex align-items-center justify-content-between p-4">
                    <i class="fa fa-heart fa-3x text-primary"></i>
                    <div class="ms-3">
                        <p class="mb-2">Live</p>
                        <h6 class="mb-0">{{ $totalAccount->where('status', 'Live')->count() }}</h6>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3">
                <div class="bg-light rounded d-flex align-items-center justify-content-between p-4">
                    <i class="fa fa-skull fa-3x text-primary"></i>
                    <div class="ms-3">
                        <p class="mb-2">Die</p>
                        <h6 class="mb-0">{{ $totalAccount->where('status', 'Die')->count() }}</h6>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3">
                <div class="bg-light rounded d-flex align-items-center justify-content-between p-4">
                    <i class="fa fa-arrow-left fa-3x text-primary"></i>
                    <div class="ms-3">
                        <p class="mb-2">Comeback</p>
                        <h6 class="mb-0">{{ $totalAccount->where('status', 'Comeback')->count() }}</h6>
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
                        <input type="date" class="form-control"
                               placeholder="Name" name="date" value="{{ request()->get('date') }}" >
                        <label for="floatingInput">Date</label>
                    </div>
                </div>
                <div class="col-3 col-xl-3">
                    <select class="form-select form-select-lg mb-3" aria-label=".form-select-lg example" name="customer">
                        <option value="">Choose customer</option>
                        @foreach($customers as $customer)
                            <option {{getDataCreateForm('account_data', 'customer_id') == $customer->id ? "selected" : ""}} value="{{$customer->name}}">{{$customer->name}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-3 col-xl-3">
                    <div class="form-floating mb-3">
                        <input type="submit" class="btn btn-sm btn-primary" value="Search">
                        <a href="{{ route('management.account') }}" type="submit" class="btn btn-sm btn-primary">Clear</a>
                    </div>
                </div>

            </div>
        </form>
    </div>
    <div class="container-fluid pt-4 px-4">
        <div class="bg-light text-center rounded p-4">
            <div class="d-flex align-items-center justify-content-between mb-4">
                <h6 class="mb-0">
                    <a href="{{ route('management.account.create') }}"><i class="bi bi-plus-circle-fill"></i> Add</a>
                </h6>
            </div>
            <div class="table-responsive">
                <table class="table sticky-header text-start align-middle table-bordered table-hover mb-0">
                    <thead class="thead-dark">
                    @php
                        $start = \Carbon\Carbon::now()->startOfMonth();
                        $end = \Carbon\Carbon::now()->endOfMonth();
                        $dates = [];
                        while ($start->lte($end)) {
                             $dates[] = $start->copy();
                             $start->addDay();
                        }
                    @endphp
                    <tr class="text-dark">
                        <th scope="col">STT</th>
                        <th scope="col">Name</th>
                        <th scope="col">Code</th>
                        <th scope="col">Customer</th>
                        <th scope="col">Status</th>
                        @foreach($dates as $date)
                            <th>{{ $date->format('m/d/y') }}</th>
                        @endforeach
                        <th scope="col">Amount</th>
                        <th scope="col">Action</th>
                        <th scope="col">Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($accounts as $account)
                    <tr>
                        <td>{{ ++$loop->index }}</td>
                        <td>{{ $account['name'] }}</td>
                        <td>{{ $account['code'] }}</td>
                        <td>{{ $account->customer->name }}</td>
                        <td>{{ $account['status'] }}</td>
                        @foreach($dates as $date)
                            <th>{{ sprintf("%.2f", $account->reports->where('date', $date->format('Y-m-d'))->sum('amount')) }}</th>
                        @endforeach
                        <th>{{ sprintf("%.2f", $account->reports->whereBetween('date', [$dates[0]->format('Y-m-d'), end($dates)->format('Y-m-d')])->sum('amount')) }}</th>
                        <td><a class="btn btn-sm btn-primary" href="{{ route('management.account.edit', ['id' => $account->id]) }}">Edit</a></td>
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

    <script>
        $(document).ready(function(){

            $(".sticky-header").floatThead({scrollingTop:50});

        });
    </script>
@endsection

