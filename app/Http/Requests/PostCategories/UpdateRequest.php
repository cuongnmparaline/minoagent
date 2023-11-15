<?php

namespace App\Http\Requests\PostCategories;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    protected $redirectRoute = "management.postCategories";

    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'status' => 'required|string|max:30'
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        session()->flash('error', __('messages.updatePostFail'));
        return parent::failedValidation($validator);
    }
}
