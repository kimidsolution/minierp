<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CompanyStoreRequest extends FormRequest
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
            'company_name' => 'required|max:150|unique:companies,company_name',
            'brand_name' => 'required|max:150|unique:companies,brand_name',
            'email' => 'required|unique:companies,email|email',
            'phone' => 'required|numeric',
            'address' => 'nullable',
            'logo' => 'nullable|image|max:1024',
            'tax_id_number' => 'nullable|unique:companies,tax_id_number',
            'fax' => 'nullable|numeric',
            'website' => 'nullable|unique:companies,website',
            'vat_enabled' => 'required',
            'type' => 'required',
            'city' => 'required|max:150',
            'country' => 'required|max:150',
        ];
    }
}
