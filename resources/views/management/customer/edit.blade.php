@extends('layouts.main')

@section('content')
    <div class="container-fluid pt-4 px-4">
        <div class="row g-12">
            <div class="col-sm-12 col-xl-12">
                <div class="bg-light rounded h-100 p-4">
                    <h6 class="mb-4">Edit Customer</h6>
                    <form action="{{route('management.customer.update', ['id' => $customer->id])}}" method="post">
                        @csrf
                        <input type="hidden" name="id" value="{{ $customer->id }}">

                        <div class="col-12 col-xl-12">
                            <select class="form-select form-select-lg mb-3" aria-label=".form-select-lg example" name="admin_id">
                                <option value="">Choose Tech</option>
                                @foreach($techs as $tech)
                                    <option {{$customer['admin_id'] == $tech->id ? "selected" : ""}} value="{{$tech->id}}">{{$tech->name}}</option>
                                @endforeach
                            </select>
                            @if ($errors->has('admin_id'))
                                <p style="color: #ff0000">{{ $errors->first('admin_id') }}</p>
                            @endif
                        </div>
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control"
                                   placeholder="Name" name="name" value="{{ $customer->name }}">
                            <label for="floatingInput">Name</label>
                            @if ($errors->has('name'))
                                <p style="color: #ff0000">{{ $errors->first('name') }}</p>
                            @endif
                        </div>
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control"
                                   placeholder="Nick Name" name="nick_name" value="{{ $customer->nick_name }}">
                            <label for="floatingInput">Nick Name</label>
                            @if ($errors->has('nick_name'))
                                <p style="color: #ff0000">{{ $errors->first('nick_name') }}</p>
                            @endif
                        </div>
                        <div class="form-floating mb-3">
                            <input type="email" class="form-control"
                                   placeholder="name@example.com" name="email" value="{{ $customer->email }}">
                            <label for="floatingInput">Email address</label>
                            @if ($errors->has('email'))
                                <p style="color: #ff0000">{{ $errors->first('email') }}</p>
                            @endif
                        </div>
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control"
                                   placeholder="Balcne" name="balance" value="{{ $customer->balance }}" readonly>
                            <label for="floatingInput">Balance</label>
                            @if ($errors->has('balance'))
                                <p style="color: #ff0000">{{ $errors->first('balance') }}</p>
                            @endif
                        </div>
{{--                        <div class="form-floating mb-3">--}}
{{--                            <input type="text" class="form-control"--}}
{{--                                   placeholder="Add Balance" name="addBalance" value="">--}}
{{--                            <label for="floatingInput">Add Balance</label>--}}
{{--                            @if ($errors->has('addBalance'))--}}
{{--                                <p style="color: #ff0000">{{ $errors->first('addBalance') }}</p>--}}
{{--                            @endif--}}
{{--                        </div>--}}
                        <div class="form-floating mb-3">
                            <input type="number" class="form-control"
                                   placeholder="name@example.com" name="fee" value="{{ $customer->fee }}" maxlength="2">
                            <label for="floatingInput">Fee</label>
                            @if ($errors->has('fee'))
                                <p style="color: #ff0000">{{ $errors->first('fee') }}</p>
                            @endif
                        </div>
                        <div class="form-floating mb-3">
                            <input type="password" class="form-control" id="floatingPassword"
                                   placeholder="Password" name="password">
                            <label for="floatingPassword">Password</label>
                            @if ($errors->has('password'))
                                <p style="color: #ff0000">{{ $errors->first('password') }}</p>
                            @endif
                        </div>
                        <div class="form-floating mb-3">
                            <input type="password" class="form-control"
                                   placeholder="Password" name="passwordVerify">
                            <label for="floatingPassword">Password Confirm</label>
                            @if ($errors->has('passwordVerify'))
                                <p style="color: #ff0000">{{ $errors->first('passwordVerify') }}</p>
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

