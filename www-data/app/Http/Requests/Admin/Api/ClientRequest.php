<?php

namespace App\Http\Requests\Admin\Api;

use Illuminate\Foundation\Http\FormRequest;

class ClientRequest extends FormRequest
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
            'sort_field' => 'string|required',
            'sort_direction' => 'string|required',
            'categoryId' => 'integer|required',
        ];

        return $rules;
    }

    public function messages()
    {
        return [
            'name.required' => 'The Name is required',
            'url.required' => 'The Product Url is required',
            'banner_url.required' => 'The banner_url is required',
        ];
    }
}
