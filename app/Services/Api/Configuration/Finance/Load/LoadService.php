<?php

namespace App\Services\Api\Configuration\Finance\Load;

use App\Models\FinanceConfiguration;
use Illuminate\Http\Request;

class LoadService
{
    public function handle(Request $request)
    {
        $result = [];

        $query = FinanceConfiguration::with(['finance_configuration_details.account']);
        if (!is_null($request->configuration_id)) $query->where('id', $request->configuration_id);
        if (!is_null($request->company_id)) $query->where('company_id', $request->company_id);
        if (!is_null($request->configuration_status)) $query->where('configuration_status', $request->configuration_status);
        if (!is_null($request->configuration_code)) $query->where('configuration_code', $request->configuration_code);

        $data = $query->first();

        if ($data) {
            $dataDetails = $data->finance_configuration_details->toArray();
            $result = [
                'id' => $data->id,
                'company_id' => $data->company_id,
                'configuration_code' => $data->configuration_code,
                'configuration_description' => $data->configuration_description,
                'configuration_status' => $data->configuration_status,
                'details' => $dataDetails
            ];
        }

        return response()->api(true, [], $result, '', 200);
    }
}
