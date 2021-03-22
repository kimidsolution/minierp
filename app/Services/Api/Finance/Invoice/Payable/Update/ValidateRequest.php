<?php

namespace App\Services\Api\Finance\Invoice\Payable\Update;

use Illuminate\Http\Request;

class ValidateRequest
{
    public static function handle(Request $request)
    {
        app()->make('App\Http\Requests\InvoicePayableUpdateRequest');
    }
}
