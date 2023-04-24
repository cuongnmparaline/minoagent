@extends('layouts.main')

@section('content')
    <div class="container-fluid pt-4 px-4">
        <div class="row g-12">
            <div class="col-sm-12 col-xl-12">
                <div class="bg-light rounded h-100 p-4">
                    <h6 class="mb-4">Add New Account</h6>
                    <form action="{{route('management.account.store')}}" method="post">
                        @csrf
                        <select class="form-select form-select-lg mb-3" aria-label=".form-select-lg example" name="account_id">
                            <option value="">Choose account</option>
                            @foreach($accounts as $account)
                                <option {{getDataCreateForm('account_data', 'account_id') == $account->name ? "selected" : ""}} value="{{$account->id}}">{{$account->name}}</option>
                            @endforeach
                        </select>
                        @if ($errors->has('account_id'))
                            <p style="color: #ff0000">{{ $errors->first('account_id') }}</p>
                        @endif
                        <div class="form-floating mb-3">
                            <input type="date" class="form-control"
                                   placeholder="date" name="date">
                            <label for="floatingInput">Date</label>
                            @if ($errors->has('date'))
                                <p style="color: #ff0000">{{ $errors->first('date') }}</p>
                            @endif
                        </div>
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control"
                                   placeholder="Amount" name="amount">
                            <label for="floatingInput">Amount</label>
                            @if ($errors->has('amount'))
                                <p style="color: #ff0000">{{ $errors->first('amount') }}</p>
                            @endif
                        </div>
                        <select class="form-select form-select-lg mb-3" aria-label=".form-select-lg example" name="currency">
                            <option value="">Choose currency</option>
                            @foreach(config('const.currency') as $value => $currency)
                                <option {{getDataCreateForm('account_data', 'currency') == $account->name ? "selected" : ""}} value="{{ $value }}">{{ $currency }}</option>
                            @endforeach
                        </select>
                        <div class="mb-4"></div>
                        <button class="btn btn-primary w-100 m-2" type="submit">Save</button>
                        <button class="btn btn-secondary w-100 m-2" type="button">Cancel</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

