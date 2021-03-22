<?php

namespace App\Services\Api\Account\Lists\ListsParent;

use App\Models\Account;
use Illuminate\Http\Request;

class GetDataParent
{
    public static function handle(Request $request)
    {
        $account = Account::withTrashed()
            ->where('company_id', $request->company_id)
            ->where('account_type', $request->account_type);
        if ($request->except_id) {
            $account->whereNotIn('id', [$request->except_id]);
        }
        $data = $account->orderBy('account_name', 'asc')->get();
        $request->request->add(['data_response' => $data]);
    }
}
