<?php

namespace App\Configurations\Finance;

use App\Models\Account;
use App\Models\FinanceConfiguration;

class DefaultAccountArSales {

    CONST NAME_WHERE = 'AR Sales';

    public static function handle($company)
    {
        $accounts = app('data.helper')->getAccountBySpecifiation($company->id, false, Self::NAME_WHERE, Account::ASSETS);

        if (!empty($accounts)) {
            SavingConfiguration::handle(
                $company->id,
                FinanceConfiguration::CODE_ACCOUNT_AR_SALES,
                FinanceConfiguration::TEXT_ACCOUNT_AR_SALES,
                $accounts
            );
        }
    }
}
