<?php

namespace App\Services\Api\Finance\Voucher\GetListInvoiceById;

use Illuminate\Http\Request;

class GetListInvoiceByIdService
{
    public function handle(Request $request)
    {
        GetDetailVoucher::handle($request);
        FilterByInvoiceId::handle($request);
        GetNominalAmountInvoiceHasBeenPaid::handle($request);
        SetResponse::handle($request);
        
        return response()->api(true, [], $request->data_response, '', 200);   
    }
}
