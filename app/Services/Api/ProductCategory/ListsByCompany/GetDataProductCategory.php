<?php

namespace App\Services\Api\ProductCategory\ListsByCompany;

use App\User;
use Carbon\Carbon;
use App\Models\ProductCategory;
use League\Fractal\Manager;
use Illuminate\Http\Request;
use League\Fractal\Resource\Collection;

class GetDataProductCategory
{
    public static function handle(Request $request)
    {
        $product_category = ProductCategory::where('company_id', $request->company_id)->get();

        if (is_null($product_category))
            abort('Data user not found');
        
        if ($product_category->count() < 1) {
            $request->request->add([
                'data_response' => []
            ]);
            return true;
        }

        $product_categoryArray = $product_category->toArray();
            
        $fractal = new Manager();
        $resource = new Collection($product_categoryArray, function(array $pi) {
            return [
                'id' => $pi['id'],
                'text' => $pi['category_name']
            ];
        });

        $array = $fractal->createData($resource)->toArray();
        $request->request->add([
            'data_response' => $array['data']
        ]);
    }
}
