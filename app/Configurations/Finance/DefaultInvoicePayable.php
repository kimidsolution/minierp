<?php

namespace App\Configurations\Finance;

use App\Models\Account;
use App\Models\FinanceConfiguration;

class DefaultInvoicePayable {

    CONST NAME_WHERE = 'Cash';

    public static function handle($company)
    {
        $accounts = app('data.helper')->getAccountBySpecifiation($company->id, false, Self::NAME_WHERE, Account::ASSETS);

        if (!empty($accounts)) {
            SavingConfiguration::handle(
                $company->id,
                FinanceConfiguration::CODE_ACCOUNT_DP_INVOICE_PAYABLE,
                FinanceConfiguration::TEXT_ACCOUNT_DP_INVOICE_PAYABLE,
                $accounts
            );
        }
    }
}
