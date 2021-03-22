<?php

namespace App\Services\Api\Admin\User\Create;

use DB;
use App\User;
use Illuminate\Http\Request;

class SaveData
{
    public static function handle(Request $request)
    {
        DB::beginTransaction();

        try {
            //? find user have access to create
            $user_data = User::find($request->user_id);

            //? create user
            $user = new User;
            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = bcrypt($request->password);
            $user->company_id = $request->company_id;
            $user->phone_number = $request->phone_number;
            $user->status = User::STATUS_NEW;
            $user->created_by = $user_data->name;
            $user->save();

            DB::commit();

            return response()->api(true, [], $user);
        } catch (\Exception $e) {
            DB::rollback();
            abort(400, $e->getMessage());
        }
    }
}
