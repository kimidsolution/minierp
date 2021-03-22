<?php

namespace App\Services\Api\Finance\Voucher\GetListInvoiceById;

use Illuminate\Http\Request;

class SetResponse
{
    public static function handle(Request $request)
    {
        $voucher = $request->voucher;
        $voucherInvoiceFiltered = $request->voucher_invoice_filtered;
        $invoiceFiltered = $request->invoice_filtered;

        $dataResponse = [
            'nominal_amount' => $voucherInvoiceFiltered->amount,
            'remaining_nominal_amount_must_be_paid' => $request->remaining_nominal_amount,
            'description' => $invoiceFiltered->note,
            'detail_expenses' => $voucherInvoiceFiltered->voucher_detail_expenses->toArray()
        ];

        $request->request->add([
            'data_response' => $dataResponse
        ]);
    }
}