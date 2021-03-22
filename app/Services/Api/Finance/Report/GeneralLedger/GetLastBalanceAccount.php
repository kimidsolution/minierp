<?php

namespace App\Services\Api\Finance\Report\GeneralLedger;

use App\Models\AccountBalance;
use Illuminate\Http\Request;

class GetLastBalanceAccount
{
    public static function handle(Request $request)
    {
        $lastBalanceAccount = AccountBalance::where('account_id', $request->account_id)->orderBy('balance_date', 'desc')->first();

        if (is_null($lastBalanceAccount)) {
            $request->request->add([
                'last_balance_account' => [
                    'debit' => 0,
                    'credit' => 0,
                    'balance' => 0,
                    'date' => '',
                    'reference_number' => '',
                    'description' => 'Beginning Balance Of Account',
                    'transaction' => '',
                    'account_balance_name' => ''
                ]
            ]);
        } else {
            $request->request->add([
                'last_balance_account' => [
                    'debit' => $lastBalanceAccount->debit_amount,
                    'credit' => $lastBalanceAccount->credit_amount,
                    'balance' => (0 == $lastBalanceAccount->debit_amount) ? $lastBalanceAccount->credit_amount : $lastBalanceAccount->debit_amount,
                    'date' => '',
                    'reference_number' => '',
                    'description' => 'Beginning Balance Of Account',
                    'transaction' => '',
                    'account_balance_name' => $lastBalanceAccount->account->account_name
                ]
            ]);
        }
    }
}
