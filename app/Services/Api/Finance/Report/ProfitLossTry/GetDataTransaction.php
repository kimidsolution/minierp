<?php

namespace App\Services\Api\Finance\Report\ProfitLossTry;

use App\Models\TransactionDetail;
use Illuminate\Http\Request;

class GetDataTransaction
{
    public static function handle(Request $request)
    {
        $transaction_details = TransactionDetail::with(['account'])
            ->whereHas('transaction', function($param) use ($request) {
                $param->where('transactions.company_id', $request->company_id)
                    ->whereBetween('transactions.transaction_date', [
                        app('string.helper')->parseStartOrLastDateOfMonth($request->start_period, 'Y-m-d', false),
                        app('string.helper')->parseStartOrLastDateOfMonth($request->end_period, 'Y-m-d', true)
                    ]);
            })
        ->get();

        $request->request->add(['data_transaction' => $transaction_details->toArray()]);
    }
}
