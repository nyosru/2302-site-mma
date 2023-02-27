<?php

namespace App\Http\Requests;

use App\Rules\MailableEmailRule;
use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
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
        $rules = [
            'name' => 'string|required',
            'email' => [
                'required',
                'string',
                'min:3',
                'max:255',
                'email',
                new MailableEmailRule(),
            ],
        ];

        return $rules;
    }

    public function messages()
    {
        return [
            'name.required' => 'The Name is required',

        ];
    }
}
