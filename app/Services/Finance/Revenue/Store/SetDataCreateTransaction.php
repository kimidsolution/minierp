<?php

namespace App\Services\Finance\Revenue\Store;

use Illuminate\Support\Str;
use Illuminate\Http\Request;

class SetDataCreateTransaction
{
    public static function handle(Request $request)
    {
        $dataUser = $request->user;

        // data transaction
        $dataTransaction = [
            'uuid' => (string) Str::uuid(),
            'date' => $request->date,
            'model_id' => null,
            'model_type' => '\\App\\Models\\Revenue',
            'reference_number' => $request->reference_number,
            'description' => $request->description,
            'company_id' => $dataUser['company_id'],
            'created_at' => now(),
            'updated_at' => now()
        ];

        $request->request->add([
            'data_transaction' => $dataTransaction
        ]);
    }
}
