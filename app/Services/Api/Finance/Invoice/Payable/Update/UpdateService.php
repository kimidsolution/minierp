<?php

namespace App\Services\Api\Finance\Invoice\Payable\Update;

use Illuminate\Http\Request;

class UpdateService
{
    public function handle(Request $request)
    {
        ValidateRequest::handle($request);
        ValidateRangeDate::handle($request);
        GetDataCompany::handle($request);
        ValidateNominal::handle($request);
        ValidateProducts::handle($request);
        SetDataUpdateInvoice::handle($request);
        SetDataUpdateInvoiceDetail::handle($request);
        SetDataUpdateInvoiceTax::handle($request);
        SetDataTransaction::handle($request);
        SetDataTransactionDetail::handle($request);
        SaveData::handle($request);
        
        return response()->json([
            'url' => route('finance.invoices.payable.index')
        ]);
    }
}