<?php

namespace App\Configurations\Finance;

use App\Models\Account;
use App\Models\FinanceConfiguration;

class DefaultTax {

    CONST NAME_WHERE = 'Tax';

    public static function handle($company)
    {
        $accounts = app('data.helper')
            ->getAccountBySpecifiation($company->id, true, Self::NAME_WHERE, Account::EXPENSES)
            ->toArray();

        if (!empty($accounts)) {
            SavingConfiguration::handle(
                $company->id,
                FinanceConfiguration::CODE_ACCOUNT_ANNUAL_TAX,
                FinanceConfiguration::TEXT_ACCOUNT_ANNUAL_TAX,
                $accounts
            );
        }
    }
}
