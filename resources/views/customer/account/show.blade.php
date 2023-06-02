@extends('layouts.main')

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

        </div>
    </div>
@endsection

