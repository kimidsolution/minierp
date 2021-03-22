<?php

namespace App\Configurations\Finance;

use App\Models\Account;
use App\Models\FinanceConfiguration;

class DefaultAccountVatOut {

    CONST NAME_WHERE = 'Vat Out';

    public static function handle($company)
    {
        $accounts = app('data.helper')->getAccountBySpecifiation($company->id, false, Self::NAME_WHERE, Account::LIABILITIES);

        if (!empty($accounts)) {
            SavingConfiguration::handle(
                $company->id,
                FinanceConfiguration::CODE_ACCOUNT_VAT_OUT,
                FinanceConfiguration::TEXT_ACCOUNT_VAT_OUT,
                $accounts
            );
        }
    }
}
