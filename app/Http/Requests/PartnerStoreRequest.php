<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PartnerStoreRequest extends FormRequest
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
            'name' => 'required|max:150',
            'email' => 'required',
            'phone_number' => 'nullable|numeric',
            'fax' => 'nullable|numeric',
            'address' => 'required',
            'city' => 'required',
            'country' => 'required',
            'partner_status' => 'required',
            'pic_phone_number' => 'nullable|numeric',
        ];
    }
}
