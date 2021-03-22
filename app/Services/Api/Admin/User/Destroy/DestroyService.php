<?php

namespace App\Services\Api\Admin\User\Destroy;

use DB;
use App\Models\User;
use Illuminate\Http\Request;

class DestroyService
{
    public function handle(Request $request)
    {
        DB::beginTransaction();

        try {

            $userWhichWillDelete = User::find($request->id);
            $superAdmin = User::find($request->user_id);

            if (is_null($userWhichWillDelete))
                abort(400, 'User  which will delete not found');

            $userWhichWillDelete->deleted_by = $superAdmin->name;
            $userWhichWillDelete->save();
            // $userWhichWillDelete->delete();
            User::where('id', '=', $request->id)->first()->delete();
            DB::commit();
            // send response
            return response()->api(true, [], $userWhichWillDelete);
        } catch (\Exception $e) {
            DB::rollback();
            abort(400, $e->getMessage());
        }
    }
}