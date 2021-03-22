<?php

namespace App\Services\Api\Finance\Transaction\Store;

use App\Models\Transaction;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class StoreTransactionService
{
    public function handle(Request $request)
    {
        try {
            app()->make('App\Http\Requests\TransactionStoreRequest');
            $total_debit_amount = preg_replace("/[^0-9-]+/", "", $request->totalDebitAmount);
            $total_credit_amount = preg_replace("/[^0-9-]+/", "", $request->totalCreditAmount);
            if ($total_debit_amount != $total_credit_amount) {
                return abort(400, 'Transaction not balance');
            }
            ParseBeforeStoreData::handle($request);
            $response = StoreDataTransaction::handle($request);
            return $response;
        } catch (QueryException $e) {
            return response()->json([
                'status' => false,
                'messages' => $e->getMessage()
            ]);
        }
    }
}
