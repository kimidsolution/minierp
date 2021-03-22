<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateVoucherSalesRequest extends FormRequest
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
            'invoice_number' => 'required',
            'date' => 'required|date_format:Y-m-d',
            'reference_number' => 'required',
            'type' => 'required|in:sales,purchases',
            'note' => 'nullable',
            'account_id_paid' => 'required|exists:accounts,id'
        ];
    }
}
