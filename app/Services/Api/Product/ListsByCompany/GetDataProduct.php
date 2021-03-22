<?php

namespace App\Services\Api\Product\ListsByCompany;

use App\User;
use Carbon\Carbon;
use App\Models\Product;
use League\Fractal\Manager;
use Illuminate\Http\Request;
use League\Fractal\Resource\Collection;

class GetDataProduct
{
    public static function handle(Request $request)
    {
        $product = Product::where('company_id', $request->company_id)->get();

        if (is_null($product))
            abort('Data user not found');
        
        if ($product->count() < 1) {
            $request->request->add([
                'data_response' => []
            ]);
            return true;
        }

        $productArray = $product->toArray();
            
        $fractal = new Manager();
        $resource = new Collection($productArray, function(array $pi) {
            return [
                'id' => $pi['id'],
                'text' => $pi['product_name'],
                'type' => $pi['type'],
                'harga' => $pi['price'],
            ];
        });

        $array = $fractal->createData($resource)->toArray();
        $request->request->add([
            'data_response' => $array['data']
        ]);
    }
}
