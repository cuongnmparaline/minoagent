<?php

namespace App\Http\Requests\Report;

use Illuminate\Foundation\Http\FormRequest;

class EditRequest extends FormRequest
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
            'email' => 'bail|required|email|max:128|unique:customers,email,'.request()->get('id'),
            'password' => 'nullable|max:64',
            'passwordVerify' => 'nullable|same:password',
        ];
    }
}
