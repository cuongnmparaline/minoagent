@extends('layouts.main')

@section('content')
    <div class="container-fluid pt-4 px-4">
        <div class="row g-12">
            <div class="col-sm-12 col-xl-12">
                <div class="bg-light rounded h-100 p-4">
                    <h6 class="mb-4">Add New Group</h6>
                    <form action="{{route('management.group.store')}}" method="post">
                        @csrf
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control"
                                   placeholder="Name" name="name">
                            <label for="floatingInput">Name</label>
                            @if ($errors->has('name'))
                                <p style="color: #ff0000">{{ $errors->first('name') }}</p>
                            @endif
                        </div>
                        <select class="form-select form-select-lg mb-3" aria-label=".form-select-lg example" name="customer_id">
                            <option value="">Choose customer</option>
                            @foreach($customers as $customer)
                                <option {{getDataCreateForm('customer_data', 'customer_id') == $customer->name ? "selected" : ""}} value="{{$customer->id}}">{{$customer->name}}</option>
                            @endforeach
                        </select>
                        @if ($errors->has('customer_id'))
                            <p style="color: #ff0000">{{ $errors->first('customer_id') }}</p>
                        @endif
                        <button class="btn btn-primary w-100 m-2" type="submit">Save</button>
                        <button class="btn btn-secondary w-100 m-2" type="button">Cancel</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

