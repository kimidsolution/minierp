<?php

namespace App\Services\Finance\Revenue\Store;

use App\Models\Account;
use Illuminate\Http\Request;

class ValidateAssetAccountIdHaveCompany
{
    public static function handle(Request $request)
    {
        $dataUser = $request->user;
        $accountExist = Account::where('id', $request->paid_to)
                            ->where('company_id', $dataUser['company_id'])
                            ->first();

        if (is_null($accountExist))
            abort(400, 'Account id for paid not match with company id');
    }
}
