<?php

namespace App\Services\Api\Finance\Expense\Store;

use App\Models\Transaction;
use Illuminate\Http\Request;

class SetDataCreateTransaction
{
    public static function handle(Request $request)
    {
        if (!is_null($request->transaction_id) && !is_null($request->expense_id)) {
            $dataTransaction = Self::handleSetUpdateEntityTransaction($request);
        } else {
            $dataTransaction = [
                'transaction_date' => date('Y-m-d', strtotime($request->expense_date)),
                'model_id' => null,
                'model_type' => Transaction::MODEL_TYPE_EXPENSE_DEC,
                'transaction_type' => Transaction::TYPE_PAYABLE,
                'transaction_status' => $request->is_posted == '0' ? Transaction::STATUS_DRAFT : Transaction::STATUS_POSTED,
                'reference_number' => $request->expense_number,
                'description' => $request->description,
                'company_id' => $request->company_id,
            ];
        }

        $request->request->add(['data_transaction' => $dataTransaction]);
    }

    protected static function handleSetUpdateEntityTransaction($request)
    {
        $entity = Transaction::find($request->transaction_id);
        if (is_null($entity)) {
            return abort(400, 'Transaction not found');
        }
        $entity->transaction_date = date('Y-m-d', strtotime($request->expense_date));
        $entity->model_id = $request->expense_id;
        $entity->model_type = Transaction::MODEL_TYPE_EXPENSE_DEC;
        $entity->transaction_type =  Transaction::TYPE_PAYABLE;
        $entity->transaction_status = $request->is_posted == '0' ? Transaction::STATUS_DRAFT : Transaction::STATUS_POSTED;
        $entity->reference_number = $request->expense_number;
        $entity->description = $request->description;
        $entity->company_id = $request->company_id;

        return $entity;
    }
}
