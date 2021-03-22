<?php

namespace App\Services\Finance\Revenue\Store;

use Illuminate\Http\Request;

class SetDataCreateTransactionDetail
{
    public static function handle(Request $request)
    {
        $dataTransactionDetail = [];
        $dataUser = $request->user;
        $items = $request->items;

        // add items
        foreach ($items as $key => $value) {
            $dataTransactionDetail[$key]['debit_amount'] = 0;
            $dataTransactionDetail[$key]['credit_amount'] = (int) $value['nominal'];
            $dataTransactionDetail[$key]['account_id'] = $value['account_id'];
        }


        // add item debit
        array_push($dataTransactionDetail, [
            'debit_amount' => $request->amount,
            'credit_amount' => 0,
            'account_id' => $request->paid_to
        ]);


        // for currency
        $companyCurrency = \App\Models\CompanyCurrency::where('company_id', $dataUser['company_id'])->first();
        
        foreach ($dataTransactionDetail as $key => $value) {
            $dataTransactionDetail[$key]['date'] = $request->date;
            $dataTransactionDetail[$key]['value_rate'] = 1;
            $dataTransactionDetail[$key]['exchange_rate_from'] = $companyCurrency->id;
            $dataTransactionDetail[$key]['exchange_rate_to'] = $companyCurrency->id;
        }

        $request->request->add([
            'data_transaction_detail' => $dataTransactionDetail
        ]);
    }
}
