<?php

namespace App\Services\Api\Product\ListsByCompany;

use Illuminate\Http\Request;

class ListService
{
    public function handle(Request $request)
    {
        ValidateRequest::handle($request);
        GetDataCompany::handle($request);
        GetDataProduct::handle($request);

        return response()->api(true, [], $request->data_response);
    }
}
