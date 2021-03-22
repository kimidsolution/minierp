<?php

namespace App\Services\Api\Admin\User\ListByCompany;

use Illuminate\Http\Request;

class ValidateRequest
{
    public static function handle(Request $request)
    {
        app()->make('App\Http\Requests\ListUserByCompanyRequest');
    }
}
