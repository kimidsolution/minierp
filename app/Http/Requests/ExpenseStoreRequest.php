<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ExpenseStoreRequest extends FormRequest
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
            'amount' => 'required|numeric',
            'company_id' => 'required|exists:companies,id',
            'description' => 'required',
            'expense_account_id' => 'required|exists:accounts,id',
            'expense_date' => 'required|date_format:d-m-Y',
            'expense_number' => 'required',
            'is_posted' => 'required',
            'payment_account_id' => 'required|exists:accounts,id',
            'user_id' => 'required|exists:users,id'
        ];
    }
}
