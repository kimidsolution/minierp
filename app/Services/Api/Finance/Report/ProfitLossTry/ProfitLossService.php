<?php

namespace App\Services\Api\Finance\Report\ProfitLossTry;

use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use App\Services\Api\Finance\Report\ProfitLoss\ValidateRequest;

class ProfitLossService
{
    public function handle(Request $request)
    {
        try {
            ValidateRequest::handle($request);
            GetDataAccountType::handle($request);
            GetDataTransaction::handle($request);
            EachDataRawType::handle($request);
            dd($request->all());
        } catch (QueryException $e) {
            return response()->json([
                'status' => false,
                'messages' => $e->getMessage()
            ]);
        }
    }
}
