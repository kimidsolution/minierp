<?php

namespace App\Services\Api\Finance\Invoice\Payable\Store;

use App\User;
use Carbon\Carbon;
use App\Models\Invoice;
use Illuminate\Http\Request;

class SetDataCreateInvoice
{
    public static function handle(Request $request)
    {
        // find user name by user id
        $data_user = User::where('id', $request->user_id)->first();

        $dataInvoice = [
            'company_id' => $request->company_id,
            'partner_id' => $request->partner_id,
            'downpayment_account_id' => $request->account_id_asset,
            'type' => Invoice::TYPE_PAYABLE,
            'payment_status' =>  app('invoice.helper')->getPaymentStatusInvoiceBeforeVoucherCreated($request->due_date, $request->nominal_down_payment),
            'invoice_date' => Carbon::parse($request->invoice_date),
            'due_date' => Carbon::parse($request->due_date),
            'is_posted' => (1 == $request->is_posted) ? Invoice::POSTED_YES : Invoice::POSTED_NO,
            'sent_to_partner' => Invoice::SEND_PARTNER_NO,
            'invoice_number' => $request->invoice_number,
            'discount' => $request->nominal_discount,
            'down_payment' => $request->nominal_down_payment,
            'total_amount' => app('invoice.helper')->calculateTotalAmountInvoicePayable($request->company_id, $request->products, $request->nominal_discount, $request->nominal_down_payment, $request->nominal_vat, $request->nominal_prepaid_income_tax),
            'note' => $request->description,
            'purchase_order' => $request->purchase_order,
            'company_id' => $request->company_id,
            'partner_id' => $request->partner_id,
            'created_by' => is_null($data_user) ? NULL : $data_user->name,
            'posted_by' => (1 == $request->is_posted) ? $request->user_id : NULL,
            'updated_by' => is_null($data_user) ? NULL : $data_user->name
        ];

        $request->request->add([
            'data_invoice' => $dataInvoice
        ]);
    }
}
