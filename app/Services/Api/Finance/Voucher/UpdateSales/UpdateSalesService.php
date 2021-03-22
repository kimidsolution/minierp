<?php

namespace App\Services\Api\Finance\Voucher\UpdateSales;

use Illuminate\Http\Request;

class UpdateSalesService
{
    public function handle(Request $request)
    {
        ValidateRequest::handle($request);
        GetDataCompany::handle($request);
        ValidateInvoiceNumberBelongToCompany::handle($request);
        ValidateAccountIdPaid::handle($request);
        SetDataUpdateVoucher::handle($request);
        SetDataTransaction::handle($request);
        SaveTransactionVoucher::handle($request);

        return 'ok';
    }
}
