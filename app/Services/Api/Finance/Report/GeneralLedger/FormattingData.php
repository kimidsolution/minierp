<?php

namespace App\Services\Api\Finance\Report\GeneralLedger;

use Carbon\Carbon;
use Illuminate\Http\Request;

class FormattingData
{
    public static function handle(Request $request)
    {
        $result = [];
        $dataTransactionFiltered = $request->data_provider;
        if (count($dataTransactionFiltered) > 0) {
            $first_key = array_key_first($dataTransactionFiltered);
            $last_key = array_key_last($dataTransactionFiltered);
            $last_data = end($dataTransactionFiltered);
            $last_amount = $last_data->account_balance == 'credit' ? $last_data->credit_amount : $last_data->debit_amount;

            foreach ($dataTransactionFiltered as $key => $value) {
                $result[] = [
                    'transaction_id' => $value->transaction_id,
                    'account_id' => $value->account_id,
                    'transaction_detail_id' => $value->transaction_detail_id,
                    'debit' => $value->debit_amount,
                    'credit' => $value->credit_amount,
                    'balance' => 0,
                    'date' => Carbon::parse($value->transaction_date)->format('d-m-Y'),
                    'reference_number' => $value->reference_number,
                    'description' => $value->description,
                    'account_name' => $value->account_naming,
                    'account_code' => $value->account_code,
                    'last_amount' => $last_amount,
                    'is_first_key' => $key == $first_key,
                    'is_last_key' => $key == $last_key
                ];
            }
        }

        $request->request->add([
            'list_data_without_balance' => $result,
            'balance_of_account' => count($result) > 0 && !is_null($last_data) ? $last_data->account_balance : null
        ]);
    }
}
