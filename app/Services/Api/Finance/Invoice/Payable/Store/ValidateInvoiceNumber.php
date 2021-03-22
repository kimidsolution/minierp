<?php

namespace App\Services\Api\Finance\Invoice\Payable\Store;

use Illuminate\Http\Request;

class ValidateInvoiceNumber
{
    public static function handle(Request $request)
    {
        $dataCompany = $request->data_company;
        $invoiceNumberExist = \App\Models\Invoice::where('company_id', $dataCompany['company']['id'])
                                ->where('partner_id', $request->partner_id)
                                ->where('number', $request->invoice_number)
                                ->first();


        if ($invoiceNumberExist)
            abort(400, 'Invoice number already exist');
    }
}
