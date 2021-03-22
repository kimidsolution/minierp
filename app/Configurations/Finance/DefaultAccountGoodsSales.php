<?php

namespace App\Configurations\Finance;

use App\Models\Account;
use App\Models\FinanceConfiguration;

class DefaultAccountGoodsSales {

    CONST NAME_WHERE = 'Goods Sales';

    public static function handle($company)
    {
        $accounts = app('data.helper')->getAccountBySpecifiation($company->id, false, Self::NAME_WHERE, Account::INCOME);

        if (!empty($accounts)) {
            SavingConfiguration::handle(
                $company->id,
                FinanceConfiguration::CODE_ACCOUNT_GOODS_SALES,
                FinanceConfiguration::TEXT_ACCOUNT_GOODS_SALES,
                $accounts
            );
        }
    }
}
