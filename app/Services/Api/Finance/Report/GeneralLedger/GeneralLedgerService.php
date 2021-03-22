<?php

namespace App\Services\Api\Finance\Report\GeneralLedger;

use App\Services\Api\Finance\Report\GeneralLedger\ValidateRequest;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class GeneralLedgerService
{
    public function handle(Request $request)
    {
        try {
            ValidateRequest::handle($request);
            GetLastBalanceAccount::handle($request);
            GetTransactionByFilter::handle($request);
            FormattingData::handle($request);
            CountingTransactionAccount::handle($request);
            CountingResult::handle($request);
            return ResponseProvider::handle($request);
        } catch (QueryException $e) {
            return response()->json([
                'status' => false,
                'messages' => $e->getMessage()
            ]);
        }
    }
}
