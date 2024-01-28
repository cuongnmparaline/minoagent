@php
    $currentMonth = \Carbon\Carbon::now()->month;
@endphp
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
                        <h6 class="mb-0">{{ $customers->count() }}</h6>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3">
                <div class="bg-light rounded d-flex align-items-center justify-content-between p-4">
                    <i class="fa fa-chart-bar fa-3x text-primary"></i>
                    <div class="ms-3">
                        <p class="mb-2">Total Balance</p>
                        <h6 class="mb-0">{{ sprintf("%.2f", $customers->sum('balance')) }}</h6>
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
        <form action="" method="get">
            <div class="col-4 col-xl-4">
                <div class="form-floating mb-3">
                    <input type="date" class="form-control"
                           name="searchDate" value="{{ request('searchDate') }}" >
                    <label for="floatingInput">Date</label>
                </div>
            </div>
            <div class="col-3 col-xl-3">
                <div class="form-floating mb-3">
                    <input type="submit" class="btn btn-sm btn-primary" value="Search">
                </div>
            </div>
        </form>
        <div class="bg-light text-center rounded p-4">
            <div class="table-responsive">
                <table class="table text-start align-middle table-bordered mb-0 table-striped">
                    <thead>
                    <tr class="text-dark">
                        <th scope="col">Customer name</th>
                        @for($i = 6; $i >= 0; $i--)
                            @php
                                $day = request('searchDate') ? \Carbon\Carbon::createFromFormat("Y-m-d",request('searchDate')) : \Carbon\Carbon::today();
                            @endphp
                            <th scope="col">{{ $day->subDay($i)->format('d/m') }}</th>
                        @endfor
                        <th scope="col">Total Real Spend Month</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td><b>Total</b></td>
                        @for($i = 6; $i >= 0; $i--)
                            @php
                                $day = request('searchDate') ? \Carbon\Carbon::createFromFormat("Y-m-d",request('searchDate')) : \Carbon\Carbon::today();
                                    $totalAmountDay = \App\Models\Account::withoutGlobalScopes()->join('reports', 'reports.account_id', '=', 'accounts.id')
                                    ->join('customers', 'customers.id', '=', 'accounts.customer_id')
                                    ->where('reports.date', '=', $day->subDay($i)->format("Y-m-d"))->where('accounts.del_flag', '=', config('const.active'))
                                    ->groupBy('accounts.id')
                                    ->get(['accounts.id', \Illuminate\Support\Facades\DB::raw('sum(reports.real_spend) as real_spend')])
                                    ->sum('real_spend');
                            @endphp
                            <td><b>{{ sprintf("%.2f",  $totalAmountDay) }}</b></td>

                        @endfor
                        @php
                            $totalAmountCustomerMonth = \App\Models\Account::withoutGlobalScopes()->join('reports', 'reports.account_id', '=', 'accounts.id')
                            ->join('customers', 'customers.id', '=', 'accounts.customer_id')
                            ->whereRaw("MONTH(date) = $currentMonth")->where('accounts.del_flag', '=', config('const.active'))
                            ->groupBy('accounts.id')
                            ->get(['accounts.id', \Illuminate\Support\Facades\DB::raw('sum(reports.real_spend) as real_spend')])
                            ->sum('real_spend');
                        @endphp
                        <td><b>{{ sprintf("%.2f", $reports->sum('real_spend')) }}</b></td>
                    </tr>
                    @foreach($customers as $customer)
                        <tr>
                            <td>{{ $customer['name'] }}</td>
                            @for($i = 6; $i >= 0; $i--)
                                @php
                                    $day = request('searchDate') ? \Carbon\Carbon::createFromFormat("Y-m-d",request('searchDate')) : \Carbon\Carbon::today();
                                        $totalAmount = \App\Models\Account::withoutGlobalScopes()->join('reports', 'reports.account_id', '=', 'accounts.id')
                                    ->join('customers', 'customers.id', '=', 'accounts.customer_id')
                                    ->where('reports.date', '=', $day->subDay($i)->format("Y-m-d"))->where('accounts.del_flag', '=', config('const.active'))
                                    ->where('accounts.customer_id', '=', $customer->id)
                                    ->groupBy('accounts.id')
                                    ->get(['accounts.id', \Illuminate\Support\Facades\DB::raw('sum(reports.amount) as amount')])
                                    ->sum('amount');
                                @endphp
                                <td>{{ sprintf("%.2f",  $totalAmount) }}</td>
                            @endfor
                            @php
                                $totalAmountMonth = \App\Models\Account::withoutGlobalScopes()->join('reports', 'reports.account_id', '=', 'accounts.id')
                                ->join('customers', 'customers.id', '=', 'accounts.customer_id')
                                ->whereRaw("MONTH(date) = $currentMonth")->where('accounts.del_flag', '=', config('const.active'))
                                ->where('accounts.customer_id', '=', $customer->id)
                                ->groupBy('accounts.id')
                                ->get(['accounts.id', \Illuminate\Support\Facades\DB::raw('sum(reports.amount) as amount')])
                                ->sum('amount');
                            @endphp
                            <td>{{ sprintf("%.2f",  $totalAmountMonth) }}</td>

                            @php
                                $totalFeeMonth = \App\Models\Account::withoutGlobalScopes()->join('reports', 'reports.account_id', '=', 'accounts.id')
                                ->join('customers', 'customers.id', '=', 'accounts.customer_id')
                                ->whereRaw("MONTH(date) = $currentMonth")->where('accounts.del_flag', '=', config('const.active'))
                                ->where('accounts.customer_id', '=', $customer->id)
                                ->groupBy('accounts.id')
                                ->get(['accounts.id', \Illuminate\Support\Facades\DB::raw('sum(reports.amount_fee) as amount_fee')])
                                ->sum('amount_fee');
                            @endphp
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

