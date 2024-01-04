@php
    $month = \Carbon\Carbon::now()->format('Y-m');
    $start = \Carbon\Carbon::parse($exportDate)->startOfMonth();
    $end = \Carbon\Carbon::parse($exportDate)->endOfMonth();
    $dates = [];
    while ($start->lte($end)) {
       $dates[] = $start->copy();
       $start->addDay();
      }
@endphp

<table>
    <thead>
        <tr>
            <td>Customer name</td>
            <td>Balance</td>
            @foreach($dates as $date)
                <td>{{ $date->format('m/d/y') }}</td>
            @endforeach
            <td>Total</td>
        </tr>
    </thead>
    <tbody>
        @foreach($customers as $customer)
            <tr>
                <td>{{ $customer->name }}</td>
                <td>{{ $customer->balance }}</td>
                @foreach($dates as $date)
                    @php
                        $totalAmount = \App\Models\Account::withoutGlobalScopes()->join('reports', 'reports.account_id', '=', 'accounts.id')
                        ->join('customers', 'customers.id', '=', 'accounts.customer_id')
                        ->where('reports.date', '=', $date)->where('accounts.del_flag', '=', config('const.active'))
                        ->where('accounts.customer_id', '=', $customer->id)
                        ->groupBy('accounts.id')
                        ->get(['accounts.id', \Illuminate\Support\Facades\DB::raw('sum(reports.amount) as amount')])
                        ->sum('amount');
                    @endphp
                    <td>{{ $totalAmount }}</td>
                @endforeach
            </tr>
        @endforeach
    </tbody>
</table>
