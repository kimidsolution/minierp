<?php

namespace App\Services\Api\Account\Lists\ListsAccountNested;

use App\Models\AccountType;
use Illuminate\Http\Request;
use League\Fractal\Manager;
use League\Fractal\Resource\Collection;

class GetDataAccount
{
    public static function handle(Request $request)
    {
        $account_list = Self::getAccountsByType($request);
        $request->request->add(['data_response' => $account_list]);
    }

    protected static function getAccountType($request)
    {
        if ($request->company_id) {
            return AccountType::where('company_id', '=', $request->company_id)
                ->whereNull('deleted_at')
                ->orderBy('name', 'asc')
                ->get();
        }
        return null;
    }

    protected static function getAccountsByType($request)
    {
        $result = [];
        $fractal = new Manager();

        $account_typ_list = Self::getAccountType($request);
        if (!empty($account_typ_list)) {
            foreach ($account_typ_list as $key => $account_type) {
                if (!empty($account_type->accounts)) {
                    $list_account_of_type = $account_type->accounts()->with('childrens')->parentless()->get();
                    $resource = new Collection($list_account_of_type->toArray(), function(array $account) {
                        $nested_childrens = app('array.helper')->buildTree($account['childrens'], $account['id'], 'parent_account_id', 'id');
                        return [
                            'id' => $account['id'],
                            'uuid' => $account['uuid'],
                            'name' => $account['name'],
                            'number' => $account['number'],
                            'level' => $account['level'],
                            'description' => $account['description'],
                            'parent_account_id' => $account['parent_account_id'],
                            'company_id' => $account['company_id'],
                            'account_type_id' => $account['account_type_id'],
                            'created_by' => $account['created_by'],
                            'updated_by' => $account['updated_by'],
                            'deleted_by' => $account['deleted_by'],
                            'created_at' => $account['created_at'],
                            'updated_at' => $account['updated_at'],
                            'deleted_at' => $account['deleted_at'],
                            'childrens' => $nested_childrens
                        ];
                    });

                    $array = $fractal->createData($resource)->toArray();
                    $arrayData = $array['data'];
                    $result[$key] =[
                        'account_type_id' => $account_type->id,
                        'account_type_name' => $account_type->name,
                        'account_type_code' => $account_type->code,
                        'account_type_balance' => $account_type->balance,
                        'accounts' => $arrayData
                    ];
                }
            }
        }

        return $result;
    }
}
