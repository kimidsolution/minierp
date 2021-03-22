<?php

namespace App\Services\Finance\Revenue\Store;

use App\User;
use Illuminate\Http\Request;

class GetDataUser
{
    public static function handle(Request $request)
    {
        $user = User::where('id', $request->user_id)->first();

        if (is_null($user))
            abort('Data user not found');

        $request->request->add([
            'user' => $user->toArray()
        ]);
    }
}
