@extends('layouts.main')
@section('content')
    <div class="container-fluid pt-4 px-4">
        <table class="table table-borderless">
            <tr>
                <td><b>Name:</b></td>
                <td>{{ $customer->name }}</td>
                <td></td>
                <td><b>Email:</b></td>
                <td>{{ $customer->email }}</td>
            </tr>
            <tr>
                <td><b>Balance:</b></td>
                <td>{{ $customer->balance }}</td>
                <td></td>
                <td><b>Fee:</b></td>
                <td>{{ $customer->fee }}</td>
            </tr>
        </table>
    </div>
    <div class="container-fluid pt-4 px-4">
        <div class="bg-light text-center rounded p-4">
            <a class="btn-primary btn" href="{{ route('management.customer.cal-balance', ['id' => $customer->id]) }}">Balance Calculate</a>
        </div>
    </div>
@endsection

