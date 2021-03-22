<?php

namespace App\Services\Api\Finance\Report\GeneralLedger;

use App\Models\Transaction;
use App\Models\TransactionDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GetDataGeneralLedger
{
    public static function handle(Request $request)
    {
        $query = TransactionDetail::select(DB::raw("
            transactions.id AS transaction_id,
            transactions.transaction_date,
            transactions.transaction_status,
            transactions.reference_number,
            transactions.description,
            transactions.company_id,
            transactions.model_type,
            transactions.model_id,
            transaction_details.id AS transaction_detail_id,
            accounts.id AS account_id,
            accounts.balance AS account_balance,
            accounts.account_name,
            accounts.account_code,
            IF ((account_text IS NOT NULL OR account_text != ''), account_text, account_name) AS account_naming,
            transaction_details.debit_amount,
            transaction_details.credit_amount
        "))
        ->join('transactions', 'transactions.id', '=', 'transaction_details.transaction_id')
        ->join('accounts', 'accounts.id', '=', 'transaction_details.account_id')
        ->where('transactions.transaction_status', Transaction::STATUS_POSTED)
        ->where('transactions.company_id', $request->company_id)
        ->where('transaction_details.account_id', $request->account_id)
        ->whereBetween('transactions.transaction_date', [
            app('string.helper')->parseDateFormat($request->start_date),
            app('string.helper')->parseDateFormat($request->end_date)
        ])
        ->orderBy('transactions.transaction_date', 'asc')
        ->orderBy('transactions.reference_number', 'asc');

        $data = !is_null($request->pagination) ? $query->paginate($request->pagination) : $query->get();

        return $data;
    }
}
