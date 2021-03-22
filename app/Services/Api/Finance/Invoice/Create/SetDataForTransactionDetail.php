<?php

namespace App\Services\Api\Finance\Invoice\Create;

use App\Models\Account;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;

class SetDataForTransactionDetail
{
    public static function handle(Request $request)
    {
        $itemsTransactionInvoice = [];
        $transactionDetails = [];
        $dataCompany = $request->data_company;
        $dataInvoice = $request->data_invoice;

        if ('sales' == $request->type) {
            $invoiceAccountUsed = config('sempoa.invoice.account_used.sales');
        } else {
            $invoiceAccountUsed = config('sempoa.invoice.account_used.purchases');
        }

        // discount
        $itemsTransactionInvoice['discount'] = $request->nominal_discount;
            
        //  tax
        $itemsTransactionInvoice['tax'] = $request->nominal_tax;

        // remaining
        $itemsTransactionInvoice['remaining'] = $request->nominal_remaining;

        // amount
        $itemsTransactionInvoice['amount'] = $dataInvoice['amount'];


        // transform for transaction detail
        if ('sales' == $request->type) {

            foreach ($invoiceAccountUsed as $key => $value) {
                if (array_key_exists($key, $itemsTransactionInvoice)) {
                    if ('debit' == $value['balance']) {
                        $invoiceAccountUsed[$key]['debit_amount'] = $itemsTransactionInvoice[$key];
                    } else {
                        $invoiceAccountUsed[$key]['credit_amount'] = $itemsTransactionInvoice[$key];
                    }
                }
            }

            // handle down payment
            if ($request->nominal_down_payment > 0) {

                $assetAccount = Account::find($request->asset_account_id);
                $invoiceAccountUsed['down_payment'] = [
                    'name' => $assetAccount->name,
                    'debit_amount' => $request->nominal_down_payment,
                    'credit_amount' => 0,
                    'balance' => 'debit'
                ];
            }

        } else {
            foreach ($invoiceAccountUsed as $key => $value) {
                if (array_key_exists($key, $itemsTransactionInvoice)) {
                    if ('debit' == $value['balance']) {
                        $invoiceAccountUsed[$key]['debit_amount'] = $itemsTransactionInvoice[$key];
                    } else {
                        $invoiceAccountUsed[$key]['credit_amount'] = $itemsTransactionInvoice[$key];
                    }
                }
            }

            // handle down payment
            if ($request->nominal_down_payment > 0) {
                $assetAccount = Account::find($request->asset_account_id);
                $invoiceAccountUsed['down_payment'] = [
                    'name' => $assetAccount->name,
                    'debit_amount' => 0,
                    'credit_amount' => $request->nominal_down_payment,
                    'balance' => 'credit'
                ];
            }
        }

        
        // handle currency
        $companyCurrency = \App\Models\CompanyCurrency::where('company_id', $dataCompany['company_id'])->first();
        foreach ($invoiceAccountUsed as $key => $value) {
            $invoiceAccountUsed[$key]['value_rate'] = 1;
            $invoiceAccountUsed[$key]['exchange_rate_from'] = $companyCurrency->id;
            $invoiceAccountUsed[$key]['exchange_rate_to'] = $companyCurrency->id;
        }


        // handle account id
        foreach ($invoiceAccountUsed as $key => $value) {
            $account = Account::where('company_id', $dataCompany['company_id'])->where('name', $value['name'])->first();
            $invoiceAccountUsed[$key]['account_id'] = $account->id;
            unset($invoiceAccountUsed[$key]['name']);
        }

        $request->request->add([
            'data_transaction_detail' => $invoiceAccountUsed
        ]);
    }
}