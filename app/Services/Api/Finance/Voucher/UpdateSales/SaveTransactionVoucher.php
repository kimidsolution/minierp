<?php

namespace App\Services\Api\Finance\Voucher\UpdateSales;

use DB;
use App\Models\Voucher;
use Illuminate\Http\Request;
use App\Models\TransactionTemp;
use App\Models\TransactionDetailTemp;

class SaveTransactionVoucher
{
    public static function handle(Request $request)
    {
        DB::beginTransaction();

        try {

            $userCompany = $request->user_company;

            // save to vouchers table
            Voucher::where('reference_number', $request->reference_number)->update($request->data_save_voucher);
            $voucher = Voucher::where('reference_number', $request->reference_number)->first();


            // delete transaction
            $transactionBefore = TransactionTemp::where('company_id', $userCompany->company_id)->where('model_id', $voucher->id)->where('model_type', '\App\Models\Voucher')->first();
            TransactionTemp::where('company_id', $userCompany->company_id)->where('model_id', $voucher->id)->where('model_type', '\App\Models\Voucher')->forceDelete();
            TransactionDetailTemp::where('transaction_temp_id', $transactionBefore->id)->delete();


            // save to transactions table
            $dataSaveTransaction = $request->data_save_transaction;
            $dataSaveTransaction['model_id'] = $voucher->id;
            $transaction = \App\Models\TransactionTemp::create($dataSaveTransaction);


            // save to transaction_details table
            $dataSaveTransactionDetail = $request->data_save_transaction_detail;
            foreach ($dataSaveTransactionDetail as $key => $value) {
                $dataSaveTransactionDetail[$key]['transaction_temp_id'] = $transaction->id;
            }
            foreach ($dataSaveTransactionDetail as $key => $value) {
                \App\Models\TransactionDetailTemp::create($value);
            }

            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollback();
            abort(400, $e->getMessage());
        }
    }
}
