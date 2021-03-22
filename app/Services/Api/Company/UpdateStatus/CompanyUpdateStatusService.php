<?php

namespace App\Services\Api\Company\UpdateStatus;

use Illuminate\Http\Request;

class CompanyUpdateStatusService
{
    public function handle(Request $request)
    {
        ValidateRequest::handle($request);
        $response = StoreData::handle($request);
        return $response;
    }
}
