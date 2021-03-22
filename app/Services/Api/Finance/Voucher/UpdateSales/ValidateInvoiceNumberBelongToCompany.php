<?php

namespace App\Services\Api\Finance\Voucher\UpdateSales;

use Illuminate\Http\Request;

class ValidateInvoiceNumberBelongToCompany
{
    public static function handle(Request $request)
    {
        $userCompany = $request->user_company;
        $invoice = \App\Models\Invoice::where('number', $request->invoice_number)
                        ->where('company_id', $userCompany->company_id)
                        ->first();

        if (is_null($invoice))
            abort(400, 'Invoice tidak ditemukan');

        $request->request->add([
            'data_invoice' => $invoice
        ]);
    }
}
