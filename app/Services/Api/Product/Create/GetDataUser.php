<?php

namespace App\Services\Api\Product\Create;

use App\User;
use App\Models\Company;
use Illuminate\Http\Request;

class GetDataUser
{
    public static function handle(Request $request)
    {
        $user = User::with(['company'])->where('id', $request->user_id)->first();

        if (is_null($user))
            abort('Data user not found');

        $request->request->add([
            'data_user' => $user->toArray()
        ]);
    }
}
