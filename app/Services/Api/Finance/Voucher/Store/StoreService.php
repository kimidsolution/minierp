<?php

namespace App\Services\Api\Finance\Voucher\Store;

use Illuminate\Http\Request;

class StoreService
{
    public function handle(Request $request)
    {
        ValidateRequest::handle($request);
        GetDataCompany::handle($request);
        ValidateAssetAccountIdUsed::handle($request);
        SetListInvoiceId::handle($request);
        ValidateInvoiceIdHaveCompanyWithPartner::handle($request);
        ValidateInvoiceNumberHasBeenPaid::handle($request);
        SetDataCreateVoucher::handle($request);
        SetDataCreateVoucherInvoice::handle($request);
        SetDataTransaction::handle($request);
        SetDataTransactionDetail::handle($request);
        SetDataVoucherInvoiceExpense::handle($request);
        SaveTransactionVoucher::handle($request);

        $voucherSaved = $request->voucher_saved;
        return response()->json([
            'url' => (1 == $request->is_posted) ? route('finance.vouchers.index') : route('finance.vouchers.create')
        ]);
    }
}
