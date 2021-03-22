<?php

namespace App\Services\Api\Finance\Voucher\UpdateSales;

use Illuminate\Http\Request;

class ValidateAccountIdPaid
{
    public static function handle(Request $request)
    {
        $userCompany = $request->user_company;
        $account = \App\Models\Account::where('id', $request->account_id_paid)
                        ->where('company_id', $userCompany->company_id)
                        ->first();

        if (is_null($account))
            abort(400, 'Invalid account id for paid');
    }
}
