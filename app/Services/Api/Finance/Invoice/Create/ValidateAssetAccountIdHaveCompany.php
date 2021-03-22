<?php

namespace App\Services\Api\Finance\Invoice\Create;

use App\Models\Account;
use Illuminate\Http\Request;

class ValidateAssetAccountIdHaveCompany
{
    public static function handle(Request $request)
    {
        $dataCompany = $request->data_company;
        $accountExist = Account::where('id', $request->asset_account_id)
                            ->where('company_id', $dataCompany['company_id'])
                            ->first();

        if (is_null($accountExist))
            abort(400, 'Asset account id not match with company id');
    }
}
