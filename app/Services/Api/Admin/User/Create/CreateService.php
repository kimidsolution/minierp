<?php

namespace App\Services\Api\Admin\User\Create;

use Illuminate\Http\Request;

class CreateService
{
    public function handle(Request $request)
    {
        ValidateRequest::handle($request);
        GetDataCompany::handle($request);
        GetDataUser::handle($request);
        $getData = SaveData::handle($request);
        
        return $getData;
    }
}