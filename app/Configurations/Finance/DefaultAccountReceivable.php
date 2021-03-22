<?php

namespace App\Configurations\Finance;

use App\Models\Account;
use App\Models\FinanceConfiguration;

class DefaultAccountReceivable {

    CONST NAME_WHERE = 'Account Receivable';

    public static function handle($company)
    {
        $accounts = app('data.helper')->getAccountBySpecifiation($company->id, false, Self::NAME_WHERE, Account::ASSETS);

        if (!empty($accounts)) {
            SavingConfiguration::handle(
                $company->id,
                FinanceConfiguration::CODE_ACCOUNT_RECEIVABLE,
                FinanceConfiguration::TEXT_ACCOUNT_RECEIVABLE,
                $accounts
            );
        }
    }
}
