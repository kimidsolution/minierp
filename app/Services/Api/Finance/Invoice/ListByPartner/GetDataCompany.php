<?php

namespace App\Services\Api\Finance\Invoice\ListByPartner;

use App\User;
use Illuminate\Http\Request;

class GetDataCompany
{
    public static function handle(Request $request)
    {
        $userCompany = User::with(['company'])->where('company_id', $request->company_id)->first();

        if (is_null($userCompany))
            abort(400, 'Anda tidak terdaftar di perusahaan manapun');


        $request->request->add([
            'user_company' => $userCompany
        ]);
    }
}
