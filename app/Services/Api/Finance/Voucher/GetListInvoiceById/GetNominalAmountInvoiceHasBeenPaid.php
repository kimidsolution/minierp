<?php

namespace App\Services\Api\Finance\Voucher\GetListInvoiceById;

use App\Models\Invoice;
use App\Models\Voucher;
use Illuminate\Http\Request;

class GetNominalAmountInvoiceHasBeenPaid
{
    public static function handle(Request $request)
    {
        $voucherDetails = \App\Models\VoucherDetail::with(['voucher'])->where('invoice_id', $request->invoice_id)->get();
        $filteredVoucherHasBeenPosted = $voucherDetails->filter(function ($value, $key) {
            return $value->voucher->is_posted == Voucher::POSTED_YES;
        });


        if (count($filteredVoucherHasBeenPosted->all()) > 0) {

            $tempNominals = [];
            $filteredVoucherHasBeenPostedAll = $filteredVoucherHasBeenPosted->all();
            foreach ($filteredVoucherHasBeenPostedAll as $key => $value) {
                array_push($tempNominals, $value->amount);
            }

            $totalAmount = Invoice::find($request->invoice_id)->total_amount;
            $nominalRemaining = $totalAmount - array_sum($tempNominals);

        } else {
            $nominalRemaining = Invoice::find($request->invoice_id)->total_amount;
        }

        $request->request->add([
            'remaining_nominal_amount' => $nominalRemaining
        ]);
    }
}