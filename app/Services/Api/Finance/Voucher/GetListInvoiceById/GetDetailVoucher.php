<?php

namespace App\Services\Api\Finance\Voucher\GetListInvoiceById;

use App\Models\Voucher;
use Illuminate\Http\Request;

class GetDetailVoucher
{
    public static function handle(Request $request)
    {
        $voucher = Voucher::with(['voucher_details.voucher_detail_expenses', 'invoices'])->find($request->voucher_id);

        if (is_null($voucher))
            abort(400, 'Voucher id not found');

        $request->request->add([
            'voucher' => $voucher
        ]);
    }
}