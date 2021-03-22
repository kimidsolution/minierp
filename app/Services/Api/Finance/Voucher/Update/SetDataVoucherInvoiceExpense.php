<?php

namespace App\Services\Api\Finance\Voucher\Update;

use Illuminate\Http\Request;

class SetDataVoucherInvoiceExpense
{
    public static function handle(Request $request)
    {
        $dataVoucherInvoiceExpense = [];
        $dataReformatted = $request->data;

        foreach ($dataReformatted as $key => $value) {
            $additionalExpenseAccounts = $dataReformatted[$key]['additional_accounts'];
            if (count($additionalExpenseAccounts) > 0) {
                foreach ($additionalExpenseAccounts as $keyAea => $valueAea) {
                    if ($valueAea['nominal'] > 0 && $valueAea['account_id'] !== null) {
                        $dataVoucherInvoiceExpense[$key][] = [
                            'amount' => $valueAea['nominal'],
                            'account_id' => $valueAea['account_id']
                        ];
                    }
                }
            }
        }

        $request->request->add([
            'data_save_voucher_invoice_expense' => $dataVoucherInvoiceExpense
        ]);
    }
}
