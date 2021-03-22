<?php

namespace App\Services\Api\Account\StoreAccount;

use App\Models\Account;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class StoreData
{
    public static function handle(Request $request)
    {
        DB::beginTransaction();
        try {
            $user = User::find($request->user_id);
            if ($request->account_id) {
                $account = Account::find($request->account_id);
                $account->updated_by = $user->id;
            } else {
                $account = new Account();
                $account_number = preg_replace('/\s+/', '', $request->account_code);
                $account_number_company = Account::where('company_id', $user->company_id)->where('number', $account_number)->get();
                if (count($account_number_company) > 0) {
                    return response()->json([
                        'status' => false,
                        'message' => 'Account code has been registered',
                    ]);
                }
                $account->uuid = (string) Str::uuid();
                $account->level = $request->account_level;
                $account->account_type_id = $request->account_type_id;
                $account->created_by = $user->id;
                $account->company_id = $user->company_id;
            }
            $account->name = $request->account_name;
            $account->number = $request->account_code;
            $account->parent_account_id = $request->account_parent_id;
            $account->description = $request->account_description;
            $account->save();
            DB::commit();
            return response()->api(true, [], $account);
        } catch (\Exception $e) {
            DB::rollback();
            abort(400, $e->getMessage());
        }
    }
}
