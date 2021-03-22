<?php

namespace App\Services\Api\Finance\Voucher\Create;

use Illuminate\Http\Request;

class SetDataVoucherInvoiceExpense
{
    public static function handle(Request $request)
    {
        $dataVoucherInvoiceExpense = [];
        $dataReformatted = $request->data_reformatted;

        foreach ($dataReformatted as $key => $value) {
            $additionalExpenseAccounts = $dataReformatted[$key]['additional_accounts'];
            if (count($additionalExpenseAccounts) > 0) {
                foreach ($additionalExpenseAccounts as $keyAea => $valueAea) {
                    $dataVoucherInvoiceExpense[$key][] = [
                        'nominal' => $valueAea,
                        'account_id' => (int) $keyAea,
                        'created_at' => now()
                    ];
                }
            }
        }

        $request->request->add([
            'data_save_voucher_invoice_expense' => $dataVoucherInvoiceExpense
        ]);
    }
}
