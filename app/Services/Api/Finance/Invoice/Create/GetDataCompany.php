<?php

namespace App\Services\Api\Finance\Invoice\Create;

use App\User;
use Illuminate\Http\Request;

class GetDataCompany
{
    public static function handle(Request $request)
    {
        $user = User::with(['company'])->where('id', $request->user_id)->first();

        if (is_null($user))
            abort('Data user not found');

        $request->request->add([
            'data_company' => $user->toArray()
        ]);
    }
}
