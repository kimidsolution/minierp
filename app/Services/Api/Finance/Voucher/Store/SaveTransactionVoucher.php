<?php

namespace App\Services\Api\Finance\Voucher\Store;

use DB;
use App\Models\Invoice;
use App\Models\Voucher;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class SaveTransactionVoucher
{
    public static function handle(Request $request)
    {
        $userCompany = $request->user_company;
        $dataSaveVoucherInvoiceExpense = $request->data_save_voucher_invoice_expense;

        DB::beginTransaction();

        try {

            // save to vouchers table
            $voucher = \App\Models\Voucher::create($request->data_save_voucher);

            // save to transactions table
            $dataSaveTransaction = $request->data_save_transaction;
            $dataSaveTransaction['model_id'] = $voucher->id;

            $transaction = \App\Models\Transaction::create($dataSaveTransaction);

            // save to voucher_invoices table
            $saveVoucherInvoice = $request->data_save_voucher_invoice;

            foreach ($saveVoucherInvoice as $key => $value) {
                $saveVoucherInvoice[$key]['voucher_id'] = $voucher->id;
            }

            foreach ($saveVoucherInvoice as $key => $value) {

                $voucherInvoice = \App\Models\VoucherDetail::create($value);
                
                if (count($dataSaveVoucherInvoiceExpense) > 0) {
                    $dataVie = $dataSaveVoucherInvoiceExpense[$key];

                    foreach ($dataVie as $keyVie => $valueVie) {
                        $dataSaveVoucherInvoiceExpense[$key][$keyVie]['voucher_detail_id'] = $voucherInvoice->id;
                    }
                }

                // if not posted, not change payment status invoice
                if ($request->is_posted == Voucher::POSTED_YES) {
                    // update invoice
                    Invoice::where('id', $value['invoice_id'])
                    ->update([
                        'payment_status' => $value['payment_status'],
                        'updated_by' => $userCompany->name,
                        'updated_at' => now()
                    ]);
                }
            }

            // save to voucher_invoice_expenses table
            if (count($dataSaveVoucherInvoiceExpense) > 0) {
                foreach ($dataSaveVoucherInvoiceExpense as $keySvie => $valueSvie) {
                    foreach ($valueSvie as $keyVSvie => $valueVSvie) {
                        \App\Models\VoucherDetailExpense::create($valueVSvie);
                    }
                }
            }

            // save to transaction_details table
            $dataSaveTransactionDetail = $request->data_save_transaction_detail;

            foreach ($dataSaveTransactionDetail as $key => $value) {
                foreach ($value as $keyV => $valueV) {
                    $dataSaveTransactionDetail[$key][$keyV]['transaction_id'] = $transaction->id;
                }
            }

            foreach ($dataSaveTransactionDetail as $key => $value) {
                foreach ($value as $keyV => $valueV) {
                    \App\Models\TransactionDetail::create($valueV);
                }
            }

            $request->request->add([
                'voucher_saved' => $voucher
            ]);

            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollback();
            abort(400, $e->getMessage());
        }
    }
}
