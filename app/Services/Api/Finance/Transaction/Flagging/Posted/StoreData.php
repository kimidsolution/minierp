<?php

namespace App\Services\Api\Finance\Transaction\Flagging\Posted;

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
        DB::beginTransaction();
        try {
            $data = Transaction::find($request->id);
            if (is_null($data)) abort(400, 'Transaction not found');

            $user = User::find($request->user_id);
            $request->request->add(['user_id' => $user->id]);

            if ($data->model_type == Transaction::MODEL_TYPE_EXPENSE_DEC) {
                $data_expense = Expense::find($data->model_id);
                if (is_null($data_expense)) abort(400, 'Expense not found');

                $data_expense->is_posted = Expense::STATUS_POSTED;
                $data_expense->save();
            }

            $data->transaction_status = Transaction::STATUS_POSTED;
            $data->save();

            DB::commit();
            return response()->api(true, [], $data);
        } catch (Exception $e) {
            DB::rollback();
            abort(400, $e->getMessage());
        }
    }
}
