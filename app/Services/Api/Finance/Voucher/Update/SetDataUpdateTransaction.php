<?php

namespace App\Services\Api\Finance\Voucher\Update;

use App\Models\Voucher;
use App\Models\Transaction;
use Illuminate\Http\Request;

class SetDataUpdateTransaction
{
    public static function handle(Request $request)
    {
        $dataTransactionDetail = [];
        $userCompany = $request->user_company;
        
        // set data transaction
        $dataSaveTransaction = [
            'transaction_date' => $request->date,
            'reference_number' => $request->number,
            'description' => $request->note
        ];

        $request->request->add([
            'data_save_transaction' => $dataSaveTransaction
        ]);
    }
}
