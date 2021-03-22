<?php

namespace App\Services\Api\Account\Flagging\Destroy;

use App\Models\Account;
use App\Models\AccountBalance;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StoreData
{
    public static function handle(Request $request)
    {
        DB::beginTransaction();
        try {
            app()->make('App\Http\Requests\FlaggingRequest');
            $data = Account::find($request->id);
            $data_balance = AccountBalance::where('account_id', $data->id)->first();
            if (is_null($data) || is_null($data_balance)) {
                abort(400, 'Account which will delete not found');
            }
            $user = User::find($request->user_id);
            $request->request->add(['user_id' => $user->id]);

            $data->delete();
            $data_balance->delete();

            DB::commit();
            return response()->api(true, [], $data);
        } catch (\Exception $e) {
            DB::rollback();
            abort(400, $e->getMessage());
        }
    }
}
