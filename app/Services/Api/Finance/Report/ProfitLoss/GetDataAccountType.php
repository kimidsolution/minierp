<?php

namespace App\Services\Api\Finance\Report\ProfitLoss;

use App\Models\Account;
use Illuminate\Http\Request;

class GetDataAccountType
{
    public static function handle(Request $request)
    {
        $account_type_income = Self::getAccountTypeByBalance(Account::INCOME);
        $account_type_cogs = Self::getAccountTypeByBalance(Account::COGS);
        $account_type_expense = Self::getAccountTypeByBalance(Account::EXPENSES);
        $account_type_other_income = Self::getAccountTypeByBalance(Account::OTHER_INCOME);
        $account_type_other_expense = Self::getAccountTypeByBalance(Account::OTHER_EXPENSES);
        $request->request->add([
            'is_profit_loss' => true,
            'data_account_type_income' => $account_type_income,
            'data_account_type_cogs' => $account_type_cogs,
            'data_account_type_expense' => $account_type_expense,
            'data_account_type_other_income' => $account_type_other_income,
            'data_account_type_other_expense' => $account_type_other_expense,
        ]);
    }

    protected static function getAccountTypeByBalance($id_type)
    {
        $result = [];
        if ($id_type) {
            foreach (config('sempoa.coa_type') as $data) {
                if ($data['id'] == $id_type) array_push($result, $data);
            }
        }
        return $result;
    }

}
