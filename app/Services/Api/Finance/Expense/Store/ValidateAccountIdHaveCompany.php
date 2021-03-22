<?php

namespace App\Services\Api\Finance\Expense\Store;

use App\Models\Account;
use Illuminate\Http\Request;

class ValidateAccountIdHaveCompany
{
    public static function handle(Request $request)
    {
        $accountExist = Account::where('id', $request->payment_account_id)->where('company_id', $request->company_id)->first();

        if (is_null($accountExist)) abort(400, 'Account id for paid not match with company id');

        $accountExpenseExist = Account::where('id', $request->expense_account_id)->where('company_id', $request->company_id)->first();

        if (is_null($accountExpenseExist)) abort(400, 'Account id expense not match with company id');
    }
}
