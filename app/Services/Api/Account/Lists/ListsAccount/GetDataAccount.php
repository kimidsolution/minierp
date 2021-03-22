<?php

namespace App\Services\Api\Account\Lists\ListsAccount;

use App\Models\Account;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GetDataAccount
{
    public static function handle(Request $request)
    {
        $request_account_type = !is_null($request->account_type) ? $request->account_type : [];

        $query = Account::select(
            DB::raw("CONCAT(
                account_code, ' - ',
                IF ((account_text IS NOT NULL OR account_text != ''), account_text, account_name)
            ) AS account_naming"),'id'
        )->where('company_id', $request->company_id);

        if (!empty($request->except_id))
            $query->whereNotIn('id', $request->except_id);
        if (!is_null($request->account_type))
            $query->whereIn('account_type', $request_account_type);
        if (!is_null($request->level))
            $query->where('level', $request->level);

        $account_list = $query->orderBy('account_code', 'asc')->get();
        $request->request->add(['data_response' => $account_list->toArray()]);
    }
}
