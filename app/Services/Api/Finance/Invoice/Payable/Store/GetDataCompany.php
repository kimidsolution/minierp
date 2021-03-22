<?php

namespace App\Services\Api\Finance\Invoice\Payable\Store;

use Illuminate\Http\Request;

class GetDataCompany
{
    public static function handle(Request $request)
    {
        $user = \App\Models\User::with(['company'])->where('company_id', $request->company_id)->first();

        if (is_null($user))
            abort('Data user not found');

        $request->request->add([
            'data_company' => $user->toArray()
        ]);
    }
}
