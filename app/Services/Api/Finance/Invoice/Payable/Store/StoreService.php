<?php

namespace App\Services\Api\Finance\Invoice\Payable\Store;

use Illuminate\Http\Request;

class StoreService
{
    public function handle(Request $request)
    {
        ValidateRequest::handle($request);
        ValidateRangeDate::handle($request);
        GetDataCompany::handle($request);
        ValidateNominal::handle($request);
        ValidateProducts::handle($request);
        SetDataCreateInvoice::handle($request);
        SetDataCreateInvoiceDetail::handle($request);
        SetDataCreateInvoiceTax::handle($request);
        SetDataTransaction::handle($request);
        SetDataTransactionDetail::handle($request);
        SaveData::handle($request);
        
        $invoiceSaved = $request->invoice_saved;
        return response()->json([
            'url' => (1 == $request->is_posted) ? route('finance.invoices.payable.show', ['payable' => $invoiceSaved->id]) : route('finance.invoices.payable.create')
        ]);
    }
}