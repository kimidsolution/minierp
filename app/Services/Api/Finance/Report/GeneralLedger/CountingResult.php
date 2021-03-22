<?php

namespace App\Services\Api\Finance\Report\GeneralLedger;

use Illuminate\Http\Request;

class CountingResult
{
    public static function handle(Request $request)
    {
        $transaction = app('data.helper')->getTransactionByOtherSpecification($request, $request->account_id);

        $sumBalance = (float) 0;
        $sumDebit = (float) $transaction->nominal_debit_amount;
        $sumCredit = (float) $transaction->nominal_credit_amount;

        if (!empty($sumDebit)) {
            $sumBalance = $sumDebit - $sumCredit;
        } else {
            if (!is_null($request->balance_of_account) && $request->balance_of_account == 'credit') {
                $sumBalance = $sumCredit;
            }
        }

        $resultTotal = [
            'debit' => $sumDebit,
            'credit' => $sumCredit,
            'balance' => $sumBalance,
            'date' => '',
            'reference_number' => '',
            'description' => 'total'
        ];

        $request->request->add(['data_result_total' => $resultTotal]);
    }
}
