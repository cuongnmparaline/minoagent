@extends('layouts.main')

@section('content')
    <div class="container-fluid pt-4 px-4">
        <div class="row g-12">
            <div class="col-sm-12 col-xl-12">
                <div class="bg-light rounded h-100 p-4">
                    <h6 class="mb-4">Add New Customer</h6>
                    <form action="{{route('management.admin.store')}}" method="post">
                        @csrf
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control"
                                   placeholder="Name" name="name">
                            <label for="floatingInput">Name</label>
                            @if ($errors->has('name'))
                                <p style="color: #ff0000">{{ $errors->first('name') }}</p>
                            @endif
                        </div>
                        <div class="form-floating mb-3">
                            <input type="email" class="form-control"
                                   placeholder="name@example.com" name="email">
                            <label for="floatingInput">Email address</label>
                            @if ($errors->has('email'))
                                <p style="color: #ff0000">{{ $errors->first('email') }}</p>
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
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="role" value="1" id="flexRadioDefault2">
                            <label class="form-check-label" for="flexRadioDefault2">
                                Admin
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="role" value="2" id="flexRadioDefault1" checked>
                            <label class="form-check-label" for="flexRadioDefault1">
                                Tech
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="role" value="3" id="flexRadioDefault1" checked>
                            <label class="form-check-label" for="flexRadioDefault1">
                                Account
                            </label>
                        </div>
                        <button class="btn btn-primary w-100 m-2" type="submit">Save</button>
                        <button class="btn btn-secondary w-100 m-2" type="button">Cancel</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

