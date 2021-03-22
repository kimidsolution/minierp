<?php

namespace App\Services\Api\Finance\Invoice\Receivable\Update;

use Carbon\Carbon;
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
            'model_id' => $request->invoice_id,
            'model_type' => '\\App\\Models\\Invoice',
            'transaction_type' => Transaction::TYPE_RECEIVABLE,
            'transaction_status' => Transaction::STATUS_DRAFT,
            'reference_number' => $request->invoice_number,
            'description' => $request->description,
            'company_id' => $request->company_id,
            'updated_at' => now()
        ];

        $request->request->add([
            'data_transaction' => $dataTransaction
        ]);
    }
}
