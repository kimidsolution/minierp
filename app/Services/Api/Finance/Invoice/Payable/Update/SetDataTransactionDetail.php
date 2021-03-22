<?php

namespace App\Services\Api\Finance\Invoice\Payable\Update;

use App\Models\FinanceConfiguration;
use Illuminate\Http\Request;

class SetDataTransactionDetail
{
    public static function handle(Request $request)
    {
        $priceProducts = [];
        $invoiceAccountTransaction = [];
        $dataProducts = $request->products;
        $dataCompany = $request->data_company;

        // add good sales
        foreach ($dataProducts as $key => $value) {
            array_push($priceProducts, $value['total']);
        }

        $totalPriceProducts = array_sum($priceProducts);

        $finance_config_operational_expense = app('data.helper')->getFinanceConfigurationActiveBySpec(
            $request->company_id, FinanceConfiguration::CODE_ACCOUNT_OPERATIONAL_EXPENSES
        );
        $accountProcess = app('data.helper')->getFinanceConfigurationDetailAccount($finance_config_operational_expense);
        if (!is_null($accountProcess)) {
            $invoiceAccountTransaction[] = [
                'account_id' => $accountProcess->id,
                'debit_amount' => $totalPriceProducts,
                'credit_amount' => 0
            ];
        }


        // add trade payable
        $finance_config_trade_payable = app('data.helper')->getFinanceConfigurationActiveBySpec(
            $request->company_id, FinanceConfiguration::CODE_ACCOUNT_TRADE_PAYABLE
        );
        $accountProcess = app('data.helper')->getFinanceConfigurationDetailAccount($finance_config_trade_payable);
        if (!is_null($accountProcess)) {
            $invoiceAccountTransaction[] = [
                'account_id' => $accountProcess->id,
                'debit_amount' => 0,
                'credit_amount' => $request->nominal_remaining
            ];
        }


        // handle down payment
        if ($request->nominal_down_payment > 0) {
            $invoiceAccountTransaction[] = [
                'account_id' => $request->account_id_asset,
                'debit_amount' => 0,
                'credit_amount' => $request->nominal_down_payment
            ];
        }


        // handle vat
        if (true == $dataCompany['company']['vat_enabled']) {
            $finance_config_vat_in = app('data.helper')->getFinanceConfigurationActiveBySpec(
                $request->company_id, FinanceConfiguration::CODE_ACCOUNT_VAT_IN
            );
            $accountVat = app('data.helper')->getFinanceConfigurationDetailAccount($finance_config_vat_in);
            if (!is_null($accountVat)) {
                $invoiceAccountTransaction[] = [
                    'account_id' => $accountVat->id,
                    'debit_amount' => $request->nominal_vat,
                    'credit_amount' => 0
                ];
            }
        }


        // handle tax 23
        if ($request->has('nominal_prepaid_income_tax')) {
            if ($request->nominal_prepaid_income_tax > 0) {
                $finance_config_holding_tax = app('data.helper')->getFinanceConfigurationActiveBySpec(
                    $request->company_id, FinanceConfiguration::CODE_ACCOUNT_INCOME_TAX23_PAYABLE
                );
                $accountWithHoldingTax = app('data.helper')->getFinanceConfigurationDetailAccount($finance_config_holding_tax);
                if (!is_null($accountWithHoldingTax)) {
                    $invoiceAccountTransaction[] = [
                        'account_id' => $accountWithHoldingTax->id,
                        'debit_amount' => 0,
                        'credit_amount' => $request->nominal_prepaid_income_tax
                    ];
                }
            }
        }


        $request->request->add([
            'data_transaction_detail' => $invoiceAccountTransaction
        ]);
    }
}
