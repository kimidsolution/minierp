<?php

namespace App\Services\Api\Finance\Invoice\Create;

use Illuminate\Http\Request;

class CreateService
{
    public function handle(Request $request)
    {
        ValidateRequest::handle($request);
        ValidateRangeDate::handle($request);
        GetDataCompany::handle($request);
        ValidateAssetAccountIdHaveCompany::handle($request);
        ValidateNominal::handle($request);
        ValidateItems::handle($request);
        SetDataForCreateInvoice::handle($request);
        SetDataForTransaction::handle($request);
        SetDataForTransactionDetail::handle($request);
        $getData = SaveData::handle($request);
        
        return $getData;
    }
}