@extends('layouts.main')
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
@section('content')
    <div class="container-fluid pt-4 px-4">
        <table class="table table-borderless">
            <tr>
                <td><b>Code:</b></td>
                <td>{{ $account->code }}</td>
                <td></td>
                <td><b>Name:</b></td>
                <td>{{ $account->name }}</td>
            </tr>
            <tr>
                <td><b>Customer:</b></td>
                <td>{{ $account->customer->name }}</td>
                <td></td>
                <td><b>Status:</b></td>
                <td>{{ $account->status }}</td>
            </tr>
        </table>
    </div>
    <div class="container-fluid pt-4 px-4">
        <div class="bg-light text-center rounded p-4">
            <h3>Monthly Spend</h3>
            <table class="table">
                @foreach($dates as $date)
                    <tr>
                        @php
                            $report = $account->reports->where('date', $date->format('Y-m-d'))->first();
                        @endphp
                        <td>{{$date->format('Y-m-d')}}</td>
                        <td>@if(!empty($report)) {{ $report->amount }} @else 0 @endif$</td>
                    </tr>
                @endforeach
            </table>
        </div>
    </div>
@endsection

