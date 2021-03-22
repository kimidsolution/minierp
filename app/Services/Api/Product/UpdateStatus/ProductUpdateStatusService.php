<?php

namespace App\Services\Api\Product\UpdateStatus;

use Illuminate\Http\Request;

class ProductUpdateStatusService
{
    public function handle(Request $request)
    {
        ValidateRequest::handle($request);
        $response = StoreData::handle($request);
        return $response;
    }
}
