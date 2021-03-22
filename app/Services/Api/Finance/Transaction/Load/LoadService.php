<?php

namespace App\Services\Api\Finance\Transaction\Load;

use App\Models\Transaction;
use Illuminate\Http\Request;

class LoadService
{
    public function handle(Request $request)
    {
        $data = [];

        $query = Transaction::with(['transaction_details.account']);
        if (!is_null($request->transaction_id)) $query->where('id', $request->transaction_id);
        $transaction = $query->first();

        if ($transaction) {
            $transactionDetails = $transaction->transaction_details->toArray();
            $data = [
                'date' => $transaction->transaction_date,
                'model_id' => $transaction->model_id,
                'model_type' => $transaction->model_type,
                'type_id' => $transaction->transaction_type,
                'status_id' => $transaction->transaction_status,
                'description' => $transaction->description,
                'details' => $transactionDetails,
                'total_debit_amount' => $transaction->checking_balance_debit,
                'total_credit_amount' => $transaction->checking_balance_credit
            ];
        }

        return response()->api(true, [], $data, '', 200);
    }
}
