<?php

namespace App\Http\Controllers;

use App\Http\Requests\Admin\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function getLogin(){
        if (Auth::guard('admin')->check()) {
            return redirect()->route('management.home');
        }
        return view("management.login");
    }

    public function postLogin(LoginRequest $request){
        return redirect()->route('management.home');
    }

    public function logout()
    {
        Auth::guard('admin')->logout();
        return redirect()->route('login');
    }
}
