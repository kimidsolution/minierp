<?php

namespace App\Services\Api\Finance\Invoice\Create;

use DB;
use App\Models\Invoice;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Models\InvoiceDetail;
use App\Models\TransactionTemp;
use App\Models\TransactionDetail;
use App\Models\TransactionDetailTemp;

class SaveData
{
    public static function handle(Request $request)
    {
        DB::beginTransaction();

        try {

            $dataInvoice = $request->data_invoice;
            $dataInvoiceDetail = $request->data_invoice_detail;
            $dataTransaction = $request->data_transaction;
            $dataTransactionDetail = $request->data_transaction_detail;

            // save data invoice
            $invoice = Invoice::create($dataInvoice);


            // save data invoice detail
            foreach ($dataInvoiceDetail as $key => $value) {
                InvoiceDetail::create([
                    'qty' => $value['qty'],
                    'basic_price' => $value['basic_price'],
                    'total_price' => $value['total_price'],
                    'product_id' => $value['product_id'],
                    'invoice_id' => $invoice->id
                ]);
            }


            // save data transaction temp
            $dataTransaction['model_id'] = $invoice->id;
            $transaction = TransactionTemp::create($dataTransaction);
            

            // save data transaction detail temp
            foreach ($dataTransactionDetail as $key => $value) {
                TransactionDetailTemp::create([
                    'date' => $request->date,
                    'debit_amount' => $value['debit_amount'],
                    'credit_amount' => $value['credit_amount'],
                    'transaction_temp_id' => $transaction->id,
                    'account_id' => $value['account_id'],
                    'value_rate' => $value['value_rate'],
                    'exchange_rate_from' => $value['exchange_rate_from'],
                    'exchange_rate_to' => $value['exchange_rate_to'],
                    'transaction_id' => $transaction->id
                ]);
            }

            DB::commit();

            //get last ID invoice
            $lastInvoice = Invoice::latest()->where('company_id', $dataInvoice['company_id'])->first();
            $nextIdInvoice = (is_null($lastInvoice)) ? 0 : $lastInvoice->id;
            $return = [
                'status' => 'ok',
                'last_id_invoice' => $nextIdInvoice,
            ];

            return $return;
        } catch (\Exception $e) {
            DB::rollback();
            abort(400, $e->getMessage());
        }
    }
}
