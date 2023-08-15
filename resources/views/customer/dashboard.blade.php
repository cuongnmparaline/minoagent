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
                            <p class="mb-2">Balance</p>
                            <h6 class="mb-0">{{ sprintf("%.2f",$customer->balance) }}</h6>
                        </div>
                    </div>
                </div>
            <div class="col-sm-6 col-xl-3">
                <div class="bg-light rounded d-flex align-items-center justify-content-between p-4">
                    <i class="fa fa-chart-line fa-3x text-primary"></i>
                    <div class="ms-3">
                        <p class="mb-2">Total Accounts</p>
                        <h6 class="mb-0">{{$customer->accounts->count()}}</h6>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3">
                <div class="bg-light rounded d-flex align-items-center justify-content-between p-4">
                    <i class="fa fa-chart-area fa-3x text-primary"></i>
                    <div class="ms-3">
                        <p class="mb-2">Total Spend Month</p>
                        <h6 class="mb-0">{{ sprintf("%.2f",$reports->sum('amount')) }}</h6>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3">
                <div class="bg-light rounded d-flex align-items-center justify-content-between p-4">
                    <i class="fa fa-chart-area fa-3x text-primary"></i>
                    <div class="ms-3">
                        <p class="mb-2">Total Fee Month</p>
                        <h6 class="mb-0">{{ sprintf("%.2f",$reports->sum('amount_fee')) }}</h6>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container-fluid pt-4 px-4">
        <div class="bg-light text-center rounded p-4">
            <div class="table-responsive">
                <table class="table text-start align-middle table-bordered table-striped mb-0">
                    <thead>
                    <tr class="text-dark">
                        <th scope="col">Date</th>
                        @for($i = 6; $i >= 0; $i--)
                            <th scope="col">{{ \Carbon\Carbon::today()->subDay($i)->format('d/m') }}</th>
                        @endfor
                    </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Total Spend</td>
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
                                <td>{{ sprintf("%.2f",$totalAmount) }}</td>
                            @endfor
                        </tr>
                        <tr>
                            <td>Number Account Spend</td>
                            @for($i = 6; $i >= 0; $i--)
                                @php
                                    $numberCustomerSpend = \App\Models\Account::withoutGlobalScopes()->join('reports', 'reports.account_id', '=', 'accounts.id')
                                ->join('customers', 'customers.id', '=', 'accounts.customer_id')
                                ->where('reports.date', '=', \Carbon\Carbon::today()->subDay($i))->where('accounts.del_flag', '=', config('const.active'))
                                ->where('accounts.customer_id', '=', $customer->id)
                                ->groupBy('accounts.id')
                                ->get(['accounts.id', \Illuminate\Support\Facades\DB::raw('sum(reports.amount) as amount')])
                                ->count();
                                @endphp
                                <td>{{ $numberCustomerSpend }}</td>
                            @endfor
                        </tr>
                        <tr>
                            <td>Number Fee Spend</td>
                            @for($i = 6; $i >= 0; $i--)
                                @php
                                    $numberCustomerFee = \App\Models\Account::withoutGlobalScopes()->join('reports', 'reports.account_id', '=', 'accounts.id')
                                ->join('customers', 'customers.id', '=', 'accounts.customer_id')
                                ->where('reports.date', '=', \Carbon\Carbon::today()->subDay($i))->where('accounts.del_flag', '=', config('const.active'))
                                ->where('accounts.customer_id', '=', $customer->id)
                                ->groupBy('accounts.id')
                                ->get(['accounts.id', \Illuminate\Support\Facades\DB::raw('sum(reports.amount_fee) as amount_fee')])
                                ->sum('amount_fee');
                                @endphp
                                <td>{{ sprintf("%.2f",$numberCustomerFee) }}</td>
                            @endfor
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

