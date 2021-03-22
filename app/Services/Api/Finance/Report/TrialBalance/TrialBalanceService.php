<?php

namespace App\Services\Api\Finance\Report\TrialBalance;

use Illuminate\Http\Request;

class TrialBalanceService
{
    public function handle(Request $request)
    {
        ValidateRequest::handle($request);
        ValidateRangeDate::handle($request);
        CreateRangeMonth::handle($request);
        GetStartLastDateRequest::handle($request);
        GetAccount::handle($request);
        GetTransaction::handle($request);
        CountingBalance::handle($request);
        CountingBalanceAccountPerMonth::handle($request);

        $data = [
            'content_account_balances' => $request->content_account_balance,
            'total_account_group_month' => [
                'mutations' => $request->total_account_group_month
            ]
        ];

        return response()->api(true, [], $data, '', 200);
    }
}
