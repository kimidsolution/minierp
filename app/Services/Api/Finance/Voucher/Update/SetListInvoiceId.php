<?php

namespace App\Services\Api\Finance\Voucher\Update;

use Illuminate\Http\Request;

class SetListInvoiceId
{
    public static function handle(Request $request)
    {
        $listInvoiceId = [];
        $data = $request->data;

        foreach ($data as $key => $value) {
            array_push($listInvoiceId, $value['invoice_id']);
        }

        $request->request->add([
            'list_invoice_id' => $listInvoiceId
        ]);
    }
}
