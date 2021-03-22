<?php

namespace App\Services\Api\Company\UpdateStatus;

use Illuminate\Http\Request;

class ValidateRequest
{
    public static function handle(Request $request)
    {
        app()->make('App\Http\Requests\CompanyUpdateStatusRequest');
    }
}
