<?php

namespace App\Services\Api\Finance\Invoice\Receivable\Update;

use App\User;
use Carbon\Carbon;
use App\Models\Invoice;
use Illuminate\Http\Request;

class SetDataUpdateInvoice
{
    public static function handle(Request $request)
    {
        // find user name by user id
        $data_user = User::where('id', $request->user_id)->first();

        $dataInvoice = [
            'company_id' => $request->company_id,
            'partner_id' => $request->partner_id,
            'type' => Invoice::TYPE_RECEIVABLE,            
            'downpayment_account_id' => $request->account_id_asset,
            'payment_status' =>  app('invoice.helper')->getPaymentStatusInvoiceBeforeVoucherCreated($request->due_date, $request->nominal_down_payment),
            'invoice_date' => Carbon::parse($request->invoice_date),
            'due_date' => Carbon::parse($request->due_date),
            'is_posted' => Invoice::POSTED_NO,
            'sent_to_partner' => Invoice::SEND_PARTNER_NO,
            'invoice_number' => $request->invoice_number,
            'discount' => $request->nominal_discount,
            'down_payment' => $request->nominal_down_payment,
            'total_amount' => app('invoice.helper')->calculateTotalAmountInvoiceReceivable($request->company_id, $request->products, $request->nominal_discount, $request->nominal_down_payment, $request->nominal_vat),
            'note' => $request->description,
            'company_id' => $request->company_id,
            'partner_id' => $request->partner_id,
            'updated_by' => is_null($data_user) ? NULL : $data_user->name
        ];

        $request->request->add([
            'data_invoice' => $dataInvoice
        ]);
    }
}
