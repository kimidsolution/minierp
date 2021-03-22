<?php

namespace App\Configurations\Finance;

use App\Models\Account;
use App\Models\FinanceConfiguration;

class DefaultAccountRevRecInAdv {

    CONST NAME_WHERE = 'Revenue Received In Advanced';

    public static function handle($company)
    {
        $accounts = app('data.helper')->getAccountBySpecifiation($company->id, false, Self::NAME_WHERE, Account::LIABILITIES);

        if (!empty($accounts)) {
            SavingConfiguration::handle(
                $company->id,
                FinanceConfiguration::CODE_ACCOUNT_REVENUE_RECEIVED_IN_ADVANCED,
                FinanceConfiguration::TEXT_ACCOUNT_REVENUE_RECEIVED_IN_ADVANCED,
                $accounts
            );
        }
    }
}
