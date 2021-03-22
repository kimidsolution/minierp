<?php

namespace App\Services\Api\Finance\Report\GeneralLedger;

use Illuminate\Http\Request;

class GetTransactionByFilter
{
    const COUNT_PAGINATION = 50;

    public static function handle(Request $request)
    {
        $request->request->add(['pagination' => Self::COUNT_PAGINATION]);
        $data = GetDataGeneralLedger::handle($request);
        $data_items = $data->items();

        $request->request->add([
            'links' => $data->toArray(),
            'data_provider' => $data_items
        ]);
    }
}
