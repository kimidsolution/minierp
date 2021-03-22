<?php

namespace App\Configurations\Finance;

use App\Models\Account;
use App\Models\FinanceConfiguration;

class DefaultAccountTradePayable {

    CONST NAME_WHERE = 'Trade Payable';

    public static function handle($company)
    {
        $accounts = app('data.helper')->getAccountBySpecifiation($company->id, false, Self::NAME_WHERE, Account::LIABILITIES);

        if (!empty($accounts)) {
            SavingConfiguration::handle(
                $company->id,
                FinanceConfiguration::CODE_ACCOUNT_TRADE_PAYABLE,
                FinanceConfiguration::TEXT_ACCOUNT_TRADE_PAYABLE,
                $accounts
            );
        }
    }
}
