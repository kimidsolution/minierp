<?php

namespace App\Services\Finance\Revenue\Store;

use Illuminate\Http\Request;

class StoreService
{
    public function handle(Request $request)
    {
        ValidateRequest::handle($request);
        GetDataUser::handle($request);
        ValidateAssetAccountIdHaveCompany::handle($request);
        ValidateItems::handle($request);
        SetDataCreateRevenue::handle($request);
        SetDataCreateTransaction::handle($request);
        SetDataCreateTransactionDetail::handle($request);
        SaveData::handle($request);
        
        return 'ok';
    }
}