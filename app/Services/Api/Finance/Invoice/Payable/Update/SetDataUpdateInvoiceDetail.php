<?php

namespace App\Services\Api\Finance\Invoice\Payable\Update;

use Illuminate\Http\Request;

class SetDataUpdateInvoiceDetail
{
    public static function handle(Request $request)
    {
        $dataInvoiceDetails = [];
        $dataProducts = $request->products;

        foreach ($dataProducts as $key => $value) {
            $dataInvoiceDetails[$key]['quantity'] = $value['qty'];
            $dataInvoiceDetails[$key]['price'] = $value['basic_price'];
            $dataInvoiceDetails[$key]['product_id'] = $value['product_id'];
        }

        $request->request->add([
            'data_invoice_detail' => $dataInvoiceDetails
        ]);
    }
}
