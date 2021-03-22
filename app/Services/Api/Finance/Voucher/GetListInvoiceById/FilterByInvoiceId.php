<?php

namespace App\Services\Api\Finance\Voucher\GetListInvoiceById;

use Illuminate\Http\Request;

class FilterByInvoiceId
{
    public static function handle(Request $request)
    {
        $invoiceId = $request->invoice_id;
        $voucherDetails = $request->voucher->voucher_details;
        $invoices = $request->voucher->invoices;

        $voucherInvoiceFiltered = $voucherDetails->filter(function ($value, $key) use ($invoiceId) {
            return $value->invoice_id == $invoiceId;
        });

        $invoiceFiltered = $invoices->filter(function ($value, $key) use ($invoiceId) {
            return $value->id == $invoiceId;
        });

        if (count($voucherInvoiceFiltered->all()) < 1)
            abort(400, 'Invoice id not found');

        $request->request->add([
            'voucher_invoice_filtered' => $voucherInvoiceFiltered->first(),
            'invoice_filtered' => $invoiceFiltered->first()
        ]);
    }
}