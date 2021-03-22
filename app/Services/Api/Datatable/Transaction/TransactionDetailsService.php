<?php

namespace App\Services\Api\Datatable\Transaction;

class TransactionDetailsService
{
    public function handle()
    {
        $transaction_details = app('data.helper')->getTransactionDetailsByTransactionId(request()->id);
        return !is_null($transaction_details) ? $transaction_details->toArray() : [];
    }
}
