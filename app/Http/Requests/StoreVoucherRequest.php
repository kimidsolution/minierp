<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreVoucherRequest extends FormRequest
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
            'date' => 'required|date_format:d-m-Y',
            'number' => 'required|unique:vouchers,voucher_number',
            'type' => 'required|in:1,2',
            'note' => 'nullable',
            'partner_id' => 'required|exists:partners,id',
            'asset_account_id_used' => 'required|exists:accounts,id',
            'data' => 'required|array',
            'data.*.invoice_id' => 'required|exists:invoices,id',
            'invoices.*.amount' => 'required|numeric',
            'invoices.*.final_amount' => 'required|numeric'
        ];
    }
}
