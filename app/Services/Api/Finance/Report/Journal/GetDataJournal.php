<?php

namespace App\Services\Api\Finance\Report\Journal;

use App\Models\Invoice;
use App\Models\Transaction;
use Illuminate\Http\Request;

class GetDataJournal
{
    public static function handle(Request $request)
    {
        $query = Transaction::with(['transaction_details']);
        if (!is_null($request->account_id)) {
            $query = Transaction::whereHas('transaction_details', function ($param) use ($request) {
                $param->where('transaction_details.account_id', $request->account_id);
            });
        }

        $query->where('company_id', $request->company_id)
        ->where('transaction_status', Transaction::STATUS_POSTED)
        ->whereBetween('transaction_date', [
            app('string.helper')->parseDateFormat($request->start_date),
            app('string.helper')->parseDateFormat($request->end_date)
        ]);

        $data = $query->orderBy('transaction_date', 'asc')->paginate(20);
        $dataArray = $data->toArray();
        $onlyData = $dataArray['data'];

        // insert account each details & filter spesific account id after where has
        foreach ($onlyData as $keys => $values) {
            $details = $values['transaction_details'];
            if (count($details) > 0) {
                foreach ($details as $keyDetail => $valueDetail) {
                    // insert account each details
                    $accounts = app('data.helper')->getAccountById($valueDetail['account_id'])->toArray();
                    $onlyData[$keys]['transaction_details'][$keyDetail]['account'] = $accounts;

                    // filter spesific account id after where has
                    if (!is_null($request->account_id) && $valueDetail['account_id'] != $request->account_id) {
                        unset($onlyData[$keys]['transaction_details'][$keyDetail]);
                    }
                }
            }
        }

         // modify invoice
        foreach ($onlyData as $key => $value) {
            $transactionDetails = $value['transaction_details'];

            if (count($transactionDetails) > 0) {
                if ('\App\Models\Invoice' == $value['model_type']) {
                    $invoice = Invoice::find($value['model_id']);
                    if (!is_null($invoice)) {
                        if ($invoice->type == Invoice::TYPE_RECEIVABLE) {
                            // check account Sales Discounts
                            $findSalesDiscount = false;
                            $keySalesDiscount = null;

                            foreach ($transactionDetails as $keySd => $valueSd) {
                                if ('Sales Discounts' == $valueSd['account']['account_name']) {
                                    $findSalesDiscount = true;
                                    $keySalesDiscount = $keySd;
                                    break;
                                }
                            }

                            if (true == $findSalesDiscount) {

                                $nominalSalesDiscount = $transactionDetails[$keySalesDiscount]['debit_amount'];

                                // change nominal account good sales
                                $keyGoodSales = null;
                                foreach ($transactionDetails as $keyGs => $valueGs) {
                                    if ('Goods Sales' == $valueGs['account']['account_name']) {
                                        $keyGoodSales = $keyGs;
                                        break;
                                    }
                                }

                                $nominalGoodSales = $transactionDetails[$keyGoodSales]['credit_amount'];
                                $dataArray['data'][$key]['transaction_details'][$keyGoodSales]['credit_amount'] = ($nominalGoodSales - $nominalSalesDiscount);

                                // remove sales discount
                                unset($dataArray['data'][$key]['transaction_details'][$keySalesDiscount]);
                            }
                        } else {
                            // check account Vat In
                            $findVatIn = false;
                            $keyVatIn = null;

                            foreach ($transactionDetails as $keySd => $valueSd) {
                                if ('Vat In' == $valueSd['account']['account_name']) {
                                    $findVatIn = true;
                                    $keyVatIn = $keySd;
                                    break;
                                }
                            }

                            if (true == $findVatIn) {

                                $nominalVatIn = $transactionDetails[$keyVatIn]['debit_amount'];

                                // change nominal Operational Expenses
                                $keySellingExpense = null;
                                foreach ($transactionDetails as $keyGs => $valueGs) {
                                    if ('Operational Expenses' == $valueGs['account']['account_name']) {
                                        $keySellingExpense = $keyGs;
                                        break;
                                    }
                                }

                                $nominalSellingExpense = $transactionDetails[$keySellingExpense]['debit_amount'];
                                $dataArray['data'][$key]['transaction_details'][$keySellingExpense]['debit_amount'] = ($nominalSellingExpense + $nominalVatIn);

                                // remove sales discount
                                unset($dataArray['data'][$key]['transaction_details'][$keyVatIn]);
                            }
                        }
                    }
                }
            }
        }

        $request->request->add([
            'data_general_ledger' => ['data' => $onlyData],
            'links' => $dataArray
        ]);
    }
}
