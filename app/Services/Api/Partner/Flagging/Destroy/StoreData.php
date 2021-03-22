<?php

namespace App\Services\Api\Partner\Flagging\Destroy;

use App\Models\Partner;
use App\User;
use Illuminate\Http\Request;

class StoreData
{
    public static function handle(Request $request)
    {
        $data = Partner::find($request->id);
        if (is_null($data)) {
            abort(400, 'Partner not found');
        }

        $user = User::find($request->user_id);
        $request->request->add(['user_id' => $user->id]);
        $data->delete();

        return response()->api(true, [], $data);
    }
}
