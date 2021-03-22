<?php

namespace App\Services\Api\Finance\Voucher\Create;

use Illuminate\Http\Request;

class CreateService
{
    public function handle(Request $request)
    {
        ValidateRequest::handle($request);
        GetDataCompany::handle($request);
        SetListInvoiceId::handle($request);
        ValidateInvoiceIdHaveCompanyWithPartner::handle($request);
        ValidateInvoiceNumberHasBeenPaid::handle($request);
        ValidateAccountIdPaid::handle($request);
        ReformattedData::handle($request);
        SetDataCreateVoucher::handle($request);
        SetDataCreateVoucherInvoice::handle($request);
        SetDataTransaction::handle($request);
        SetDataTransactionDetail::handle($request);
        SetDataVoucherInvoiceExpense::handle($request);
        SaveTransactionVoucher::handle($request);

        return 'ok';
    }
}
