<?php

namespace App\Services\Api\Company\ListsPartner;

use Illuminate\Http\Request;

class ValidateRequest
{
    public static function handle(Request $request)
    {
        app()->make('App\Http\Requests\ListPartnerByCompanyRequest');
    }
}
