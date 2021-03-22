<?php

namespace App\Services\Api\Finance\Invoice\Create;

use Illuminate\Support\Str;
use Illuminate\Http\Request;

class SetDataForTransaction
{
    public static function handle(Request $request)
    {
        $dataCompany = $request->data_company;

        // data transaction
        $dataTransaction = [
            'uuid' => (string) Str::uuid(),
            'date' => $request->date,
            'model_id' => null,
            'model_type' => '\\App\\Models\\Invoice',
            'reference_number' => $request->invoice_number,
            'description' => $request->description,
            'company_id' => $dataCompany['company_id'],
            'created_at' => now(),
            'updated_at' => now()
        ];

        $request->request->add([
            'data_transaction' => $dataTransaction
        ]);
    }
}
