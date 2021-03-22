<?php

namespace App\Services\Api\Product\UpdateStatus;

use Illuminate\Http\Request;

class ValidateRequest
{
    public static function handle(Request $request)
    {
        app()->make('App\Http\Requests\ProductUpdateStatusRequest');
    }
}
