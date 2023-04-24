@extends('layouts.main')

@section('content')
    <div class="container-fluid pt-4 px-4">
        <div class="row g-12">
            <div class="col-sm-12 col-xl-12">
                <div class="bg-light rounded h-100 p-4">
                    <h6 class="mb-4">Edit Account</h6>
                    <form action="{{route('management.account.update', ['id' => $account->id])}}" method="post">
                        @csrf
                        <input type="hidden" name="id" value="{{ $account->id }}">
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control"
                                   placeholder="Name" name="code" value="{{ $account->code }}">
                            <label for="floatingInput">Code</label>
                            @if ($errors->has('code'))
                                <p style="color: #ff0000">{{ $errors->first('code') }}</p>
                            @endif
                        </div>
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control"
                                   placeholder="Name" name="name" value="{{ $account->name }}">
                            <label for="floatingInput">Name</label>
                            @if ($errors->has('name'))
                                <p style="color: #ff0000">{{ $errors->first('name') }}</p>
                            @endif
                        </div>
                        <select class="form-select form-select-lg mb-3" aria-label=".form-select-lg example" name="customer_id">
                            <option value="">Choose customer</option>
                            @foreach($customers as $customer)
                                <option {{ $account->customer_id == $customer->id ? "selected" : ""}} value="{{$customer->id}}">{{$customer->email}}</option>
                            @endforeach

                        </select>
                        @if ($errors->has('customer_id'))
                            <p style="color: #ff0000">{{ $errors->first('customer_id') }}</p>
                        @endif
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="status" id="inlineRadio1" value="0" {{ $account->status == config('const.status.0') ? "checked" : ""}}>
                            <label class="form-check-label" for="inlineRadio1">Live</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="status" id="inlineRadio2" value="1" {{ $account->status == config('const.status.1') ? "checked" : ""}}>
                            <label class="form-check-label" for="inlineRadio2">Die</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="status" id="inlineRadio3" value="2" {{ $account->status == config('const.status.2') ? "checked" : ""}}>
                            <label class="form-check-label" for="inlineRadio3">Comeback</label>
                        </div>
                        @if ($errors->has('status'))
                            <p style="color: #ff0000">{{ $errors->first('status') }}</p>
                        @endif
                        <div class="mb-4"></div>
                        <button class="btn btn-primary w-100 m-2" type="submit">Save</button>
                        <button class="btn btn-secondary w-100 m-2" type="button">Cancel</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

