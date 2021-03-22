<?php

namespace App\Services\Api\Finance\Expense\Store;

use App\Models\Expense;
use Illuminate\Http\Request;

class SetDataCreateExpense
{
    public static function handle(Request $request)
    {
        if (!is_null($request->expense_id)) {
            $dataExpense = Self::handleSetUpdateEntityExpense($request);
            $message_response = 'Expense has been updated';
            $link_redirect = route('finance.expenses.index');
        } else {
            $message_response = 'Expense has been created';
            $link_redirect = $request->is_posted == '1' ? route('finance.expenses.index') : route('finance.expenses.create');
            $dataExpense = [
                'company_id' => $request->company_id,
                'payment_account_id' => $request->payment_account_id,
                'expense_date' => date('Y-m-d', strtotime($request->expense_date)),
                'reference_number' => $request->expense_number,
                'amount' => (float) $request->amount,
                'is_posted' => $request->is_posted,
                'description' => $request->description,
            ];
        }

        $request->request->add([
            'data_create_expense' => $dataExpense,
            'data_transaction_link' => $link_redirect,
            'data_message_response' => $message_response
        ]);
    }

    protected static function handleSetUpdateEntityExpense($request)
    {
        $entity = Expense::find($request->expense_id);
        if (is_null($entity)) {
            return abort(400, 'Expense not found');
        }
        $entity->company_id = $request->company_id;
        $entity->payment_account_id = $request->payment_account_id;
        $entity->expense_date = date('Y-m-d', strtotime($request->expense_date));
        $entity->reference_number = $request->expense_number;
        $entity->amount = (float) $request->amount;
        $entity->is_posted = $request->is_posted;
        $entity->description = $request->description;

        return $entity;
    }
}
