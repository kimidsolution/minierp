<?php

namespace App\Services\Api\Finance\Transaction\Flagging\Destroy;

use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;

class StoreData
{
    public static function handle(Request $request)
    {
        $data = Transaction::find($request->id);
        if (is_null($data)) {
            abort(400, 'Transaction not found');
        }

        $user = User::find($request->user_id);
        $request->request->add(['user_id' => $user->id]);
        $data->delete();

        return response()->api(true, [], $data);
    }
}
