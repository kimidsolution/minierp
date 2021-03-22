<?php

namespace App\Services\Api\Finance\Report\TrialBalance;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;

class GetTransaction
{
    public static function handle(Request $request)
    {
        $companyId = $request->company_id;
        $year = Carbon::parse($request->start_period)->format('Y');

        $transactions = \App\Models\TransactionDetail::with(['transaction'])->whereHas('transaction.company', function (Builder $query) use ($companyId) {
                            $query->where('company_id', $companyId);
                        })
                        ->whereHas('transaction', function (Builder $query) use ($year) {
                            $query->whereYear('transaction_date', $year);
                        })
                        ->get();

        $request->request->add([
            'list_transactions' => $transactions
        ]);
    }
}
