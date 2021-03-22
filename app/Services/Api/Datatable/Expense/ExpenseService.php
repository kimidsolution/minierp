<?php

namespace App\Services\Api\Datatable\Expense;

use App\Models\Expense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class ExpenseService
{
    public function handle(Request $request)
    {
        $get_data = DB::table('expenses')->select([DB::raw('
            expenses.*,
            transactions.id AS transaction_id,
            transactions.model_id,
            transactions.model_type,
            transactions.transaction_status,
            transactions.transaction_status,
            SUM(transaction_details.credit_amount) AS nominal_balance_credit,
            SUM(transaction_details.debit_amount) AS nominal_balance_debit,
            accounts.id AS account_id,
            accounts.account_code,
            accounts.level,
            accounts.balance,
            accounts.account_type,
            CONCAT(
                accounts.account_code, " - ",
                IF ((accounts.account_text IS NOT NULL OR accounts.account_text != ""),
                accounts.account_text, accounts.account_name)
            ) AS account_naming
        ')])
        ->join('transactions', function($join_transaction) {
            $join_transaction->on('expenses.id', '=', 'transactions.model_id');
            $join_transaction->whereRaw('transactions.model_type LIKE "%Expense%"');
        })
        ->join('transaction_details', 'transactions.id', '=', 'transaction_details.transaction_id')
        ->join('accounts', function($join_account) {
            $join_account->on('expenses.payment_account_id', '=', 'accounts.id');
            $join_account->whereNull('accounts.deleted_at');
        });

        if ($request->is_active) {
            if ($request->is_active == "true") {
                $get_data->whereNull('expenses.deleted_at');
            }
        } else {
            $get_data->whereNull('expenses.deleted_at');
        }
        $get_data->where('expenses.company_id', $request->company_id ? $request->company_id : null);
        $expense = $get_data->groupBy('transactions.id')->orderBy('expenses.expense_date', 'desc')->get();

        return DataTables::of($expense)
            ->addColumn('expense_date', function ($expense) {
                return $expense->expense_date;
            })
            ->addColumn('action', function ($expense) {
                return view('datatable.expense.link-action', compact('expense'));
            })
            ->addColumn('amount', function ($expense) {
                return view('datatable.expense.variable', compact('expense'));
            })
            ->addColumn('status_transaction', function ($expense) {
                $value = $expense->is_posted == Expense::STATUS_DRAFT ? 'Draft' : 'Posted';
                $class = $expense->is_posted == Expense::STATUS_DRAFT ? 'danger' : 'success';
                return '<span class="badge badge-soft-'.$class.'">'.$value.'</span>';
            })
            ->addColumn('details_url', function($expense) {
                return url(route('api.datatable.transactions.details.route', ['id' => $expense->transaction_id]));
            })
            ->rawColumns(['action', 'status_transaction'])
            ->make(true);
    }
}
