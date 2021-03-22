<?php

namespace App\Configurations\Finance;

use App\Models\Account;
use App\Models\FinanceConfiguration;

class DefaultAccountSalesDiscounts {

    CONST NAME_WHERE = 'Sales Discounts';

    public static function handle($company)
    {
        $accounts = app('data.helper')->getAccountBySpecifiation($company->id, false, Self::NAME_WHERE, Account::INCOME);

        if (!empty($accounts)) {
            SavingConfiguration::handle(
                $company->id,
                FinanceConfiguration::CODE_ACCOUNT_SALES_DISCOUNTS,
                FinanceConfiguration::TEXT_ACCOUNT_SALES_DISCOUNTS,
                $accounts
            );
        }
    }
}
