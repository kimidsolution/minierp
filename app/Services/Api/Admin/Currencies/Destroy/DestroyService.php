<?php

namespace App\Services\Api\Admin\Currencies\Destroy;

use App\Models\Currency;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DestroyService
{
    public function handle(Request $request)
    {
        DB::beginTransaction();
        try {
            app()->make('App\Http\Requests\FlaggingRequest');
            $data = Currency::find($request->id);
            if (is_null($data)) {
                abort(400, 'Currency  which will delete not found');
            }

            $user = User::find($request->user_id);
            $request->request->add(['user_id' => $user->id]);
            $data->delete();

            DB::commit();
            return response()->api(true, [], $data);
        } catch (\Exception $e) {
            DB::rollback();
            abort(400, $e->getMessage());
        }
    }
}
