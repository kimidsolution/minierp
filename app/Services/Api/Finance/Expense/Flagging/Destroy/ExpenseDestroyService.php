<?php

namespace App\Services\Api\Finance\Expense\Flagging\Destroy;

use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class ExpenseDestroyService
{
    public function handle(Request $request)
    {
        try {
            app()->make('App\Http\Requests\FlaggingRequest');
            $response = StoreData::handle($request);
            return $response;
        } catch (QueryException $e) {
            return response()->json([
                'status' => false,
                'messages' => $e->getMessage()
            ]);
        }
    }
}
