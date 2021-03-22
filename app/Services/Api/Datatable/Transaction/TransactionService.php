<?php

namespace App\Services\Api\Datatable\Transaction;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class TransactionService
{
    public function handle(Request $request)
    {
        $get_data = DB::table('transactions')->select([
            'transactions.id',
            'transactions.transaction_date',
            'transactions.model_id',
            'transactions.model_type',
            'transactions.transaction_type',
            'transactions.transaction_status',
            'transactions.reference_number',
            'transactions.company_id',
            'transactions.description',
            DB::raw('
                SUM(credit_amount) AS nominal_balance_credit,
                SUM(debit_amount) AS nominal_balance_debit
            ')
        ])->join('transaction_details', 'transactions.id', '=', 'transaction_details.transaction_id');

        if ($request->is_active) {
            if ($request->is_active == "true") {
                $get_data->whereNull('transactions.deleted_at');
            }
        } else {
            $get_data->whereNull('transactions.deleted_at');
        }
        if ($request->transaction_type) {
            $transaction_type = $request->transaction_type == 'receivable' ? Transaction::TYPE_RECEIVABLE : Transaction::TYPE_PAYABLE;
            $get_data->where('transactions.transaction_type', $transaction_type);
        }
        $get_data->where('transactions.company_id', $request->company_id ? $request->company_id : null);
        $transaction = $get_data->groupBy('transactions.id')
            ->orderBy('transactions.transaction_date', 'DESC')
            ->get();

        return DataTables::of($transaction)
            ->addColumn('transaction_of', function ($transaction) {
                $class_name = !is_null($transaction->model_type) ? class_basename($transaction->model_type) : '';
                switch ($class_name) {
                    case 'Invoice':
                        $type = $transaction->transaction_type == Transaction::TYPE_RECEIVABLE ? 'Out' : 'In';
                        return 'Invoice'.' '.$type;
                        break;
                    case 'Voucher':
                        $type = $transaction->transaction_type == Transaction::TYPE_RECEIVABLE ? 'In' : 'Out';
                        return 'Cash'.' '.$type;
                        break;
                    default:
                        return !is_null($transaction->description) ? $transaction->description : 'Other';
                        break;
                }
            })
            ->addColumn('action', function ($transaction) {
                return view('datatable.transaction.link-action', compact('transaction'));
            })
            ->addColumn('details_url', function($transaction) {
                return url(route('api.datatable.transactions.details.route', ['id' => $transaction->id]));
            })
            ->addColumn('status_transaction', function ($transaction) {
                $value = $transaction->transaction_status == Transaction::STATUS_DRAFT ? 'Draft' : 'Posted';
                $class = $transaction->transaction_status == Transaction::STATUS_DRAFT ? 'danger' : 'success';
                return '<span class="badge badge-soft-'.$class.'">'.$value.'</span>';
            })
            ->rawColumns(['action', 'status_transaction'])
            ->make(true);
    }
}
