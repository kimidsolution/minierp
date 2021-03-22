<?php

namespace App\Services\Api\Finance\Voucher\Create;

use Illuminate\Http\Request;

class SetDataTransaction
{
    public static function handle(Request $request)
    {
        $dataTransactionDetail = [];
        $userCompany = $request->user_company;
        $configTransactionAccountInVoucher = config('sempoa.voucher.account_used.sales');
        $dataReformatted = $request->data_reformatted;
        $dataSaveVoucherInvoice = $request->data_save_voucher_invoice;
        
        
        // set data transaction
        $dataSaveTransaction = [
            'date' => $request->date,
            'model_type' => '\App\Models\Voucher',
            'reference_number' => $request->number,
            'description' => $request->note,
            'company_id' => $userCompany->company_id,
            'created_at' => now(),
            'updated_at' => now()
        ];

        $request->request->add([
            'data_save_transaction' => $dataSaveTransaction
        ]);
    }
}
