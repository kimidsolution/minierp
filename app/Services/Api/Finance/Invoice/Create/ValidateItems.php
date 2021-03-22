<?php

namespace App\Services\Api\Finance\Invoice\Create;

use App\Models\Product;
use Illuminate\Http\Request;

class ValidateItems
{
    public static function handle(Request $request)
    {
        $dataCompany = $request->data_company;
        $products = $request->products;

        foreach ($products as $key => $value) {
            $productIsHaveCompany = Product::where('id', $value['product_id'])->where('company_id', $dataCompany['company_id'])->first();
            if (is_null($productIsHaveCompany))
                abort(400, 'Invalid product id');
        }
    }
}
