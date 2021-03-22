<?php

namespace App\Services\Api\Finance\Voucher\Create;

use Illuminate\Http\Request;

class ValidateInvoiceNumberHasBeenPaid
{
    public static function handle(Request $request)
    {
        $userCompany = $request->user_company;
        $listInvoices = $request->list_invoice_id;
        $invoicesValidPayInFull = \App\Models\Invoice::whereIn('id', $listInvoices)->where('status_paid', 'pay in full')->get();
        $invoicesValidOverpayment = \App\Models\Invoice::whereIn('id', $listInvoices)->where('status_paid', 'overpayment')->get();

        if ($invoicesValidPayInFull->count() > 0 || $invoicesValidOverpayment->count() > 0)
            abort(400, 'Ada invoice id yang sudah lunas');
    }
}