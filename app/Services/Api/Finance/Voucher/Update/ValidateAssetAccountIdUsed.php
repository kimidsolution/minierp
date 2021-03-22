<?php

namespace App\Services\Api\Finance\Voucher\Update;

use Illuminate\Http\Request;

class ValidateAssetAccountIdUsed
{
    public static function handle(Request $request)
    {
        $userCompany = $request->user_company;
        $account = \App\Models\Account::where('id', $request->asset_account_id_used)
                        ->where('company_id', $userCompany->company_id)
                        ->first();

        if (is_null($account))
            abort(400, 'Invalid account id used');
    }
}
