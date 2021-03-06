<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CurrencyStoreRequest extends FormRequest
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
        return [
            'currency_name' => 'required|unique:currencies|max:30',
            'currency_code' => 'required|unique:currencies|max:10',
            'iso_code' => 'required|unique:currencies|max:3',
            'symbol' => 'required'
        ];
    }
}
