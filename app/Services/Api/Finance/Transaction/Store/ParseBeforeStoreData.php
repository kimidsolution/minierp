<?php

namespace App\Services\Api\Finance\Transaction\Store;

use App\Models\Invoice;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Voucher;
use Illuminate\Http\Request;

class ParseBeforeStoreData
{
    public static function handle(Request $request)
    {
        $user = User::find($request->user_id);
        $transaction_date = date("Y-m-d", strtotime($request->transaction_date));
        $transaction_type = $request->transaction_type == '0' ? Transaction::TYPE_RECEIVABLE : Transaction::TYPE_PAYABLE;
        $transaction_status = $request->transaction_status == '1' ? Transaction::STATUS_DRAFT : Transaction::STATUS_POSTED;

        if (!is_null($request->transaction_id)) {
            $message_response = 'Transaction has been updated';
            $link_redirect = $request->transaction_type == '0' ? route('finance.transactions.receivable.index') : route('finance.transactions.payable.index');
        } else {
            $message_response = 'Transaction has been created';
            if ($transaction_status == Transaction::STATUS_POSTED) {
                $link_redirect = $request->transaction_type == '0' ? route('finance.transactions.receivable.index') : route('finance.transactions.payable.index');
            } else {
                $link_redirect = $request->transaction_type == '0' ? route('finance.transactions.receivable.create') : route('finance.transactions.payable.create');
            }
        }

        switch ($request->model_type) {
            case Transaction::MODEL_TYPE_INVOICE:
                $transaction_model_id = $request->model_id;
                $data_invoice = Invoice::find($transaction_model_id);
                $transaction_model_type = Transaction::MODEL_TYPE_INVOICE_DEC;
                $transaction_refnumber = $data_invoice->invoice_number;
            break;
            case Transaction::MODEL_TYPE_VOUCHER:
                $transaction_model_id = $request->model_id;
                $data_voucher = Voucher::find($transaction_model_id);
                $transaction_model_type = Transaction::MODEL_TYPE_VOUCHER_DEC;
                $transaction_refnumber = $data_voucher->voucher_number;
                break;
            default:
                $transaction_model_id = null;
                $transaction_model_type = null;
                $transaction_refnumber = $request->reference_number;
                break;
        }

        $request->request->add([
            'user_id' => $user->id,
            'data_parse' => [
                'data_company_id' => $request->company_id,
                'data_transaction_modelid' => $transaction_model_id,
                'data_transaction_modeltype' => $transaction_model_type,
                'data_transaction_date' => $transaction_date,
                'data_transaction_type' => $transaction_type,
                'data_transaction_status' => $transaction_status,
                'data_transaction_refnumber' => $transaction_refnumber,
                'data_transaction_description' => $request->description,
                'data_transaction_link' => $link_redirect,
                'data_message_response' => $message_response
            ]
        ]);
    }
}
