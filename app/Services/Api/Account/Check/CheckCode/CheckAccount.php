<?php

namespace App\Services\Api\Account\Check\CheckCode;

use App\Models\Account;
use Illuminate\Http\Request;

class CheckAccount
{
    public static function handle(Request $request)
    {
        $account_code = preg_replace('/\s+/', '', $request->code);
        $get_data = Account::where('company_id', $request->company_id)->where('account_code', $account_code);
        if ($request->except_id) {
            $get_data->whereNotIn('id', [$request->except_id]);
        }
        $account_code_company = $get_data->get();
        $request->request->add([
            'is_unique' => count($account_code_company) == 0,
            'data_response' => $account_code_company
        ]);
    }
}
