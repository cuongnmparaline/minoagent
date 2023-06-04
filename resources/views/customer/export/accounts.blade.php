@php
    $month = \Carbon\Carbon::now()->format('Y-m');
    $start = \Carbon\Carbon::parse($month)->startOfMonth();
    $end = \Carbon\Carbon::parse($month)->endOfMonth();
    $dates = [];
    while ($start->lte($end)) {
       $dates[] = $start->copy();
       $start->addDay();
      }
@endphp

<table>
    <thead>
        <tr>
            <td>Account name</td>
            <td>Account ID</td>
            <td>Currency Unit</td>
            <td>Status</td>
            @foreach($dates as $date)
                <td>{{ $date->format('m/d/y') }}</td>
            @endforeach
            <td>Total</td>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            @foreach($dates as $date)
                @php
                   $totalAmount = \App\Models\Account::withoutGlobalScopes()->join('reports', 'reports.account_id', '=', 'accounts.id')
                   ->where('reports.date', '=', $date)->where('accounts.del_flag', '=', config('const.active'))
                   ->groupBy('accounts.id')
                   ->get(['accounts.id', \Illuminate\Support\Facades\DB::raw('sum(reports.amount) as amount')])
                   ->sum('amount');
                @endphp
                <td>{{ $totalAmount  }}</td>
            @endforeach
            <td></td>
        </tr>
        @foreach($accounts as $account)
            <tr>
                <td>{{ $account->name }}</td>
                <td>{{ $account->code }}</td>
                <td>{{ $account->reports->first()->currency }}</td>
                <td>{{ $account->status }}</td>
                @foreach($dates as $date)
                    @php
                        $report = $account->reports->where('date', $date->format('Y-m-d'))->first();
                    @endphp
                    <td>@if(!empty($report)) {{ $report->amount }} @else 0 @endif</td>
                @endforeach
                <td>{{ $account->reports->sum('amount') }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
