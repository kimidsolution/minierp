<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ListPartnerByCompanyRequest extends FormRequest
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
            'company_id' => 'required|exists:companies,id',
            'user_id' => 'required|exists:users,id',
            'type' => 'required|in:1,2'
        ];
    }
}
