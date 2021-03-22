<?php

namespace App\Services\Api\Finance\Voucher\Update;

use Carbon\Carbon;
use App\Models\Invoice;
use Illuminate\Http\Request;

class SetDataUpdateVoucherInvoice
{
    public static function handle(Request $request)
    {
        $carbonNow = Carbon::now();
        $dataSaveVoucherInvoice = [];
        $dataFormatted = $request->data;

        foreach ($dataFormatted as $key => $value) {

            // get nominal remaining payment
            $invoice = Invoice::find($value['invoice_id']);
            $nominalRemainingPay = app('invoice.helper')->getNominalRemainingPaymentInvoice($value['invoice_id'], $invoice->total_amount);

            // nominal user pay
            $nominalUserPay = $value['amount'];
            
            // set status payment
            if ($nominalRemainingPay == $nominalUserPay) {
                $statusPaid = Invoice::STATUS_FULL_PAYMENT;
            } elseif ($nominalUserPay > $nominalRemainingPay) {
                $statusPaid = Invoice::STATUS_OVERPAYMENT;
            } elseif ($nominalUserPay < $nominalRemainingPay) {
                if (true == $carbonNow->greaterThan($invoice->due_date)) {
                    $statusPaid = Invoice::STATUS_OVERDUE;
                } else {
                    $statusPaid = Invoice::STATUS_PARTIAL_PAYMENT;
                }
            } else {
                if (true == $carbonNow->greaterThan($invoice->due_date)) {
                    $statusPaid = Invoice::STATUS_OVERDUE;
                } else {
                    $statusPaid = Invoice::STATUS_OUTSTANDING;
                }
            }

            $dataSaveVoucherInvoice[$key]['amount'] = $nominalUserPay;
            $dataSaveVoucherInvoice[$key]['payment_status'] = $statusPaid;
            $dataSaveVoucherInvoice[$key]['invoice_id'] = $value['invoice_id'];
            $dataFormatted[$key]['total_nominal_remaining_pay'] = $nominalRemainingPay;
            $dataFormatted[$key]['total_nominal_user_pay'] = $nominalUserPay;
        }


        $request->request->add([
            'data_save_voucher_invoice' => $dataSaveVoucherInvoice,
            'data' => $dataFormatted
        ]);
    }
}
