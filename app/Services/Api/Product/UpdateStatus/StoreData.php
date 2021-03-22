<?php

namespace App\Services\Api\Product\UpdateStatus;

use DB;
use App\Models\Product;
use App\User;
use Illuminate\Http\Request;

class StoreData
{
    public static function handle(Request $request)
    {
        DB::beginTransaction();
        try { 
            //? try get match status product based on model status const
            $match_status = Product::validateStatusProduct($request->status_id);
            if (!$match_status) {
                abort(400, 'Product status does not match');
            }
            
            //? update data product
            $product = Product::where('company_id', $request->company_id)->where('id', $request->product_id)->first();

            if (is_null($product)) {
                abort(400, 'Product not found');
            }

            $user = User::find($request->user_id);
            $product->status = $request->status_id;
            $product->updated_by = $user->name;
            $product->save();

            //? it works if change status to deleted
            if ($request->status_id == Product::STATUS_DELETED) {  
                $product->delete();
            }
            
            DB::commit();

            return response()->api(true, [], $product);
        } catch (\Exception $e) {
            DB::rollback();
            
            if ($e instanceof ValidationException) {
                $errors = $e->validator->errors()->toArray();
                return response()->api(true, [], $errors);
            }
            
            return response()->api(true, [], $e->getMessage());
        }
    }
}
