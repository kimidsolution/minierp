<?php

namespace App\Services\Api\ProductCategory\ListsByCompany;

use Illuminate\Http\Request;

class ValidateRequest
{
    public static function handle(Request $request)
    {
        app()->make('App\Http\Requests\ListProductCategoryByCompanyRequest');
    }
}
