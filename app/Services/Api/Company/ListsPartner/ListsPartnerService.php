<?php

namespace App\Services\Api\Company\ListsPartner;

use Illuminate\Http\Request;

class ListsPartnerService
{
    public function handle(Request $request)
    {
        ValidateRequest::handle($request);
        GetDataCompany::handle($request);
        GetPartnerOfCompany::handle($request);

        return response()->api(true, [], $request->data_response);
    }
}