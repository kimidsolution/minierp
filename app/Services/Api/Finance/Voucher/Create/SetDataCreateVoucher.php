<?php

namespace App\Services\Api\Finance\Voucher\Create;

use Illuminate\Support\Str;
use Illuminate\Http\Request;

class SetDataCreateVoucher
{
    public static function handle(Request $request)
    {
        $dataSaveVoucher = [
            'uuid' => (string) Str::uuid(),
            'date' => $request->date,
            'type' => $request->type,
            'number' => $request->number,
            'note' => $request->note,
            'paid_to' => $request->asset_account_id_used,
            'created_by' => $request->user_id,
            'created_at' => now(),
            'updated_at' => now()
        ];

        $request->request->add([
            'data_save_voucher' => $dataSaveVoucher
        ]);
    }
}
