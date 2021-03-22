<?php

namespace App\Services\Api\ProductCategory\ListsByCompany;

use Illuminate\Http\Request;

class ListService
{
    public function handle(Request $request)
    {
        ValidateRequest::handle($request);
        GetDataCompany::handle($request);
        GetDataProductCategory::handle($request);

        return response()->api(true, [], $request->data_response);
    }
}
