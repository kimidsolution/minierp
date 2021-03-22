<?php

namespace App\Services\Api\Finance\Voucher\Update;

use DB;
use App\Models\Voucher;
use App\Models\Transaction;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\VoucherDetail;
use App\Models\TransactionDetail;
use App\Models\VoucherDetailExpense;


class SaveTransactionVoucher
{
    public static function handle(Request $request)
    {
        $userCompany = $request->user_company;
        $dataUpdateVoucher = $request->data_save_voucher;
        $dataUpdateVoucherDetail = $request->data_save_voucher_invoice;
        $dataSaveTransactionDetail = $request->data_save_transaction_detail;
        $dataSaveVoucherDetailExpense = $request->data_save_voucher_invoice_expense;

        // update voucher
        Voucher::where('id', $request->voucher_id)->update($dataUpdateVoucher);


        // save voucher detail
        foreach ($dataUpdateVoucherDetail as $key => $value) {
            $voucherDetail = VoucherDetail::create([
                'voucher_id' => $request->voucher_id,
                'invoice_id' => $value['invoice_id'],
                'amount' => $value['amount'],
                'payment_status' => $value['payment_status']
            ]);

            if (count($dataSaveVoucherDetailExpense) > 0) {
                if (array_key_exists($key, $dataSaveVoucherDetailExpense)) {
                    $dataDetailExpense = $dataSaveVoucherDetailExpense[$key];
                    foreach ($dataDetailExpense as $keyExpense => $valueExpense) {
                        $dataSaveVoucherDetailExpense[$key][$keyExpense]['voucher_detail_id']= $voucherDetail->id;
                    }
                }
            }
        }


        // update transaction
        Transaction::where('model_id', $request->voucher_id)->update($request->data_save_transaction);
        $transaction = Transaction::where('model_id', $request->voucher_id)->first();


        // save transaction detail
        foreach ($dataSaveTransactionDetail as $key => $value) {
            foreach ($value as $keyV => $valueV) {
                $dataSaveTransactionDetail[$key][$keyV]['transaction_id'] = $transaction->id;
            }
        }

        foreach ($dataSaveTransactionDetail as $key => $value) {
            foreach ($value as $keyV => $valueV) {
                TransactionDetail::create($valueV);
            }
        }


        // save transaction detail expense
        if (count($dataSaveVoucherDetailExpense) > 0) {
            foreach ($dataSaveVoucherDetailExpense as $key => $value) {
                foreach ($value as $keyV => $valueV) {
                    VoucherDetailExpense::create($valueV);
                }
            }
        }

        return true;
    }
}
