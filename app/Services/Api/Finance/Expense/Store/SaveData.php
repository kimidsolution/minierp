<?php

namespace App\Services\Api\Finance\Expense\Store;

use App\Models\Expense;
use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use Illuminate\Support\Facades\DB;

class SaveData
{
    public static function handle(Request $request)
    {
        DB::beginTransaction();

        try {
            // save data expense
            if (!is_null($request->expense_id)) {
                $expense= $request->data_create_expense;
                $expense->save();
            } else {
                $expense = Expense::create($request->data_create_expense);
            }

            // save transaction
            $dataTransaction = $request->data_transaction;
            if (!is_null($request->expense_id)) {
                $transaction = $dataTransaction;
                $transaction->save();
                TransactionDetail::where('transaction_id', $transaction->id)->delete();
            } else {
                $dataTransaction['model_id'] = $expense->id;
                $transaction = Transaction::create($dataTransaction);
            }

            // save transaction detail
            $dataTransactionDetail = $request->data_transaction_detail;
            foreach ($dataTransactionDetail as $key => $value) $dataTransactionDetail[$key]['transaction_id'] = $transaction->id;
            foreach ($dataTransactionDetail as $key => $value) TransactionDetail::create($value);

            DB::commit();
            $response = [
                'data' => $transaction,
                'url' => $request['data_transaction_link'],
                'message' => $request['data_message_response']
            ];
            return response()->api(true, [], $response);
        } catch (\Exception $e) {
            DB::rollback();
            abort(400, $e->getMessage(), $dataTransactionDetail);
        }
    }
}
