<?php

namespace App\Configurations\Finance;

use App\Models\Account;
use App\Models\FinanceConfiguration;

class DefaultAccountIncomeTax23Payable {

    CONST NAME_WHERE = 'Income Tax Payable Article 23';

    public static function handle($company)
    {
        $accounts = app('data.helper')->getAccountBySpecifiation($company->id, false, Self::NAME_WHERE, Account::LIABILITIES);

        if (!empty($accounts)) {
            SavingConfiguration::handle(
                $company->id,
                FinanceConfiguration::CODE_ACCOUNT_INCOME_TAX23_PAYABLE,
                FinanceConfiguration::TEXT_ACCOUNT_INCOME_TAX23_PAYABLE,
                $accounts
            );
        }
    }
}
