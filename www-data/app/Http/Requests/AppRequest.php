<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Validator;

class AppRequest extends FormRequest
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
        Validator::extend(
            'url', function ($attribute, $value, $parameters) {
                return filter_var($value, FILTER_VALIDATE_URL);
            }
        );

        $rules = [
            'name' => 'string|required',
            'app_id' => 'string|required',
            'url' => 'required',
            #'banner_url' => 'url|nullable',
            #'download_url' => 'url|nullable',
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
