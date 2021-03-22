<?php

namespace App\Services\Api\Finance\Invoice\Receivable\Update;

use Carbon\Carbon;
use Illuminate\Http\Request;

class ValidateRangeDate
{
    public static function handle(Request $request)
    {
        $dateCarbon = Carbon::parse($request->invoice_date);
        $dueDateCarbon = Carbon::parse($request->due_date);

        if (false == $dueDateCarbon->greaterThanOrEqualTo($dateCarbon))
            abort(400, 'Due date must be greater than or equal with date');
    }
}
