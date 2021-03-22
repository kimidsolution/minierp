<?php

namespace App\Services\Api\Finance\Report\TrialBalance;

use Illuminate\Http\Request;

class ValidateRequest
{
    public static function handle(Request $request)
    {
        app()->make('App\Http\Requests\ReportFilterTrialBalanceRequest');
    }
}
