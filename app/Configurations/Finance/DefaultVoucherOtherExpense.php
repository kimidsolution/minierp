<?php

namespace App\Configurations\Finance;

use App\Models\Account;
use App\Models\FinanceConfiguration;

class DefaultVoucherOtherExpense {

    CONST NAME_WHERE = 'Bank Administration';

    public static function handle($company)
    {
        $accounts = app('data.helper')->getAccountBySpecifiation($company->id, false, Self::NAME_WHERE, Account::OTHER_EXPENSES);

        if (!empty($accounts)) {
            SavingConfiguration::handle(
                $company->id,
                FinanceConfiguration::CODE_ACCOUNT_OTHER_EXPENSE_VOUCHER,
                FinanceConfiguration::TEXT_ACCOUNT_OTHER_EXPENSE_VOUCHER,
                $accounts
            );
        }
    }
}
