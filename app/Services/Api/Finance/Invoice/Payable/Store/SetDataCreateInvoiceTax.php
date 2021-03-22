<?php

namespace App\Services\Api\Finance\Invoice\Payable\Store;

use App\Models\FinanceConfiguration;
use Illuminate\Http\Request;

class SetDataCreateInvoiceTax
{
    public static function handle(Request $request)
    {
        $dataInvoiceTaxes = [];

        if ($request->has('nominal_vat')) {
            if ($request->nominal_vat > 0) {
                $finance_config_vat_out = app('data.helper')->getFinanceConfigurationActiveBySpec(
                    $request->company_id, FinanceConfiguration::CODE_ACCOUNT_VAT_OUT
                );
                $accountVat = app('data.helper')->getFinanceConfigurationDetailAccount($finance_config_vat_out);
                if (!is_null($accountVat)) {
                    $dataInvoiceTaxes[] = [
                        'amount' => $request->nominal_vat,
                        'account_id' => $accountVat->id
                    ];
                }
            }
        }

        if ($request->has('nominal_prepaid_income_tax')) {
            if ($request->nominal_prepaid_income_tax > 0) {
                $finance_config_tax_article23 = app('data.helper')->getFinanceConfigurationActiveBySpec(
                    $request->company_id, FinanceConfiguration::CODE_ACCOUNT_INCOME_TAX23_PREPAID
                );
                $accountTaxArticle23 = app('data.helper')->getFinanceConfigurationDetailAccount($finance_config_tax_article23);

                if (!is_null($accountTaxArticle23)) {
                    $dataInvoiceTaxes[] = [
                        'amount' => $request->nominal_prepaid_income_tax,
                        'account_id' => $accountTaxArticle23->id
                    ];
                }
            }
        }

        $request->request->add([
            'data_invoice_taxes' => $dataInvoiceTaxes
        ]);
    }
}
