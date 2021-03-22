<?php

namespace App\Services\Api\Finance\Voucher\Create;

use App\Models\Invoice;
use Illuminate\Http\Request;

class ValidateInvoiceIdHaveCompanyWithPartner
{
    public static function handle(Request $request)
    {
        $userCompany = $request->user_company;
        $invalidInvoices = Invoice::whereIn('id', $request->list_invoice_id)
                            ->where('company_id', $userCompany->company_id)
                            ->where('partner_id', $request->partner_id)
                            ->get();

        if ($invalidInvoices->count() != count($request->list_invoice_id))
            abort(400, 'Ada invoice id yang tidak sesuai');
    }
}
