<?php

namespace App\Services\Api\Finance\Expense\Store;

use Illuminate\Http\Request;

class SetDataCreateTransactionDetail
{
    public static function handle(Request $request)
    {
        $dataTransactionDetail = [];

        // add item debit
        array_push($dataTransactionDetail, [
            'debit_amount' => (float) $request->amount,
            'credit_amount' => 0,
            'account_id' => $request->expense_account_id
        ]);

        // add item credit
        array_push($dataTransactionDetail, [
            'debit_amount' => 0,
            'credit_amount' => (float) $request->amount,
            'account_id' => $request->payment_account_id
        ]);

        $request->request->add(['data_transaction_detail' => $dataTransactionDetail]);
    }
}
