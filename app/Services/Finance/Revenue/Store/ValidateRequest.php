<?php

namespace App\Services\Finance\Revenue\Store;

use Illuminate\Http\Request;

class ValidateRequest
{
    public static function handle(Request $request)
    {
        app()->make('App\Http\Requests\RevenueStoreRequest');
    }
}
