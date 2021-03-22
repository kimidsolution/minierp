<?php

namespace App\Services\Api\Finance\Report\GeneralLedger;

use App\Models\Account;
use Illuminate\Http\Request;

class CountingTransactionAccount
{
    public static function handle(Request $request)
    {
        $transactionGl = $request->list_data_without_balance;
        $accountFiltered = Account::where('id', $request->account_id)->first();
        if (count($transactionGl) > 1) {
            foreach ($transactionGl as $key => $value) {
                $debit = $value['debit'];
                $credit = $value['credit'];
                $nominal = (empty($debit)) ? $credit : $debit;
                $typeNominalBalance = (empty($debit)) ? 'credit' : 'debit';

                $lastKeyUsed = ($key - 1) < 0 ? 0 : ($key - 1);

                if ($value['is_first_key'] == true) {
                    if ($request->links['current_page'] == 1) {
                        $transactionGl[$key]['balance'] = (float) 0;
                    } else {
                        $transactionGl[$key]['balance'] = $request->last_amount_row;
                    }
                }

                $lastDataUsed = $transactionGl[$lastKeyUsed];
                $nominalLastBalance = $lastDataUsed['balance'];

                if ('debit' == $accountFiltered->balance) {
                    // nilai bertambah di debit
                    // nilai kurang di credit
                    $nominalBalance = ('debit' ==  $typeNominalBalance) ? ($nominalLastBalance + $nominal) : ($nominalLastBalance - $nominal);
                    $iconBalance = ('debit' ==  $typeNominalBalance) ?
                        'mdi mdi mdi-arrow-up-thick text-success ml-1' : 'mdi mdi mdi-arrow-down-thick text-danger ml-1';

                    $transactionGl[$key]['balance'] = $nominalBalance;
                    $transactionGl[$key]['icon_balance'] = $iconBalance;

                } else {
                    // nilai bertambah di credit
                    // nilai kurang di debit
                    $nominalBalance = ('debit' ==  $typeNominalBalance) ? ($nominalLastBalance - $nominal) : ($nominalLastBalance + $nominal);
                    $iconBalance = ('debit' ==  $typeNominalBalance) ? '
                        mdi mdi mdi-arrow-down-thick text-danger ml-1' : 'mdi mdi mdi-arrow-up-thick text-success ml-1';

                    $transactionGl[$key]['balance'] = $nominalBalance;
                    $transactionGl[$key]['icon_balance'] = $iconBalance;
                }
            }
        }

        $request->request->add(['list_data_with_balance' => $transactionGl]);
    }
}
