<?php

namespace App\Services\Finance\Revenue\Store;

use DB;
use App\Models\Revenue;
use Illuminate\Http\Request;
use App\Models\RevenueDetail;
use App\Models\TransactionTemp;
use App\Models\TransactionDetailTemp;

class SaveData
{
    public static function handle(Request $request)
    {
        DB::beginTransaction();

        try {

            // save data revenue
            $revenue = Revenue::create($request->data_create_revenue);


            //save data revenue detail
            $revenueDetail = $request->data_create_revenue_detail;
            
            foreach ($revenueDetail as $key => $value) {
                $revenueDetail[$key]['revenue_id'] = $revenue->id;
            }

            foreach ($revenueDetail as $key => $value) {
                RevenueDetail::create($value);
            }


            // save transaction
            $dataTransaction = $request->data_transaction;
            $dataTransaction['model_id'] = $revenue->id;
            $transaction = TransactionTemp::create($dataTransaction);


            // save transaction detail
            $dataTransactionDetail = $request->data_transaction_detail;

            foreach ($dataTransactionDetail as $key => $value) {
                $dataTransactionDetail[$key]['transaction_temp_id'] = $transaction->id;
            }

            foreach ($dataTransactionDetail as $key => $value) {
                TransactionDetailTemp::create($value);
            }

            DB::commit();
            return true;

        } catch (\Exception $e) {
            DB::rollback();
            abort(400, $e->getMessage(), $dataTransactionDetail);
        }
    }
}
