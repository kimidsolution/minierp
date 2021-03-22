<?php

namespace App\Services\Api\Product\Create;

use DB;
use Str;
use App\User;
use App\Models\Product;
use Illuminate\Http\Request;

class SaveData
{
    public static function handle(Request $request)
    {
        DB::beginTransaction();

        try {
            //? find user have access to create
            $user_data = User::find($request->user_id);

            //? create product
            $product = new Product;
            $product->product_name = $request->product_name;
            $product->product_category_id = $request->product_category;
            $product->sku = $request->sku;
            $product->price = preg_replace("/[^0-9-]+/", "", $request->price);
            $product->status = Product::STATUS_ACTIVE;
            $product->type = $request->type;
            $product->created_by = $user_data->name;
            $product->company_id = $request->company_id;
            $product->save();

            DB::commit();

            return response()->api(true, [], $product);
        } catch (\Exception $e) {
            DB::rollback();
            abort(400, $e->getMessage());
        }
    }
}
