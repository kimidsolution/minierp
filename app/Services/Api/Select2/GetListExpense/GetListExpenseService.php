<?php

namespace App\Services\Api\Select2\GetListExpense;

use App\Models\Account;
use League\Fractal\Manager;
use Illuminate\Http\Request;
use League\Fractal\Resource\Collection;

class GetListExpenseService
{
    public function handle(Request $request)
    {
        $accounts = Account::where('account_type', $request->account_type)
                    ->where('level', $request->level)
                    ->where('company_id', $request->company_id)
                    ->get();

        if ($accounts->count() > 0) {

            $accountArray = $accounts->toArray();
            $fractal = new Manager();
            $resource = new Collection($accountArray, function(array $account) {
                return [
                    'id' => $account['id'],
                    'text' => $account['account_code'] . '  -  ' . $account['account_name']
                ];
            });

            $source = $fractal->createData($resource)->toArray();
            $array = $source['data'];
        } else {
            $array = [];
        }

        return response()->api(true, [], $array, '', 200);
    }
}
