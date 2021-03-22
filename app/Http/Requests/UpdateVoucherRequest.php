<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateVoucherRequest extends FormRequest
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
            'company_id' => 'required|exists:companies,id',
            'date' => 'required|date',
            'type' => 'required|in:1,2',
            'number' => 'nullable',
            'note' => 'nullable',
            'partner_id' => 'required|exists:partners,id',
            'asset_account_id_used' => 'required|exists:accounts,id',
            'data' => 'required|array',
            'data.*.invoice_id' => 'required|exists:invoices,id',
            'data.*.amount' => 'required|numeric',
            'data.*.final_amount' => 'required|numeric',
            'voucher_id' => 'required|exists:vouchers,id'
        ];
    }
}
