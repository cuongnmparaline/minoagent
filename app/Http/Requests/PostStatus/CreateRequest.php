<?php

namespace App\Http\Requests\PostStatus;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class CreateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    protected $redirectRoute = "management.postStatus";

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
            'name' => 'required|string|max:300',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        return redirect()->back()->withErrors($validator)->withInput();
    }
}
