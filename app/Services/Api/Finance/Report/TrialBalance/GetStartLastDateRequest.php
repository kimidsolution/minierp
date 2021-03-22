<?php

namespace App\Services\Api\Finance\Report\TrialBalance;

use Illuminate\Http\Request;

class GetStartLastDateRequest
{
    public static function handle(Request $request)
    {
        $openingDate = app('string.helper')->parseStartOrLastDateOfMonth($request->start_period, 'Y-m-d', false);
        $openingDate = \Carbon\Carbon::parse($openingDate);

        $endingDate = app('string.helper')->parseStartOrLastDateOfMonth($request->end_period, 'Y-m-d', false);
        $request->request->add([
            'opening_date' => $openingDate->subDay()->format('Y-m-d'),
            'ending_date' => $endingDate
        ]);
    }
}
