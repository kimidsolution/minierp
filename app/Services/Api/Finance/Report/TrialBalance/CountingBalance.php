<?php

namespace App\Services\Api\Finance\Report\TrialBalance;

use DateTime;
use Carbon\Carbon;
use App\Models\Account;
use League\Fractal\Manager;
use Illuminate\Http\Request;
use App\Models\AccountBalance;
use League\Fractal\Resource\Collection;

class CountingBalance
{
    public static function handle(Request $request)
    {
        $result = [];
        $openingDate = $request->opening_date;
        $listMonthFiltered = $request->list_month_filtered;
        $year = Carbon::parse($request->start_period)->format('Y');
        $listAccounts = $request->list_account_by_company_collection->toArray();
        $listTransactions = $request->list_transactions;

        $fractal = new Manager();
        $resource = new Collection($listAccounts, function(array $account) use ($year, $openingDate, $listMonthFiltered, $listTransactions) {
            $accountOpeningBalance = self::getOpeningBalance($account['id'], $openingDate);
            $accountMutations = self::getMutationBalance($account['id'], $listMonthFiltered, $listTransactions);
            $nominalNetMutations = self::getNominalMutation($account['id'], $accountMutations);
            return [
                'account_code' => $account['account_code'],
                'account_description' => $account['account_name'],
                'account_balance_year' => $year,
                'nominal_open_balance' => $accountOpeningBalance,
                'mutations' => $accountMutations,
                'nominal_net_mutation' => $nominalNetMutations,
                'nominal_end_balance' => $accountOpeningBalance + $nominalNetMutations
            ];
        });

        $array = $fractal->createData($resource)->toArray();

        $request->request->add([
            'content_account_balance' => $array['data']
        ]);
    }

    public static function getOpeningBalance($accountId, $date)
    {
        $accountBalance = AccountBalance::where('account_id', $accountId)->where('is_closed', 1)
                            ->whereDate('balance_date', $date)
                            ->first();

        if ($accountBalance) {
            return ('debit' == $accountBalance->account->balance) ? $accountBalance->debit : $accountBalance->credit;
        }

        return 0;
    }

    public static function getMutationBalance($accountId, $listMonthFiltered, $listTransactions)
    {
        $results = [];

        foreach ($listMonthFiltered as $key => $value) {
            $filteredByAccountAndMound = $listTransactions->filter(function ($valueT, $keyT) use ($accountId, $value) {
                if ($accountId == $valueT->account_id) {
                    $monthTransaction = Carbon::parse($valueT->transaction->transaction_date)->format('m');
                    if ($monthTransaction == $value) {
                        return true;
                    }
                }
                return false;
            });

            $transactionFiltered = $filteredByAccountAndMound->all();

            $dateObj = DateTime::createFromFormat('!m', $value);
            $results[$key]['month_name'] = $dateObj->format('F');
            $results[$key]['month_num'] = (int) $value;

            if (count($transactionFiltered) > 0) {

                $nominalNetMutationDebit = [];
                $nominalNetMutationCredit = [];

                foreach ($transactionFiltered as $keyF => $valueF) {
                    array_push($nominalNetMutationDebit, $valueF->debit_amount);
                    array_push($nominalNetMutationCredit, $valueF->credit_amount);
                }

                $results[$key]['nominal_mutation_debit'] = array_sum($nominalNetMutationDebit);
                $results[$key]['nominal_mutation_credit'] = array_sum($nominalNetMutationCredit);

            } else {
                $results[$key]['nominal_mutation_debit'] = 0;
                $results[$key]['nominal_mutation_credit'] = 0;
            }
        }

        return $results;
    }

    public static function getNominalMutation($accountId, $accountMutations)
    {
        $account = Account::where('id', $accountId)->first();
        $nominalDebit = array_sum(array_column($accountMutations, 'nominal_mutation_debit'));
        $nominalCredit = array_sum(array_column($accountMutations, 'nominal_mutation_credit'));

        if ('debit' == $account->balance) {
            return ($nominalDebit - $nominalCredit);
        } else {
            return ($nominalCredit - $nominalDebit);
        }
    }
}
