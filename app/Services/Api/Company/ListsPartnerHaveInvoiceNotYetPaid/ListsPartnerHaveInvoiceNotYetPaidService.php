<?php

namespace App\Services\Api\Company\ListsPartnerHaveInvoiceNotYetPaid;

use Illuminate\Http\Request;

class ListsPartnerHaveInvoiceNotYetPaidService
{
    public function handle(Request $request)
    {
        ValidateRequest::handle($request);
        GetDataCompany::handle($request);
        GetPartnerOfCompany::handle($request);
        FilterPartnerHaveInvoiceNotYetPaid::handle($request);

        return response()->api(true, [], $request->data_response);
    }
}