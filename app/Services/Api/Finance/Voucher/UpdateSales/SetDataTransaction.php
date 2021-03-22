<?php

namespace App\Services\Api\Finance\Voucher\UpdateSales;

use Illuminate\Http\Request;

class SetDataTransaction
{
    public static function handle(Request $request)
    {
        $dataTransactionDetail = [];
        $userCompany = $request->user_company;
        $dataInvoice = $request->data_invoice;
        $configTransactionAccountInVoucher = config('sempoa.voucher.account_used.sales');
        

        // set data transaction
        $dataSaveTransaction = [
            'date' => date('Y-m-d'),
            'model_type' => '\App\Models\Voucher',
            'ref_number' => $request->reference_number,
            'description' => 'Payment From Customer',
            'company_id' => $userCompany->company_id,
            'created_at' => now(),
            'updated_at' => now()
        ];


        // set data transaction detail
        $accountNameCredit = array_keys($configTransactionAccountInVoucher);
        $accountCreditTransactionVoucher = \App\Models\Account::where('name', $accountNameCredit[0])
                                                ->where('company_id', $userCompany->company_id)
                                                ->first();
        
        
        // set data transaction detail debit
        $debit = [
            'debit_amount' => $dataInvoice->final_amount,
            'credit_amount' => 0,
            'account_id' => (int) $request->account_id_paid
        ];
        array_push($dataTransactionDetail, $debit);


        // set data transaction detail credit
        $credit = [
            'debit_amount' => 0,
            'credit_amount' => $dataInvoice->final_amount,
            'account_id' => (int) $accountCreditTransactionVoucher->id
        ];
        array_push($dataTransactionDetail, $credit);


        $request->request->add([
            'data_save_transaction_detail' => $dataTransactionDetail,
            'data_save_transaction' => $dataSaveTransaction
        ]);
    }
}
