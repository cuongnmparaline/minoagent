<?php

namespace App\Http\Requests\Account;

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
            'code' => 'bail|required|max:15|unique:accounts',
            'name' => 'bail|required|max:128',
            'customer_id' => 'required',
            'status' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'customer_id.required' => 'Please choose customer!'
        ];
    }
}
