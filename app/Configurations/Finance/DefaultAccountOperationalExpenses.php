<?php

namespace App\Configurations\Finance;

use App\Models\Account;
use App\Models\FinanceConfiguration;

class DefaultAccountOperationalExpenses {

    CONST NAME_WHERE = 'Operational Expenses';

    public static function handle($company)
    {
        $accounts = app('data.helper')->getAccountBySpecifiation($company->id, false, Self::NAME_WHERE, Account::EXPENSES);

        if (!empty($accounts)) {
            SavingConfiguration::handle(
                $company->id,
                FinanceConfiguration::CODE_ACCOUNT_OPERATIONAL_EXPENSES,
                FinanceConfiguration::TEXT_ACCOUNT_OPERATIONAL_EXPENSES,
                $accounts
            );
        }
    }
}
