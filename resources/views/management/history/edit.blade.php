@extends('layouts.main')

@section('content')
    <div class="container-fluid pt-4 px-4">
        <div class="row g-12">
            <div class="col-sm-12 col-xl-12">
                <div class="bg-light rounded h-100 p-4">
                    <h6 class="mb-4">Edit History</h6>
                    <form action="{{route('management.history.update', ['id' => $history->id])}}" method="post">
                        @csrf
                        <input type="hidden" name="id" value="{{ $history->id }}">
                        <select class="form-select form-select-lg mb-3" aria-label=".form-select-lg example" name="customer_id">
                            <option value="">Choose customer</option>
                            @foreach($customers as $customer)
                                <option {{getDataEditForm($customer, 'history_data', 'customer_id') == $customer->id ? "selected" : ""}} value="{{$customer->id}}">{{$customer->name}}</option>
                            @endforeach
                        </select>
                        @if ($errors->has('customer_id'))
                            <p style="color: #ff0000">{{ $errors->first('customer_id') }}</p>
                        @endif
                        <div class="form-floating mb-3">
                            <input type="date" class="form-control"
                                   placeholder="date" name="date" value="{{ $history->date }}">
                            <label for="floatingInput">Date</label>
                            @if ($errors->has('date'))
                                <p style="color: #ff0000">{{ $errors->first('date') }}</p>
                            @endif
                        </div>
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control"
                                   placeholder="Amount" name="amount" id="amount" value="{{ $history->amount }}" readonly>
                            <label for="floatingInput">Amount</label>
                            @if ($errors->has('amount'))
                                <p style="color: #ff0000">{{ $errors->first('amount') }}</p>
                            @endif
                        </div>
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control"
                                   placeholder="Add Amount" name="addAmount" id="addAmount" value="">
                            <label for="floatingInput">Add Amount</label>
                            @if ($errors->has('addAmount'))
                                <p style="color: #ff0000">{{ $errors->first('addAmount') }}</p>
                            @endif
                        </div>
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control"
                                   placeholder="Hash Code" name="hashcode" id="hashcode" value="{{ $history->hashcode }}">
                            <label for="floatingInput">Hash Code</label>
                            @if ($errors->has('hashcode'))
                                <p style="color: #ff0000">{{ $errors->first('hashcode') }}</p>
                            @endif
                        </div>
                        <button class="btn btn-primary w-100 m-2" type="submit">Save</button>
                        <button class="btn btn-secondary w-100 m-2" type="button">Cancel</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

