<?php

namespace App\Services\Api\Finance\Report\TrialBalance;

use DateTime;
use Illuminate\Http\Request;

class CountingBalanceAccountPerMonth
{
    public static function handle(Request $request)
    {
        $result = [];

        $collectionMonths = collect($request->list_month_filtered);
        $mapMonths = $collectionMonths->map(function ($item, $key) {
            return (int) $item;
        });
        $listMonths = $mapMonths->all();
        $contentAccountBalance = $request->content_account_balance;

        foreach ($listMonths as $key => $value) {
            $dt = DateTime::createFromFormat('!m', $value);
            $result[$key]['month_name'] = $dt->format('F');
            $result[$key]['month_num'] = $value;
            $result[$key]['total_mutation_debit'] = self::totalMutation($value, $contentAccountBalance, 'nominal_mutation_debit');
            $result[$key]['total_mutation_credit'] = self::totalMutation($value, $contentAccountBalance, 'nominal_mutation_credit');
        }

        $request->request->add([
            'total_account_group_month' => $result
        ]);
    }

    public static function totalMutation($month, $listAccountBalance, $type)
    {
        $tempMutation = [];

        foreach ($listAccountBalance as $key => $value) {
            $mutation = $value['mutations'];

            foreach ($mutation as $keyM => $valueM) {
                if ($month == $valueM['month_num']) {
                    array_push($tempMutation, $valueM[$type]);
                }
            }
        }

        return array_sum($tempMutation);
    }
}
