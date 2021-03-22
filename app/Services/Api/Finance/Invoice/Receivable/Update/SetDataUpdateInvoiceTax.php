<?php

namespace App\Services\Api\Finance\Invoice\Receivable\Update;

use App\Models\Account;
use App\Models\Company;
use App\Models\FinanceConfiguration;
use App\Models\Product;
use Illuminate\Http\Request;
use Mattiasgeniar\Percentage\Percentage;

class SetDataUpdateInvoiceTax
{
    public static function handle(Request $request)
    {
        $dataInvoiceTaxes = [];
        $totalNominalServices = [];
        $products = $request->products;
        $company = Company::find($request->company_id);


        // check vat status company
        if (true == $company->vat_enabled) {
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


        // check item is service
        foreach ($products as $key => $value) {
            $product = Product::find($value['product_id']);
            if ($product->type == Product::TYPE_SERVICE) {
                array_push($totalNominalServices, $value['total']);
            }
        }

        if (count($totalNominalServices) > 0) {
            $finance_config_tax_article23 = app('data.helper')->getFinanceConfigurationActiveBySpec(
                $request->company_id, FinanceConfiguration::CODE_ACCOUNT_INCOME_TAX23_PREPAID
            );
            $accountTaxArticle23 = app('data.helper')->getFinanceConfigurationDetailAccount($finance_config_tax_article23);
            if (!is_null($accountTaxArticle23)) {
                $dataInvoiceTaxes[] = [
                    'amount' => Percentage::of(2, array_sum($totalNominalServices)),
                    'account_id' => $accountTaxArticle23['id']
                ];
            }
        }

        $request->request->add([
            'data_invoice_taxes' => $dataInvoiceTaxes
        ]);
    }
}
