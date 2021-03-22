<?php

namespace App\Services\Api\Finance\Voucher\Create;

use App\Models\Invoice;
use Illuminate\Http\Request;
use App\Models\VoucherInvoice;

class SetDataCreateVoucherInvoice
{
    public static function handle(Request $request)
    {
        $dataSaveVoucherInvoice = [];
        $dataFormatted = $request->data_reformatted;

        foreach ($dataFormatted as $key => $value) {

            // get nominal remaining payment
            $remainingPaymentByInvoice = Invoice::find($value['invoice_id'])->final_amount;
            $nominalVoucherInvoice = VoucherInvoice::where('invoice_id', $value['invoice_id'])->sum('nominal');
            $nominalRemainingPay = $remainingPaymentByInvoice - $nominalVoucherInvoice;
            
            
            // nominal user pay
            $arrayValuesAdditionalAccount = array_values($value['additional_accounts']);
            $nominalUserPay = array_sum($arrayValuesAdditionalAccount) + $value['final_amount'];
            $remainingPaymentInvoiceInt = (int) $nominalRemainingPay;

            // set status payment
            if ($remainingPaymentInvoiceInt == $nominalUserPay) {
                $statusPaid = 'pay in full';
            } elseif ($nominalUserPay > $remainingPaymentInvoiceInt) {
                $statusPaid = 'overpayment';
            } elseif ($nominalUserPay < $remainingPaymentInvoiceInt) {
                $statusPaid = 'insufficient payment';
            } else {
                $statusPaid = 'not yet paid';
            }

            $dataSaveVoucherInvoice[$key]['nominal'] = $nominalUserPay;
            $dataSaveVoucherInvoice[$key]['status_paid'] = $statusPaid;
            $dataSaveVoucherInvoice[$key]['invoice_id'] = $value['invoice_id'];
            $dataSaveVoucherInvoice[$key]['voucher_id'] = null;
            $dataSaveVoucherInvoice[$key]['created_at'] = now();
            $dataSaveVoucherInvoice[$key]['updated_at'] = now();

            $dataFormatted[$key]['total_nominal_user_pay'] = $nominalUserPay;   
            $dataFormatted[$key]['total_nominal_remaining_pay'] = $remainingPaymentInvoiceInt;
        }


        $request->request->add([
            'data_save_voucher_invoice' => $dataSaveVoucherInvoice,
            'data_reformatted' => $dataFormatted
        ]);
    }
}
