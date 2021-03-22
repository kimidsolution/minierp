<?php

namespace App\Http\Requests;

use App\Rules\UniqueProductSku;
use App\Rules\UniqueProductName;
use Illuminate\Foundation\Http\FormRequest;

class ProductStoreInvoiceRequest extends FormRequest
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
            'product_name' => ['required', 'max:100'],
            'product_category' => 'required|exists:product_categories,id',
            'sku' => ['required', 'max:100'],
            'price' => 'required',
            'type' => 'required'
        ];
    }
}
