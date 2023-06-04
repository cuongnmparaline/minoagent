<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\LoginRequest;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function getLogin(){
        if (Auth::guard('customer')->check()) {
            return redirect()->route('customer.home');
        }
        return view("customer.login");
    }

    public function postLogin(LoginRequest $request){
        return redirect()->route('customer.home');
    }

    public function logout()
    {
        Auth::guard('customer')->logout();
        return redirect()->route('customer.login');
    }
}
