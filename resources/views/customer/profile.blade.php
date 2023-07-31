@extends('layouts.main')

@section('content')
    <div class="container-fluid pt-4 px-4">
        <div class="row g-12">
            <div class="col-sm-12 col-xl-12">
                <div class="bg-light rounded h-100 p-4">
                    <h6 class="mb-4">Edit Customer</h6>
                    @if (Session::has('success'))
                        <div class="alert alert-success" style="text-align: center;">
                            <strong>{{ session()->get('success') }}</strong>
                        </div>
                    @endif
                    @if (Session::has('error'))
                        <div class="alert alert-danger" style="text-align: center;">
                            <strong>{{ session()->get('error') }}</strong>
                        </div>
                    @endif
                    <form action="{{route('customer.updateProfile', ['id' => $customer->id])}}" method="post">
                        @csrf
                        <input type="hidden" name="id" value="{{ $customer->id }}">
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control"
                                   placeholder="Nick Name" name="nick_name" value="{{ $customer->nick_name }}">
                            <label for="floatingInput">Nick Name</label>
                            @if ($errors->has('nick_name'))
                                <p style="color: #ff0000">{{ $errors->first('nick_name') }}</p>
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

