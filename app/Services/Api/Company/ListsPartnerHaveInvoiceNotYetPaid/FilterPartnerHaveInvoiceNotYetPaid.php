<?php

namespace App\Services\Api\Company\ListsPartnerHaveInvoiceNotYetPaid;

use Illuminate\Http\Request;
use App\Models\Invoice;

class FilterPartnerHaveInvoiceNotYetPaid
{
    public static function handle(Request $request)
    {
        $partnerTemporary = $request->data_response_temporary;

        foreach ($partnerTemporary as $key => $value) {
            $invoiceNotYetPaid = Invoice::whereIn('payment_status', [Invoice::STATUS_OUTSTANDING, Invoice::STATUS_PARTIAL_PAYMENT, Invoice::STATUS_OVERDUE])
                                    ->where('partner_id', $value['id'])
                                    ->where('type', $request->type)
                                    ->get();

            if ($invoiceNotYetPaid->count() < 1) {
                unset($partnerTemporary[$key]);
            }
        }

        $request->request->add([
            'data_response' => $partnerTemporary
        ]);
    }
}
