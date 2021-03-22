<?php

namespace App\Services\Api\Finance\Expense\Store;

use Illuminate\Http\Request;

class StoreService
{
    public function handle(Request $request)
    {
        ValidateRequest::handle($request);
        ValidateAccountIdHaveCompany::handle($request);
        SetDataCreateExpense::handle($request);
        SetDataCreateTransaction::handle($request);
        SetDataCreateTransactionDetail::handle($request);
        $response = SaveData::handle($request);
        return $response;
    }
}
