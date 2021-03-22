<?php

namespace App\Services\Api\Finance\Report\GeneralLedger;

use Illuminate\Http\Request;

class ResponseProvider
{
    public static function handle(Request $request)
    {
        $links = $request->links;
        $data_result = [
            'last_balance_account' => $request->last_balance_account,
            'list_data' => $request->list_data_with_balance,
            'data_result_total' => $request->data_result_total
        ];
        $links['data'] = $data_result;

        return response()->json([
            'status' => 200,
            'dataFill' => $links
        ]);
    }
}
