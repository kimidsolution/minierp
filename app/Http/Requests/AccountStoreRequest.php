<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AccountStoreRequest extends FormRequest
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
            'company_id' => 'required',
            'account_type' => 'required',
            'balance' => 'required',
            'parent_account_id' => 'required',
            'level' => 'required',
            'name' => 'required',
            'account_code' => 'required|numeric|digits_between:4,8',
            'balance_date' => 'required',
            'balance_nominal' => 'required'
        ];
    }
}
