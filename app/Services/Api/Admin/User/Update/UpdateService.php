<?php

namespace App\Services\Api\Admin\User\Update;

use App\User;
use Illuminate\Http\Request;

class UpdateService
{
    public function handle(Request $request)
    {
        try {

            app()->make('App\Http\Requests\UpdateUserStatusRequest');
            $user = User::find($request->user_id);
            $user->status = $request->status_id;
            $user->updated_by = User::find($request->updated_by)->name;
            $user->save();

            return 'ok';
        } catch (\Exception $e) {
            return response()->api(false, [], [], $e->getMessage(), 400);
        }
    }
}