<?php

namespace App\Services\Api\Account\StoreAccount;

use Illuminate\Http\Request;

class ValidateRequest
{
    public static function handle(Request $request)
    {
        app()->make('App\Http\Requests\AccountTreeStoreRequest');
    }
}
