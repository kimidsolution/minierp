<?php

namespace App\Services\Api\Finance\Report\TrialBalance;

use DateTime;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CreateRangeMonth
{
    public static function handle(Request $request)
    {
        $listMonths = [];
        $start = Carbon::parse($request->start_period)->format('m');
        $end = Carbon::parse($request->end_period)->format('m');

        array_push($listMonths, $start);
        array_push($listMonths, $end);

        for ((int) $i= $start; $i < (int) $end ; $i++) {
            if (true == ($i + 1) < $end) {
                array_push($listMonths, $i + 1);
            }
        }

        foreach ($listMonths as $key => $value) {
            $dt = DateTime::createFromFormat('!m', $value);
            $month = $dt->format('m');
            $listMonths[$key] = $month;
        }

        $listMonths = array_unique($listMonths);
        asort($listMonths);
        $listMonths = array_values($listMonths);

        $request->request->add([
            'list_month_filtered' => $listMonths
        ]);
    }
}
