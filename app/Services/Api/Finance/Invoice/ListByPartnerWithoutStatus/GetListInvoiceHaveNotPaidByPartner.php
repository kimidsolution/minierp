<?php

namespace App\Services\Api\Finance\Invoice\ListByPartnerWithoutStatus;

use Carbon\Carbon;
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
                            ->where('is_posted', 'yes')
                            ->where('type', $request->type)
                            ->get();
        
        if ($partnerInvoices->count() < 1) {
            $request->request->add([
                'data_response' => []
            ]);
            return true;
        }

        $fractal = new Manager();
        $partnerInvoiceArray = $partnerInvoices->toArray();
        $resource = new Collection($partnerInvoiceArray, function(array $pi) {
            return [
                'id' => (int) $pi['id'],
                'text' => $pi['number'],
                'detail' => [
                    'date' => Carbon::parse($pi['date'])->toDateString(),
                    'due_date' => Carbon::parse($pi['due_date'])->toDateString(),
                    'number' => $pi['number'],
                    'amount' => $pi['amount'],
                    'discount' => $pi['discount'],
                    'amount_before_tax' => $pi['amount_before_tax'],
                    'total_tax' => $pi['total_tax'],
                    'down_payment' => $pi['down_payment'],
                    'final_amount' => $pi['final_amount'],
                    'note' => $pi['note']
                ]
            ];
        });

        $array = $fractal->createData($resource)->toArray();
        $request->request->add([
            'data_response' => $array['data']
        ]);
    }
}
