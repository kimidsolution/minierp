<?php

namespace App\Services\Api\Configuration\Finance\Flagging\Destroy;

use App\Models\FinanceConfiguration;
use App\Models\User;
use Illuminate\Http\Request;

class StoreData
{
    public static function handle(Request $request)
    {
        $data = FinanceConfiguration::find($request->id);
        if (is_null($data)) abort(400, 'Configuration not found');

        $user = User::find($request->user_id);
        $request->request->add(['user_id' => $user->id]);
        $data->delete();

        return response()->api(true, [], $data);
    }
}
