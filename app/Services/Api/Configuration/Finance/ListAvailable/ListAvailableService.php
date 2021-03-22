<?php

namespace App\Services\Api\Configuration\Finance\ListAvailable;

use App\Models\FinanceConfiguration;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class ListAvailableService
{
    public function handle(Request $request)
    {
        try {
            $result = config('sempoa.config_finance');
            app()->make('App\Http\Requests\ListAccountByCompanyRequest');
            $exist_list = FinanceConfiguration::where('company_id', $request->company_id)
                ->where('configuration_status', FinanceConfiguration::STATUS_ACTIVE)
                ->get()->toArray();

            if (!empty($exist_list)) {
                foreach ($exist_list as $exist) {
                    foreach ($result as $key => $config) {
                        if (!is_null($request->selected_value)) {
                            if (
                                $config['code'] == $exist['configuration_code'] &&
                                $config['code'] != $request->selected_value
                            ) {
                                unset($result[$key]);
                            }
                        } else {
                            if ($config['code'] == $exist['configuration_code']) {
                                unset($result[$key]);
                            }
                        }
                    }
                }
            }

            return response()->api(true, [], array_values($result));
        } catch (QueryException $e) {
            return response()->json([
                'status' => false,
                'messages' => $e->getMessage()
            ]);
        }
    }
}
