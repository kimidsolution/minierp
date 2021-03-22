<?php

namespace App\Services\Api\Finance\Invoice\Receivable\Store;

use Carbon\Carbon;
use App\Models\Invoice;
use App\Models\Transaction;
use Illuminate\Http\Request;

class SetDataTransaction
{
    public static function handle(Request $request)
    {
        $date = Carbon::parse($request->invoice_date);

        // data transaction
        $dataTransaction = [
            'transaction_date' => $date->format('Y-m-d'),
            'model_id' => null,
            'transaction_type' => Transaction::TYPE_RECEIVABLE,
            'transaction_status' => (Invoice::POSTED_YES == $request->is_posted) ? Transaction::STATUS_POSTED : Transaction::STATUS_DRAFT,
            'model_type' => '\\App\\Models\\Invoice',
            'reference_number' => $request->invoice_number,
            'description' => $request->description,
            'company_id' => $request->company_id
        ];

        $request->request->add([
            'data_transaction' => $dataTransaction
        ]);
    }
}
