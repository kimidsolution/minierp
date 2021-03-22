<?php

namespace App\Services\Api\Finance\Voucher\Update;

use DB;
use Illuminate\Http\Request;

class UpdateService
{
    public function handle(Request $request)
    {
        ValidateRequest::handle($request);
        GetDataCompany::handle($request);
        ValidateAssetAccountIdUsed::handle($request);
        SetListInvoiceId::handle($request);
        ValidateInvoiceIdHaveCompanyWithPartner::handle($request);
        ValidateInvoiceNumberHasBeenPaid::handle($request);
        
        DB::beginTransaction();

        try {

            DeleteDataDraftById::handle($request);
            SetDataUpdateVoucher::handle($request);
            SetDataUpdateVoucherInvoice::handle($request);
            SetDataUpdateTransaction::handle($request);
            SetDataTransactionDetail::handle($request);
            SetDataVoucherInvoiceExpense::handle($request);
            SaveTransactionVoucher::handle($request);
            
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            abort(400, $e->getMessage());
        }

        return response()->json([
            'url' => route('finance.vouchers.index')
        ]);
    }
}
