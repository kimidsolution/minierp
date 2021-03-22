<?php

namespace App\Services\Api\ProductCategory\Create;

use DB;
use App\User;
use App\Models\ProductCategory;
use Illuminate\Http\Request;

class SaveData
{
    public static function handle(Request $request)
    {
        DB::beginTransaction();

        try {
            //? find user have access to create
            $user_data = User::find($request->user_id);

            //? create product category
            $product_category = new ProductCategory;
            $product_category->category_name = $request->product_category_name;
            $product_category->company_id = $request->company_id;
            $product_category->created_by = $user_data->name;
            $product_category->updated_by = $user_data->name;
            $product_category->save();

            DB::commit();

            return response()->api(true, [], $product_category);
        } catch (\Exception $e) {
            DB::rollback();
            abort(400, $e->getMessage());
        }
    }
}
