<?php

namespace App\Services\Api\Select2\GetListAccountAssetCompany;

use App\Models\Account;
use League\Fractal\Manager;
use Illuminate\Http\Request;
use League\Fractal\Resource\Collection;

class GetListAccountAssetCompanyService
{
    public function handle(Request $request)
    {
        $accounts = Account::where('company_id', $request->company_id)->where('account_type', Account::ASSETS)->get();

        if ($accounts->count() > 0) {

            $accountsArray = $accounts->toArray();
            $fractal = new Manager();
            $resource = new Collection($accountsArray, function(array $account) {
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
