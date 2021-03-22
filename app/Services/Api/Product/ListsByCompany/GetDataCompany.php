<?php

namespace App\Services\Api\Product\ListsByCompany;

use App\User;
use App\Models\Company;
use Illuminate\Http\Request;

class GetDataCompany
{
    public static function handle(Request $request)
    {
        $user = Company::find($request->company_id);

        if (is_null($user))
            abort('Data company not found');

        $request->request->add([
            'data_company' => $user->toArray()
        ]);
    }
}
