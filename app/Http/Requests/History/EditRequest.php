<?php

namespace App\Http\Requests\History;

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
            'customer_id' => 'required',
            'date' => 'bail|required|date',
            'addAmount' => 'nullable|numeric',
            'hashcode' => 'required'
        ];
    }
}
