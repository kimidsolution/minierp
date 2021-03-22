<?php

namespace App\Services\Api\Finance\Transaction\Store;

use App\Models\Transaction;
use App\Models\TransactionDetail;
use Exception;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StoreDataTransaction
{
    public static function handle(Request $request)
    {
        DB::beginTransaction();
        try {
            $data_store = $request->data_parse;
            if (!is_null($request->transaction_id)) {
                $transaction = Self::handleStoreUpdateTransaction($request->transaction_id, $data_store);
                if (!is_null($transaction->id)) TransactionDetail::where('transaction_id', $transaction->id)->delete(); // flush and save
            } else {
                $transaction = Self::handleStoreNewTransaction($data_store);
            }

            $transaction_details = [];
            foreach ($request->account as $key => $account_id) $accounts_id[$key] = $account_id;
            foreach ($request->debit_amount as $key => $debit) $debit_amounts[$key] = preg_replace("/[^0-9-]+/", "", $debit);
            foreach ($request->credit_amount as $key => $credit) $credit_amounts[$key] = preg_replace("/[^0-9-]+/", "", $credit);

            for ($i = 0; $i < count($accounts_id); $i++) {
                $transaction_details[$i]['id'] = (string) Str::uuid();
                $transaction_details[$i]['account_id'] = $accounts_id[$i];
                $transaction_details[$i]['transaction_id'] = $transaction->id;
                $transaction_details[$i]['debit_amount'] = $debit_amounts[$i];
                $transaction_details[$i]['credit_amount'] = $credit_amounts[$i];
            }
            TransactionDetail::insert($transaction_details);

            DB::commit();
            $response = [
                'data' => $transaction,
                'url' => $data_store['data_transaction_link'],
                'message' => $data_store['data_message_response']
            ];
            return response()->api(true, [], $response);
        } catch (Exception $e) {
            DB::rollback();
            abort(400, $e->getMessage());
        }
    }

    protected static function handleStoreNewTransaction($data_store)
    {
        return Transaction::create([
            'transaction_date' => $data_store['data_transaction_date'],
            'model_id' => $data_store['data_transaction_modelid'],
            'model_type' => $data_store['data_transaction_modeltype'],
            'company_id' => $data_store['data_company_id'],
            'transaction_type' => $data_store['data_transaction_type'],
            'transaction_status' => $data_store['data_transaction_status'],
            'reference_number' => $data_store['data_transaction_refnumber'],
            'description' => $data_store['data_transaction_description']
        ]);
    }

    protected static function handleStoreUpdateTransaction($transaction_id, $data_store)
    {
        $transaction = Transaction::find($transaction_id);
        if (is_null($transaction)) {
            return abort(400, 'Transaction not found');
        }
        $transaction->transaction_date = $data_store['data_transaction_date'];
        $transaction->model_id = $data_store['data_transaction_modelid'];
        $transaction->model_type = $data_store['data_transaction_modeltype'];
        $transaction->company_id = $data_store['data_company_id'];
        $transaction->transaction_type = $data_store['data_transaction_type'];
        $transaction->transaction_status = $data_store['data_transaction_status'];
        $transaction->reference_number = $data_store['data_transaction_refnumber'];
        $transaction->description = $data_store['data_transaction_description'];
        $transaction->save();

        return $transaction;
    }
}
