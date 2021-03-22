<?php

namespace App\Services\Api\Configuration\Finance\Store;

use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class StoreService
{
    public function handle(Request $request)
    {
        try {
            app()->make('App\Http\Requests\ConfigurationFinanceStoreRequest');
            BeforeStore::handle($request);
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
