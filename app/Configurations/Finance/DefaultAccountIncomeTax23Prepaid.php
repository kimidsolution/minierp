<?php

namespace App\Configurations\Finance;

use App\Models\Account;
use App\Models\FinanceConfiguration;

class DefaultAccountIncomeTax23Prepaid {

    CONST NAME_WHERE = 'Prepaid Income Tax Article 23';

    public static function handle($company)
    {
        $accounts = app('data.helper')->getAccountBySpecifiation($company->id, false, Self::NAME_WHERE, Account::ASSETS);

        if (!empty($accounts)) {
            SavingConfiguration::handle(
                $company->id,
                FinanceConfiguration::CODE_ACCOUNT_INCOME_TAX23_PREPAID,
                FinanceConfiguration::TEXT_ACCOUNT_INCOME_TAX23_PREPAID,
                $accounts
            );
        }
    }
}
