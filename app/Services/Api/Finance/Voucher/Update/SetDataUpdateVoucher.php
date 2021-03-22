<?php

namespace App\Services\Api\Finance\Voucher\Update;

use Illuminate\Http\Request;

class SetDataUpdateVoucher
{
    public static function handle(Request $request)
    {
        $userCompany = $request->user_company;

        $dataUpdateVoucher = [
            'voucher_date' => $request->date,
            'note' => $request->note,
            'payment_account_id' => $request->asset_account_id_used,
            'updated_by' => $userCompany->name,
        ];

        $request->request->add([
            'data_save_voucher' => $dataUpdateVoucher
        ]);
    }
}
