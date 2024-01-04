<?php

namespace App\Http\Requests\Notpay;

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
            'code' => 'bail|required|unique:accounts,code,'.request()->get('id'),
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
