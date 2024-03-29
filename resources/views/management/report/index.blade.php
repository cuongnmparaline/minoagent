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
                        <h6 class="mb-0">{{ $reports->count() }}</h6>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container-fluid pt-4 px-4">
        <form action="" method="get">
            <div class="row">
                <div class="col-4 col-xl-4">
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control"
                               placeholder="Name" name="searchName" value="{{ request('name') }}">
                        <label for="floatingInput">Name</label>
                    </div>
                </div>
                <div class="col-4 col-xl-4">
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control"
                               placeholder="Name" name="searchCode" value="{{ request('code') }}" >
                        <label for="floatingInput">Code</label>
                    </div>
                </div>
                <div class="col-4 col-xl-4">
                    <div class="form-floating mb-3">
                        <input type="date" class="form-control"
                               name="searchDate" value="{{ request('date') }}" >
                        <label for="floatingInput">Date</label>
                    </div>
                </div>

                <div class="col-3 col-xl-3">
                    <div class="form-floating mb-3">
                        <input type="submit" class="btn btn-sm btn-primary" value="Search">
                    </div>
                </div>
                <div class="col-3 col-xl-3">

                </div>
                <div class="col-3 col-xl-3">

                </div>
                <div class="col-3 col-xl-3">
                    <div class="form-floating mb-3 float-end">
                        <a href="{{ route('management.report') }}" class="btn btn-sm btn-primary">Clear</a>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <div class="container-fluid pt-4 px-4">
        <div class="bg-light text-center rounded p-4">
            <div class="d-flex align-items-center justify-content-between mb-4">
                <h6 class="mb-0">
                    <a href="{{ route('management.report.create') }}"><i class="bi bi-plus-circle-fill"></i> Add</a>
                </h6>
                <a href="">Export Excel</a>
            </div>
            <div class="table-responsive">
                <table class="table text-start align-middle table-bordered table-hover mb-0">
                    <thead>
                    <tr class="text-dark">
                        <th scope="col">STT</th>
                        <th scope="col">Account Name</th>
                        <th scope="col">Account Code</th>
                        <th scope="col">@sortablelink('date', 'Date')</th>
                        <th scope="col">@sortablelink('amount', 'Amount')</th>
                        <th scope="col">Amount Fee</th>
                        <th scope="col">Currency</th>
                        <th scope="col">Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($reports as $report)
                    <tr>
                        <td>{{ ++$loop->index }}</td>
                        <td>{{ $report->account->name }}</td>
                        <td>{{ $report->account->code }}</td>
                        <td>{{ $report['date'] }}</td>
                        <td>{{ $report['amount'] }}</td>
                        <td>{{ $report['amount_fee'] }}</td>
                        <td>{{ $report['currency'] }}</td>
                        <td><a class="btn btn-sm btn-primary" href="{{ route('management.report.edit', ['id' => $report['id'] ]) }}">Edit</a></td>
                    </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            <div class="d-flex align-items-center justify-content-center mt-4">
                {{ $reports->links() }}
            </div>
        </div>
    </div>
@endsection

