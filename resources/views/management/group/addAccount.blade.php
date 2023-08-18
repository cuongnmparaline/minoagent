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
{{--                    <i class="fa fa-chart-line fa-3x text-primary"></i>--}}
                    <div class="ms-3">
                        <p class="mb-2">Group Name</p>
                        <h6 class="mb-0">{{ $group->name }}</h6>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container-fluid pt-4 px-4">
        <div class="bg-light text-center rounded p-4">
            <div class="d-flex align-items-center justify-content-between mb-4">
{{--                <h6 class="mb-0">--}}
{{--                    <a href="{{ route('management.group.create') }}"><i class="bi bi-plus-circle-fill"></i> Add</a>--}}
{{--                </h6>--}}
            </div>
            <div class="table-responsive">
                <table class="table text-start align-middle table-bordered table-hover mb-0">
                    <thead>
                    <tr class="text-dark">
                        <th scope="col">STT</th>
                        <th scope="col">Account Code</th>
                        <th scope="col">Account Name</th>
                        <th scope="col">Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($accounts as $account)
                    <tr>
                        <td>{{ ++$loop->index }}</td>
                        <td>{{ $account['code'] }}</td>
                        <td>{{ $account['name'] }}</td>
                        <td>
                            <div class="justify-content-around">
                                <a class="btn btn-sm btn-primary" href="{{ route('management.group.saveToGroup', ['id' => $group->id, 'accountId' => $account->id]) }}">Add</a>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                    </tbody>
                </table>
{{--                <div class="d-flex align-items-center justify-content-center mt-4">--}}
{{--                    {{ $accounts->links() }}--}}
{{--                </div>--}}
            </div>
        </div>
    </div>
@endsection

