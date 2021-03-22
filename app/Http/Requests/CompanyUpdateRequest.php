<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CompanyUpdateRequest extends FormRequest
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
        $input = $this->request->all();
        
        return [
            'company_name' => 'required|max:150',
            'brand_name' => 'required|max:150',
            'email' => 'required|email',
            'phone' => 'required|numeric',
            'address' => 'nullable',
            'logo' => 'nullable|image|max:1024',
            'fax' => 'nullable|numeric',
            'vat_enabled' => 'required',
            'type' => 'required',
            'city' => 'required|max:150',
            'country' => 'required|max:150',
        ];
    }
}
