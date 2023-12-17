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
        @php
            $start = \Carbon\Carbon::now()->startOfMonth();
                $end = \Carbon\Carbon::now()->endOfMonth();
                $dates = [];
                while ($start->lte($end)) {
                     $dates[] = $start->copy();
                     $start->addDay();
                }
                $totalSpendMonth = 0;
                $totalDay = 0;
                $avarageMonth = 0;
                foreach ($customer->accounts as $account) {
                    $totalSpendMonth += $account->reports->whereBetween('date', [$dates[0]->format('Y-m-d'), end($dates)->format('Y-m-d')])->sum('amount');
                }

                  $datework = \Carbon\Carbon::createFromDate($customer['ins_datetime']);
                  $now = \Carbon\Carbon::now();

                  $totalDay = $dates[0]->diffInDays($now)+1;
                  $datediff = $datework->diffInDays($now)+1;
                    if ($datediff < $totalDay) {
                        $avarageMonth = $totalSpendMonth / $datediff;
                    } else {
                        $avarageMonth = $totalSpendMonth / $totalDay;
                    }

                if ($avarageMonth <= 1000 ) {
                    $nextFee = "9%";
                } else if($avarageMonth > 1000 && $avarageMonth <= 3000) {
                    $nextFee = "8%";
                } else if ($avarageMonth > 3000 && $avarageMonth <= 5000){
                    $nextFee = "7%";
                } else {
                    $nextFee = "NEGOTIATE";
                }
        @endphp
        <div class="modal fadeOut" tabindex="-1" role="dialog" id="myModal">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Discout Fee Alert!</h5>

                    </div>
                    <div class="modal-body">
{{--                        <p>You have spent an average of  this month!</p>--}}
                        <p>Look!! WIth <b>{{ sprintf("%.2f",$totalSpendMonth) }}$</b> total spending, your average spending is <b>{{ sprintf("%.2f", $avarageMonth) }}$</b> this month.
                            Let's spend  more to achieve your goal budget, then the fee next month will be reduced to <b>{{ $nextFee }}</b></p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal" id="btnClose">Close</button>
                    </div>
                </div>
            </div>
        </div>

        <script>
            window.onload = function() {myFunction()};
            document.getElementById("btnClose").onclick = function () {
                document.getElementById("myModal").style.display = "none";
            }
            document.getElementById("btnX").onclick = function () {
                document.getElementById("myModal").style.display = "none";
            }

            function myFunction() {
                document.getElementById("myModal").style.display = "block";
            }
        </script>
    </div>

@endsection

