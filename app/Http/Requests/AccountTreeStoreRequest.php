<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AccountTreeStoreRequest extends FormRequest
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
            'user_id' => 'required',
            'account_type_id' => 'required',
            'account_name' => 'required',
            'account_code' => 'required|numeric|digits_between:4,8',
            'account_level' => 'required|numeric',
            'account_parent_id' => 'nullable|exists:accounts,id'
        ];
    }
}
