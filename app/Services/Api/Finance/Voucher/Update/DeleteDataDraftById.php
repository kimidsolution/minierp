<?php

namespace App\Services\Api\Finance\Voucher\Update;

use Illuminate\Http\Request;
use App\Models\VoucherDetail;
use App\Models\VoucherDetailExpense;

class DeleteDataDraftById
{
    public static function handle(Request $request)
    {
        $voucherDetails = VoucherDetail::where('voucher_id', $request->voucher_id)->get();
        VoucherDetail::where('voucher_id', $request->voucher_id)->delete();

        foreach ($voucherDetails as $key => $value) {
            $voucherDetailExpense = VoucherDetailExpense::where('voucher_detail_id', $value->id)->first();
            if ($voucherDetailExpense->count() > 0) {
                VoucherDetailExpense::where('voucher_detail_id', $value)->delete();
            }
        }
    }
}
