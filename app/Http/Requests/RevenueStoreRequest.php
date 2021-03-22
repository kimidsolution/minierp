<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RevenueStoreRequest extends FormRequest
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
            'down_payment_account_id' => 'required|exists:accounts,id',
            'partner_id' => 'required|exists:partners,id',
            'date' => 'required|date_format:d-m-y',
            'due_date' => 'required|date_format:d-m-y',
            'description' => 'nullable',
            'invoice_number' => 'required|unique:invoices,number',
            'nominal_discount' => 'required|numeric',
            'nominal_vat' => 'required|numeric',
            'nominal_prepaid_income_tax' => 'required|numeric',
            'nominal_down_payment' => 'required|numeric',
            'nominal_sub_total_amount' => 'required|numeric',
            'nominal_remaining' => 'required|numeric',
            'paid_to' => 'required|exists:accounts,id'
        ];
    }
}