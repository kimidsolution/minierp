<?php

namespace App\Services\Api\Admin\User\ListByCompany;

use Illuminate\Http\Request;

class ListUserService
{
    public function handle(Request $request)
    {
        ValidateRequest::handle($request);
        GetDataCompany::handle($request);
        GetDataUser::handle($request);

        return response()->api(true, [], $request->data_response);
    }
}