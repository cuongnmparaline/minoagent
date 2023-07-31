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
                        <p class="mb-2">Total Customer</p>
                        <h6 class="mb-0"></h6>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3">
                <div class="bg-light rounded d-flex align-items-center justify-content-between p-4">
                    <i class="fa fa-chart-bar fa-3x text-primary"></i>
                    <div class="ms-3">
                        <p class="mb-2">Live</p>
                        <h6 class="mb-0">2</h6>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3">
                <div class="bg-light rounded d-flex align-items-center justify-content-between p-4">
                    <i class="fa fa-chart-area fa-3x text-primary"></i>
                    <div class="ms-3">
                        <p class="mb-2">Die</p>
                        <h6 class="mb-0">0</h6>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3">
                <div class="bg-light rounded d-flex align-items-center justify-content-between p-4">
                    <i class="fa fa-chart-pie fa-3x text-primary"></i>
                    <div class="ms-3">
                        <p class="mb-2">Comback</p>
                        <h6 class="mb-0">0</h6>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container-fluid pt-4 px-4">
        <div class="bg-light text-center rounded p-4">
            <div class="table-responsive">
                <table class="table text-start align-middle table-bordered table-hover mb-0">
                    <thead>
                    <tr class="text-dark">
                        <th scope="col">Customer name</th>
                        @for($i = 6; $i >= 0; $i--)
                            <th scope="col">{{ \Carbon\Carbon::today()->subDay($i)->format('d/m') }}</th>
                        @endfor
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($customers as $customer)
                        <tr>
                            <td>{{ $customer['name'] }}</td>
                            @for($i = 6; $i >= 0; $i--)
                                @php
                                    $totalAmount = \App\Models\Account::withoutGlobalScopes()->join('reports', 'reports.account_id', '=', 'accounts.id')
                                ->join('customers', 'customers.id', '=', 'accounts.customer_id')
                                ->where('reports.date', '=', \Carbon\Carbon::today()->subDay($i))->where('accounts.del_flag', '=', config('const.active'))
                                ->where('accounts.customer_id', '=', $customer->id)
                                ->groupBy('accounts.id')
                                ->get(['accounts.id', \Illuminate\Support\Facades\DB::raw('sum(reports.amount) as amount')])
                                ->sum('amount');
                                @endphp
                                <td>{{ $totalAmount }}</td>
                            @endfor
                        </tr>
                    @endforeach
                    </tbody>
                </table>
{{--                <div class="d-flex align-items-center justify-content-center mt-4">--}}
{{--                    {{ $customers->links() }}--}}
{{--                </div>--}}
            </div>
        </div>
    </div>
@endsection

