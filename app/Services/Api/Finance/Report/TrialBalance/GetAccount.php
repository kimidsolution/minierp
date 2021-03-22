<?php

namespace App\Services\Api\Finance\Report\TrialBalance;

use Illuminate\Http\Request;

class GetAccount
{
    public static function handle(Request $request)
    {
        $companyAccounts = \App\Models\Account::where('company_id', $request->company_id)->orderBy('account_code', 'ASC')->get();

        if ($companyAccounts->count() > 0) {

            $request->request->add([
                'list_account_by_company_collection' => $companyAccounts
            ]);
        }
    }
}
