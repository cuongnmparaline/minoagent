<?php

namespace App\Http\Requests\Customer;

use Illuminate\Foundation\Http\FormRequest;

class CreateRequest extends FormRequest
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
            'name' => 'bail|required|max:128',
            'email' => 'bail|required|email|max:128|unique:customers',
            'balance' => 'required|numeric',
            'fee' => 'required|numeric|digits:1',
            'password' => 'required|max:64',
            'passwordVerify' => 'same:password',
        ];
    }
}
