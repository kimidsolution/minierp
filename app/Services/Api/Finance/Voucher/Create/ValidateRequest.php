<?php

namespace App\Services\Api\Finance\Voucher\Create;

use Illuminate\Http\Request;

class ValidateRequest
{
    public static function handle(Request $request)
    {
        app()->make('App\Http\Requests\StoreVoucherSalesRequest');
    }
}
