<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CurrencyUpdateRequest extends FormRequest
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
        $input = $this->request->all();

        return [
            'currency_name' => "required|max:30|unique:currencies,currency_name,{$input['id']}",
            'currency_code' => "required|max:10|unique:currencies,currency_code,{$input['id']}",
            'iso_code' => "required|max:10|unique:currencies,iso_code,{$input['id']}",
            'symbol' => 'required'
        ];
    }
}
