<?php

namespace App\Services\Api\Finance\Voucher\Create;

use DB;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\CompanyCurrency;

class SaveTransactionVoucher
{
    public static function handle(Request $request)
    {
        $userCompany = $request->user_company;
        $companyCurrency = CompanyCurrency::where('company_id', $userCompany->company_id)->first();
        $dataSaveVoucherInvoiceExpense = $request->data_save_voucher_invoice_expense;

        DB::beginTransaction();

        try {

            // save to vouchers table
            $voucher = \App\Models\Voucher::create($request->data_save_voucher);


            // save to transactions table
            $dataSaveTransaction = $request->data_save_transaction;
            $dataSaveTransaction['model_id'] = $voucher->id;
            $dataSaveTransaction['uuid'] = (string) Str::uuid();
            $transaction = \App\Models\TransactionTemp::create($dataSaveTransaction);


            // save to voucher_invoices table
            $saveVoucherInvoice = $request->data_save_voucher_invoice;
            foreach ($saveVoucherInvoice as $key => $value) {
                $saveVoucherInvoice[$key]['voucher_id'] = $voucher->id;
                $saveVoucherInvoice[$key]['transaction_temp_id'] = $transaction->id;
            }

            foreach ($saveVoucherInvoice as $key => $value) {
                $voucherInvoice = \App\Models\VoucherInvoiceTemp::create($value);
                if (array_key_exists($key, $dataSaveVoucherInvoiceExpense)) {
                    $dataVie = $dataSaveVoucherInvoiceExpense[$key]; 
                    foreach ($dataVie as $keyVie => $valueVie) {
                        $dataSaveVoucherInvoiceExpense[$key][$keyVie]['voucher_invoice_temp_id'] = $voucherInvoice->id;
                        $dataSaveVoucherInvoiceExpense[$key][$keyVie]['transaction_temp_id'] = $voucherInvoice->transaction_temp_id;
                    }
                }
            }


            // save to voucher_invoice_expenses table
            foreach ($dataSaveVoucherInvoiceExpense as $keySvie => $valueSvie) {
                foreach ($valueSvie as $keyVSvie => $valueVSvie) {
                    \App\Models\VoucherInvoiceExpenseTemp::create($valueVSvie);
                }
            }


            // save to transaction_details table
            $dataSaveTransactionDetail = $request->data_save_transaction_detail;

            foreach ($dataSaveTransactionDetail as $key => $value) {
                foreach ($value as $keyV => $valueV) {
                    $dataSaveTransactionDetail[$key][$keyV]['transaction_temp_id'] = $transaction->id;
                    $dataSaveTransactionDetail[$key][$keyV]['value_rate'] = 1;
                    $dataSaveTransactionDetail[$key][$keyV]['exchange_rate_from'] = $companyCurrency->id;
                    $dataSaveTransactionDetail[$key][$keyV]['exchange_rate_to'] = $companyCurrency->id;
                }
            }

            foreach ($dataSaveTransactionDetail as $key => $value) {
                foreach ($value as $keyV => $valueV) {
                    \App\Models\TransactionDetailTemp::create($valueV);
                }
            }

            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollback();
            abort(400, $e->getMessage());
        }
    }
}
