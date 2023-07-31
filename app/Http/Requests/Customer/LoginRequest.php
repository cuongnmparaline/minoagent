<?php

namespace App\Http\Requests\Customer;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'email' => 'required',
            'password' => 'required'
        ];
    }

    public function withValidator($validator)
    {
        if(!empty(request()->get('email')) && !empty(request()->get('password'))){
            $validator->after(function ($validator) {
                if(Auth::guard('customer')->attempt(request()->only(['email', 'password']))){
                    Auth::guard('admin')->logout();
                } else {
                    $validator->errors()->add('incorrectAccount', 'Email or password are incorrect!');
                }
            });
        }
    }
}