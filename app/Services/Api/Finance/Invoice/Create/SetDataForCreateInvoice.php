<?php

namespace App\Services\Api\Finance\Invoice\Create;

use Illuminate\Support\Str;
use Illuminate\Http\Request;

class SetDataForCreateInvoice
{
    public static function handle(Request $request)
    {
        $dataCompany = $request->data_company;
        $dataProducts = $request->products;
        $dataInvoiceDetails = [];
        $totalPrices = [];

        // data invoice detail
        foreach ($dataProducts as $key => $value) {
            $dataInvoiceDetails[$key]['qty'] = $value['qty'];
            $dataInvoiceDetails[$key]['basic_price'] = $value['basic_price'];
            $dataInvoiceDetails[$key]['total_price'] = $value['total'];
            $dataInvoiceDetails[$key]['product_id'] = $value['product_id'];
        }

        foreach ($dataInvoiceDetails as $key => $value) {
            array_push($totalPrices, $value['total_price']);
        }

        $nominalTotalPrices = (array_sum($totalPrices) - $request->nominal_discount);

        // data invoice
        $dataInvoice = [
            'uuid' => (string) Str::uuid(),
            'type' => 'sales',
            'status_paid' => 'not yet paid',
            'date' => $request->date,
            'due_date' => $request->due_date,
            'is_posted' => 'no',
            'is_send_to_partner' => 'no',
            'number' => $request->invoice_number,
            'amount' => array_sum($totalPrices),
            'discount' => $request->nominal_discount,
            'total_tax' => $request->nominal_tax,
            'amount_before_tax' => $nominalTotalPrices,
            'down_payment' => $request->nominal_down_payment,
            'final_amount' => $request->nominal_remaining,
            'remaining_payment' => $request->nominal_remaining,
            'note' => $request->description,
            'company_id' => $dataCompany['company_id'],
            'partner_id' => $request->partner_id,
            'created_by' => $request->user_id,
            'updated_by' => $request->user_id,
            'created_at' => now(),
            'updated_at' => now()
        ];

        

        $request->request->add([
            'data_invoice' => $dataInvoice,
            'data_invoice_detail' => $dataInvoiceDetails
        ]);
    }
}
