<?php

namespace App\Services\Api\Finance\Voucher\Update;

use App\Models\FinanceConfiguration;
use App\Models\Invoice;
use App\Models\Voucher;
use Illuminate\Http\Request;

class SetDataTransactionDetail
{
    public static function handle(Request $request)
    {
        $dataTransactionDetail = [];
        $userCompany = $request->user_company;
        $dataReformatted = $request->data;
        $dataSaveVoucherInvoice = $request->data_save_voucher_invoice;

        foreach ($dataReformatted as $key => $value) {
            if (Voucher::TYPE_RECEIVABLE == $request->type) {

                $dataTransactionDetail[$key][] = [
                    'debit_amount' => $value['final_amount'],
                    'credit_amount' => 0,
                    'account_id' => $request->asset_account_id_used
                ];

                $finance_config_arsales = app('data.helper')->getFinanceConfigurationActiveBySpec(
                    $userCompany['company_id'], FinanceConfiguration::CODE_ACCOUNT_AR_SALES
                );
                $account_arsales = app('data.helper')->getFinanceConfigurationDetailAccount($finance_config_arsales);

                if (!is_null($account_arsales)) {
                    $dataTransactionDetail[$key][] = [
                        'debit_amount' => 0,
                        'credit_amount' => $value['amount'],
                        'account_id' => $account_arsales->id
                    ];
                }

                $additionalExpenseAccounts = $value['additional_accounts'];
                if (count($additionalExpenseAccounts) > 0) {
                    foreach ($additionalExpenseAccounts as $keyAe => $valueAe) {
                        if ($valueAe['account_id'] !== null) {
                            $dataTransactionDetail[$key][] = [
                                'debit_amount' => $valueAe['nominal'],
                                'credit_amount' => 0,
                                'account_id' => $valueAe['account_id']
                            ];
                        }
                    }
                }

                if (Invoice::STATUS_OVERPAYMENT == $dataSaveVoucherInvoice[$key]['payment_status']) {
                    $finance_config_overpayment = app('data.helper')->getFinanceConfigurationActiveBySpec(
                        $userCompany['company_id'], FinanceConfiguration::CODE_ACCOUNT_REVENUE_RECEIVED_IN_ADVANCED
                    );
                    $account_overpayment = app('data.helper')->getFinanceConfigurationDetailAccount($finance_config_overpayment);

                    if (!is_null($account_overpayment)) {
                        $dataTransactionDetail[$key][] = [
                            'debit_amount' => 0,
                            'credit_amount' => ($value['total_nominal_user_pay'] - $value['total_nominal_remaining_pay']),
                            'account_id' => $account_overpayment->id
                        ];
                    }

                    $dataTransactionDetail[$key][] = [
                        'debit_amount' => ($value['total_nominal_user_pay'] - $value['total_nominal_remaining_pay']),
                        'credit_amount' => 0,
                        'account_id' => $request->asset_account_id_used
                    ];
                }

            } else {

                $dataTransactionDetail[$key][] = [
                    'debit_amount' => 0,
                    'credit_amount' => $value['final_amount'],
                    'account_id' => $request->asset_account_id_used
                ];

                $finance_config_tradepayable = app('data.helper')->getFinanceConfigurationActiveBySpec(
                    $userCompany['company_id'], FinanceConfiguration::CODE_ACCOUNT_TRADE_PAYABLE
                );
                $account_tradepayable = app('data.helper')->getFinanceConfigurationDetailAccount($finance_config_tradepayable);

                if (!is_null($account_tradepayable)) {
                    $dataTransactionDetail[$key][] = [
                        'debit_amount' => $value['amount'],
                        'credit_amount' => 0,
                        'account_id' => $account_tradepayable->id
                    ];
                }

                $additionalExpenseAccounts = $value['additional_accounts'];
                if (count($additionalExpenseAccounts) > 0) {
                    foreach ($additionalExpenseAccounts as $keyAe => $valueAe) {
                        if ($valueAe['nominal'] > 0 && $valueAe['account_id'] !== null) {
                            $dataTransactionDetail[$key][] = [
                                'debit_amount' => 0,
                                'credit_amount' => $valueAe['nominal'],
                                'account_id' => $valueAe['account_id']
                            ];
                        }
                    }
                }

                if (Invoice::STATUS_OVERPAYMENT == $dataSaveVoucherInvoice[$key]['payment_status']) {
                    $finance_config_overpayment = app('data.helper')->getFinanceConfigurationActiveBySpec(
                        $userCompany['company_id'], FinanceConfiguration::CODE_ACCOUNT_RECEIVABLE
                    );
                    $account_overpayment = app('data.helper')->getFinanceConfigurationDetailAccount($finance_config_overpayment);

                    if (!is_null($account_overpayment)) {
                        $dataTransactionDetail[$key][] = [
                            'debit_amount' => ($value['total_nominal_user_pay'] - $value['total_nominal_remaining_pay']),
                            'credit_amount' => 0,
                            'account_id' => $account_overpayment->id
                        ];
                    }
                }
            }
        }

        $request->request->add([
            'data_save_transaction_detail' => $dataTransactionDetail
        ]);
    }
}
