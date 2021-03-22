<?php

namespace App\Services\Api\Finance\Voucher\Store;

use App\Models\Voucher;
use App\Models\Transaction;
use Illuminate\Http\Request;

class SetDataTransaction
{
    public static function handle(Request $request)
    {
        $dataTransactionDetail = [];
        $userCompany = $request->user_company;
        
        // set data transaction
        $dataSaveTransaction = [
            'transaction_date' => $request->date,
            'model_id' => null,
            'transaction_type' => (Voucher::TYPE_RECEIVABLE == $request->type) ? Transaction::TYPE_RECEIVABLE : Transaction::TYPE_PAYABLE,
            'transaction_status' => (Voucher::POSTED_YES == $request->is_posted) ? Transaction::STATUS_POSTED : Transaction::STATUS_DRAFT,
            'model_type' => '\\App\\Models\\Voucher',
            'reference_number' => $request->number,
            'description' => $request->note,
            'company_id' => $userCompany->company_id
        ];

        $request->request->add([
            'data_save_transaction' => $dataSaveTransaction
        ]);
    }
}
