<?php

namespace App\Services\Api\Finance\Invoice\Receivable\Store;

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

        // add discount
        if ($request->nominal_discount > 0) {
            $finance_config_sales_disc = app('data.helper')->getFinanceConfigurationActiveBySpec(
                $request->company_id, FinanceConfiguration::CODE_ACCOUNT_SALES_DISCOUNTS
            );
            $accountDiscount = app('data.helper')->getFinanceConfigurationDetailAccount($finance_config_sales_disc);
            if (!is_null($accountDiscount)) {
                $invoiceAccountTransaction[] = [
                    'account_id' => $accountDiscount->id,
                    'debit_amount' => $request->nominal_discount,
                    'credit_amount' => 0
                ];
            }
        }

        // add good sales
        foreach ($dataProducts as $key => $value) {
            array_push($priceProducts, $value['total']);
        }

        $totalPriceProducts = array_sum($priceProducts);

        $finance_config_goodsales = app('data.helper')->getFinanceConfigurationActiveBySpec(
            $request->company_id, FinanceConfiguration::CODE_ACCOUNT_GOODS_SALES
        );
        $accountDiscount = app('data.helper')->getFinanceConfigurationDetailAccount($finance_config_goodsales);
        if (!is_null($accountDiscount)) {
            $invoiceAccountTransaction[] = [
                'account_id' => $accountDiscount->id,
                'debit_amount' => 0,
                'credit_amount' => $totalPriceProducts
            ];
        }


        // add ar sales
        $finance_config_arsales = app('data.helper')->getFinanceConfigurationActiveBySpec(
            $request->company_id, FinanceConfiguration::CODE_ACCOUNT_AR_SALES
        );
        $accountDiscount = app('data.helper')->getFinanceConfigurationDetailAccount($finance_config_arsales);
        if (!is_null($accountDiscount)) {
            $invoiceAccountTransaction[] = [
                'account_id' => $accountDiscount->id,
                'debit_amount' => $request->nominal_remaining,
                'credit_amount' => 0
            ];
        }


        // handle down payment
        if ($request->nominal_down_payment > 0) {
            $invoiceAccountTransaction[] = [
                'account_id' => $request->account_id_asset,
                'debit_amount' => $request->nominal_down_payment,
                'credit_amount' => 0
            ];
        }


        // handle vat
        if (true == $dataCompany['company']['vat_enabled']) {
            $finance_config_vat_out = app('data.helper')->getFinanceConfigurationActiveBySpec(
                $request->company_id, FinanceConfiguration::CODE_ACCOUNT_VAT_OUT
            );
            $accountVat = app('data.helper')->getFinanceConfigurationDetailAccount($finance_config_vat_out);
            if (!is_null($accountVat)) {
                $invoiceAccountTransaction[] = [
                    'account_id' => $accountVat->id,
                    'debit_amount' => 0,
                    'credit_amount' => $request->nominal_vat
                ];
            }
        }

        $request->request->add([
            'data_transaction_detail' => $invoiceAccountTransaction
        ]);
    }
}
