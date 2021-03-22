<?php

namespace App\Services\Api\Account\Lists\ListsParent;

use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class ListParentService
{
    public function handle(Request $request)
    {
        try {
            app()->make('App\Http\Requests\ListParentAccountRequest');
            GetDataParent::handle($request);
            return response()->api(true, [], $request->data_response);
        } catch (QueryException $e) {
            return response()->json([
                'status' => false,
                'messages' => $e->getMessage()
            ]);
        }
    }
}
