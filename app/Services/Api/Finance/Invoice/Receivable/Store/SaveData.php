<?php

namespace App\Services\Api\Finance\Invoice\Receivable\Store;

use DB;
use App\Models\Invoice;
use App\Models\InvoiceTax;
use App\Models\Transaction;
use Illuminate\Support\Str;
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
            $dataInvoiceTax = $request->data_invoice_taxes;
            $dataInvoiceDetail = $request->data_invoice_detail;
            $dataTransaction = $request->data_transaction;
            $dataTransactionDetail = $request->data_transaction_detail;


            // save invoice
            $invoice = Invoice::create($dataInvoice);

            
            // save data invoice detail
            foreach ($dataInvoiceDetail as $key => $value) {
                $dataInvoiceDetail[$key]['id'] = (string) Str::uuid();
                $dataInvoiceDetail[$key]['invoice_id'] = $invoice->id;
            }
            InvoiceDetail::insert($dataInvoiceDetail);

            
            // save data invoice taxes
            foreach ($dataInvoiceTax as $key => $value) {
                $dataInvoiceTax[$key]['id'] = (string) Str::uuid();
                $dataInvoiceTax[$key]['invoice_id'] = $invoice->id;
            }
            InvoiceTax::insert($dataInvoiceTax);

            // save data transaction
            $dataTransaction['model_id'] = $invoice->id;
            $transaction = Transaction::create($dataTransaction);            

            // save data transaction detail
            foreach ($dataTransactionDetail as $key => $value) {
                $dataTransactionDetail[$key]['id'] = (string) Str::uuid();
                $dataTransactionDetail[$key]['transaction_id'] = $transaction->id;
            }

            TransactionDetail::insert($dataTransactionDetail);

            $request->request->add([
                'invoice_saved' => $invoice
            ]);
            
            DB::commit();

        } catch (\Exception $e) {
            DB::rollback();
            abort(400, $e->getMessage());
        }
    }
}
