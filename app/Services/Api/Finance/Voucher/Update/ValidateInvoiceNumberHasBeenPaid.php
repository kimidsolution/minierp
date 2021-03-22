<?php

namespace App\Services\Api\Finance\Voucher\Update;

use App\Models\Invoice;
use Illuminate\Http\Request;

class ValidateInvoiceNumberHasBeenPaid
{
    public static function handle(Request $request)
    {
        $userCompany = $request->user_company;
        $listInvoices = $request->list_invoice_id;
        $invoicesValidPayInFull = Invoice::whereIn('id', $listInvoices)->where('payment_status', Invoice::STATUS_FULL_PAYMENT)->get();
        $invoicesValidOverpayment = Invoice::whereIn('id', $listInvoices)->where('payment_status', Invoice::STATUS_OVERPAYMENT)->get();

        if ($invoicesValidPayInFull->count() > 0 || $invoicesValidOverpayment->count() > 0)
            abort(400, 'There is an invoice paid off');
    }
}