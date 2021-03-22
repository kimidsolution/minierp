<?php

namespace App\Services\Api\Company\ListsPartner;

use App\Models\UsersCompany;
use Illuminate\Http\Request;

class GetDataCompany
{
    public static function handle(Request $request)
    {
        $userCompany = UsersCompany::with(['company'])->where('user_id', $request->user_id)->first();

        if (is_null($userCompany))
            abort(400, 'Anda tidak terdaftar di perusahaan manapun');


        $request->request->add([
            'user_company' => $userCompany
        ]);
    }
}
