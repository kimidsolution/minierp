<?php

namespace App\Services\Finance\Revenue\Store;

use App\Models\Account;
use Illuminate\Http\Request;

class ValidateItems
{
    public static function handle(Request $request)
    {
        $dataUser = $request->user;
        $items = $request->items;

        foreach ($items as $key => $value) {
            $paccountIsHaveCompany = Account::where('id', $value['account_id'])->where('company_id', $dataUser['company_id'])->first();
            if (is_null($paccountIsHaveCompany))
                abort(400, 'Invalid account id id');
        }
    }
}
