<?php

namespace App\Services\Api\Finance\Invoice\ListByPartner;

use Carbon\Carbon;
use App\Models\Invoice;
use App\Models\InvoiceTax;
use League\Fractal\Manager;
use Illuminate\Http\Request;
use League\Fractal\Resource\Collection;

class GetListInvoiceHaveNotPaidByPartner
{
    public static function handle(Request $request)
    {
        $userCompany = $request->user_company;
        $partnerInvoices = \App\Models\Invoice::where('partner_id', $request->partner_id)
                            ->where('company_id', $userCompany->company_id)
                            ->whereIn('payment_status', [
                                    Invoice::STATUS_PARTIAL_PAYMENT, 
                                    Invoice::STATUS_OUTSTANDING, 
                                    Invoice::STATUS_OVERDUE
                                ])
                            ->where('is_posted', Invoice::POSTED_YES)
                            ->where('type', $request->type)
                            ->get();
        
        if ($partnerInvoices->count() < 1) {
            $request->request->add([
                'data_response' => []
            ]);
            return true;
        }

        $partnerInvoiceArray = $partnerInvoices->toArray();

        $fractal = new Manager();
        $resource = new Collection($partnerInvoiceArray, function(array $pi) {
            return [
                'id' => $pi['id'],
                'text' => $pi['invoice_number'],
                'detail' => [
                    'date' => Carbon::parse($pi['invoice_date'])->toDateString(),
                    'due_date' => Carbon::parse($pi['due_date'])->toDateString(),
                    'number' => $pi['invoice_number'],
                    'discount' => $pi['discount'],
                    'down_payment' => $pi['down_payment'],
                    'final_amount' => $pi['total_amount'],
                    'note' => $pi['note'],
                    'remaining_payment' => app('invoice.helper')->getNominalRemainingPaymentInvoice($pi['id'], $pi['total_amount'])
                ]
            ];
        });

        $array = $fractal->createData($resource)->toArray();
        $request->request->add([
            'data_response' => $array['data']
        ]);
    }
}
