<?php

namespace App\Services\Api\Finance\Voucher\UpdateSales;

use Illuminate\Support\Str;
use Illuminate\Http\Request;

class SetDataUpdateVoucher
{
    public static function handle(Request $request)
    {
        $dataInvoice = $request->data_invoice;
        $dataSaveVoucher = [
            'date' => $request->date,
            'note' => $request->note,
            'paid_to' => $request->account_id_paid,
            'updated_at' => now()
        ];

        $request->request->add([
            'data_save_voucher' => $dataSaveVoucher
        ]);
    }
}
