<?php

namespace App\Services\Api\Finance\Report\TrialBalance;

use Carbon\Carbon;
use Illuminate\Http\Request;

class ValidateRangeDate
{
    public static function handle(Request $request)
    {
        $startYear = Carbon::parse($request->start_period)->format('Y');
        $endYear = Carbon::parse($request->end_period)->format('Y');

        if ($startYear != $endYear)
            abort(400, 'Tahun tidak sama');
    }
}
