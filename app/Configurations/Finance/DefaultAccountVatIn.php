<?php

namespace App\Configurations\Finance;

use App\Models\Account;
use App\Models\FinanceConfiguration;

class DefaultAccountVatIn {

    CONST NAME_WHERE = 'Vat In';

    public static function handle($company)
    {
        $accounts = app('data.helper')->getAccountBySpecifiation($company->id, false, Self::NAME_WHERE, Account::ASSETS);

        if (!empty($accounts)) {
            SavingConfiguration::handle(
                $company->id,
                FinanceConfiguration::CODE_ACCOUNT_VAT_IN,
                FinanceConfiguration::TEXT_ACCOUNT_VAT_IN,
                $accounts
            );
        }
    }
}
