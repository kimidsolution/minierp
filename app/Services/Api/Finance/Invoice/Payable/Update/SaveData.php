<?php

namespace App\Services\Api\Finance\Invoice\Payable\Update;

use DB;
use App\Models\Invoice;
use App\Models\InvoiceTax;
use App\Models\Transaction;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\InvoiceDetail;
use App\Models\TransactionDetail;

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
            $oldTransaction = Transaction::where('model_id', $request->invoice_id)->first();

            // save invoice
            Invoice::where('invoice_number', $request->invoice_number)->update($dataInvoice);
            
            // delete old invoice detail & save new invoice detail
            InvoiceDetail::where('invoice_id', $request->invoice_id)->delete();
            foreach ($dataInvoiceDetail as $key => $value) {
                $dataInvoiceDetail[$key]['id'] = (string) Str::uuid();
                $dataInvoiceDetail[$key]['invoice_id'] = $request->invoice_id;
            }
            InvoiceDetail::insert($dataInvoiceDetail);
            
            // delete old invoice taxes & save new invoice taxes
            InvoiceTax::where('invoice_id', $request->invoice_id)->delete();
            foreach ($dataInvoiceTax as $key => $value) {
                $dataInvoiceTax[$key]['id'] = (string) Str::uuid();
                $dataInvoiceTax[$key]['invoice_id'] = $request->invoice_id;
            }
            InvoiceTax::insert($dataInvoiceTax);

            // update data transaction
            Transaction::where('id', $oldTransaction->id)->update($dataTransaction);
            
            // save data transaction detail
            foreach ($dataTransactionDetail as $key => $value) {
                $dataTransactionDetail[$key]['id'] = (string) Str::uuid();
                $dataTransactionDetail[$key]['transaction_id'] = $oldTransaction->id;
            }

            TransactionDetail::where('transaction_id', $oldTransaction->id)->delete();
            TransactionDetail::insert($dataTransactionDetail);
            
            DB::commit();

        } catch (\Exception $e) {
            DB::rollback();
            abort(400, $e->getMessage());
        }
    }
}
