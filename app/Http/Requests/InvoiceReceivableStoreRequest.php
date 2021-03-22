<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class InvoiceReceivableStoreRequest extends FormRequest
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
            'partner_id' => 'required|exists:partners,id',
            'account_id_asset' => 'required|exists:accounts,id',
            'date' => 'required|date_format:d-m-Y',
            'due_date' => 'required|date_format:d-m-Y',
            'description' => 'nullable',
            'invoice_number' => 'required|unique:invoices,invoice_number',
            'nominal_discount' => 'required|numeric|min:0',
            'nominal_vat' => 'required|numeric|min:0',
            'nominal_down_payment' => 'required|numeric|min:0',
            'nominal_sub_total_amount' => 'required|numeric',
            'nominal_remaining' => 'required|numeric',
            'products' => 'required|array',
            'products.*.product_id' => 'required|exists:products,id'
        ];
    }
}
