<?php

namespace App\Services\Api\Account\StoreAccount;

use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class StoreAccountService
{
    public function handle(Request $request)
    {
        try {
            ValidateRequest::handle($request);
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
