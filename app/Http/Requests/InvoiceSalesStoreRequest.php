<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class InvoiceSalesStoreRequest extends FormRequest
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
            'asset_account_id' => 'required|exists:accounts,id',
            'partner_id' => 'required|exists:partners,id',
            'date' => 'required|date_format:Y-m-d',
            'due_date' => 'required|date_format:Y-m-d',
            'description' => 'nullable',
            'invoice_number' => 'required|unique:invoices,number',
            'nominal_discount' => 'required|numeric|min:0',
            'nominal_tax' => 'required|numeric|min:0',
            'nominal_sub_total_amount' => 'required|numeric',
            'nominal_down_payment' => 'required|numeric|min:0',
            'nominal_remaining' => 'required|numeric',
            'products' => 'required|array',
            'products.*.product_id' => 'required|exists:products,id',
        ];
    }
}
