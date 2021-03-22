<?php

namespace App\Services\Api\Finance\Report\ProfitLossTry;

use Illuminate\Http\Request;

class EachDataRawType
{
    public static function handle(Request $request)
    {
        $source_income = Self::getRawTransactionFilteredType($request, $request->type_income);
        $source_cogs = Self::getRawTransactionFilteredType($request, $request->type_cogs);
        $source_expense = Self::getRawTransactionFilteredType($request, $request->type_expense);
        $source_other_income = Self::getRawTransactionFilteredType($request, $request->type_other_income);
        $source_other_expense = Self::getRawTransactionFilteredType($request, $request->type_other_expense);

        $request->request->add([
            'raw_income' => $source_income,
            'raw_cogs' => $source_cogs,
            'raw_expense' => $source_expense,
            'raw_other_income' => $source_other_income,
            'raw_other_expense' => $source_other_expense,
        ]);
    }

    protected static function getRawTransactionFilteredType($request, $type) {
        $result = [];
        $raw_result = [];
        if (!empty($request->data_transaction)) {
            $sum_debit = (float) 0;
            $sum_credit = (float) 0;

            foreach ($request->data_transaction as $value) {
                if (!is_null($value['account']) && $value['account']['account_type'] == $type['id']) {
                    $sum_debit += $value['debit_amount'];
                    $sum_credit += $value['credit_amount'];

                    $value['nominal_debit_amount'] = $sum_debit;
                    $value['nominal_credit_amount'] = $sum_credit;

                    array_push($raw_result, $value);
                }
            }
        }

        if (!empty($raw_result)) {
            foreach ($raw_result as $value_raw) {
                $map_raw = [
                    'account_id' => $value_raw['account_id'],
                    'transaction_id' => $value_raw['transaction_id'],
                    'company_id' => $value_raw['account']['company_id'],
                    'parent_account_id' => $value_raw['account']['parent_account_id'],
                    'account_name' => $value_raw['account']['naming'],
                    'account_code' => $value_raw['account']['account_code'],
                    'account_level' => $value_raw['account']['level'],
                    'account_balance' => $value_raw['account']['balance'],
                    'account_type' => $value_raw['account']['account_type'],
                    'nominal_debit_amount' => $value_raw['nominal_debit_amount'],
                    'nominal_credit_amount' => $value_raw['nominal_credit_amount'],
                ];

                array_push($result, $map_raw);
            }
        }

        return $result;
    }
}
