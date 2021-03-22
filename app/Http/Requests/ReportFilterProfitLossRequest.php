<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReportFilterProfitLossRequest extends FormRequest
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
            'start_period' => 'required|date_format:Y-m',
            'end_period' => 'required|date_format:Y-m',
            'to_json' => 'required',
            'company_id' => 'required',
            'page' => 'nullable'
        ];
    }
}
