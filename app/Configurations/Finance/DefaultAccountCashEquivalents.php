<?php

namespace App\Configurations\Finance;

use App\Models\Account;
use App\Models\FinanceConfiguration;

class DefaultAccountCashEquivalents {

    CONST NAME_WHERE = 'Cash & Equivalents';

    public static function handle($company)
    {
        $accounts = app('data.helper')->getAccountBySpecifiation($company->id, false, Self::NAME_WHERE, Account::ASSETS);

        if (!empty($accounts)) {
            SavingConfiguration::handle(
                $company->id,
                FinanceConfiguration::CODE_ACCOUNT_CASH_EQUIVALENTS,
                FinanceConfiguration::TEXT_ACCOUNT_CASH_EQUIVALENTS,
                $accounts
            );
        }
    }
}
