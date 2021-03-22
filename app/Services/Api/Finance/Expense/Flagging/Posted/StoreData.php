<?php

namespace App\Services\Api\Finance\Expense\Flagging\Posted;

use App\Models\Expense;
use App\Models\Transaction;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StoreData
{
    public static function handle(Request $request)
    {
        $data_expense = Expense::find($request->id);
        if (is_null($data_expense)) abort(400, 'Expense not found');

        $data_transaction = Transaction::where('model_id', $request->id)->first();
        if (is_null($data_transaction)) abort(400, 'Transaction not found');

        $user = User::find($request->user_id);
        if (is_null($user)) abort(400, 'User not found');

        $request->request->add(['user_id' => $user->id]);

        DB::beginTransaction();
        try {
            $data_expense->is_posted = Expense::STATUS_POSTED;
            $data_transaction->transaction_status = Transaction::STATUS_POSTED;

            if ($data_expense->save()) $data_transaction->save();

            DB::commit();
            return response()->api(true, [], $data_expense);
        } catch (Exception $e) {
            DB::rollback();
            abort(400, $e->getMessage());
        }
    }
}
