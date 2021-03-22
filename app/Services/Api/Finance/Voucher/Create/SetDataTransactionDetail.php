<?php

namespace App\Services\Api\Finance\Voucher\Create;

use App\Models\Account;
use Illuminate\Http\Request;

class SetDataTransactionDetail
{
    public static function handle(Request $request)
    {
        $dataTransactionDetail = [];
        $userCompany = $request->user_company;
        $dataReformatted = $request->data_reformatted;
        $dataSaveVoucherInvoice = $request->data_save_voucher_invoice;

        foreach ($dataReformatted as $key => $value) {
            if ('sales' == $request->type) {

                $dataTransactionDetail[$key][] = [
                    'date' => $request->date,
                    'debit_amount' => $value['final_amount'],
                    'credit_amount' => 0,
                    'account_id' => (int) $request->asset_account_id_used
                ];

                $dataTransactionDetail[$key][] = [
                    'date' => $request->date,
                    'debit_amount' => 0,
                    'credit_amount' => $value['amount'],
                    'account_id' => (int) Account::where('name', 'piutang usaha penjualan')->where('company_id', $userCompany->company_id)->first()->id
                ];

                $additionalExpenseAccounts = $dataReformatted[$key]['additional_accounts'];
                if (count($additionalExpenseAccounts) > 0) {
                    foreach ($additionalExpenseAccounts as $keyAea => $valueAea) {
                        $dataTransactionDetail[$key][] = [
                            'date' => $request->date,
                            'debit_amount' => $valueAea,
                            'credit_amount' => 0,
                            'account_id' => (int) $keyAea
                        ];
                    }
                }

                if ('overpayment' == $dataSaveVoucherInvoice[$key]['status_paid']) {
                    $configOverPaymentVoucher = config('sempoa.voucher.account_used.sales.over_payment'); 

                    $dataTransactionDetail[$key][] = [
                        'date' => $request->date,
                        'debit_amount' => 0,
                        'credit_amount' => ($value['total_nominal_user_pay'] - $value['total_nominal_remaining_pay']),
                        'account_id' => (int) Account::where('name', $configOverPaymentVoucher['name'])->where('company_id', $userCompany->company_id)->first()->id
                    ];

                    $dataTransactionDetail[$key][] = [
                        'date' => $request->date,
                        'debit_amount' => ($value['total_nominal_user_pay'] - $value['total_nominal_remaining_pay']),
                        'credit_amount' => 0,
                        'account_id' => (int) $request->asset_account_id_used
                    ];
                }

            } else {
                
            }
        }

        $request->request->add([
            'data_save_transaction_detail' => $dataTransactionDetail
        ]);
    }
}
