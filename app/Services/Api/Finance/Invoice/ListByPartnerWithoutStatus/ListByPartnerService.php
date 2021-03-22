<?php

namespace App\Services\Api\Finance\Invoice\ListByPartnerWithoutStatus;

use Illuminate\Http\Request;

class ListByPartnerService
{
    public function handle(Request $request)
    {
        ValidateRequest::handle($request);
        GetDataCompany::handle($request);
        ValidatePartnerIsHaveCompany::handle($request);
        GetListInvoiceHaveNotPaidByPartner::handle($request);

        return response()->api(true, [], $request->data_response);
    }
}