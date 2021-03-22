<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TransactionStoreRequest extends FormRequest
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
            'user_id' => 'required|exists:users,id',
            'transaction_type' => 'required',
            'company_id' => 'required|exists:companies,id',
            'model_type' => 'required',
            'transaction_date' => 'required|date_format:d-m-Y',
            'description' => 'required',
            'account' => 'required|array',
            'debit_amount' => 'required|array',
            'credit_amount' => 'required|array',
            'totalDebitAmount' => 'required',
            'totalCreditAmount' => 'required',
            'transaction_status' => 'required',
            'model_id' => 'required',
        ];
    }
}
