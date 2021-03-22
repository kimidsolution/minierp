<?php

namespace App\Services\Api\Finance\Voucher\Store;

use App\Models\Voucher;
use Illuminate\Http\Request;

class SetDataCreateVoucher
{
    public static function handle(Request $request)
    {
        $userCompany = $request->user_company;

        $dataSaveVoucher = [
            'voucher_date' => $request->date,
            'voucher_type' => $request->type,
            'voucher_number' => $request->number,
            'note' => $request->note,
            'is_posted' => (1 == $request->is_posted) ? Voucher::POSTED_YES : Voucher::POSTED_NO,
            'payment_account_id' => $request->asset_account_id_used,
            'company_id' => $userCompany->company_id,
            'partner_id' => $request->partner_id,
            'posted_by' => $request->user_id,
            'created_by' => $userCompany->name,
            'updated_by' => $userCompany->name
        ];

        $request->request->add([
            'data_save_voucher' => $dataSaveVoucher
        ]);
    }
}
