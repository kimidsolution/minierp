<?php

namespace App\Services\Api\Configuration\Finance\Store;

use App\Models\FinanceConfiguration;
use App\Models\FinanceConfigurationDetail;
use Exception;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StoreData
{
    public static function handle(Request $request)
    {
        DB::beginTransaction();
        try {
            if (!is_null($request->configuration_id)) {
                $title_button = 'Edit';
                $message_response = 'Configuration has been updated';
                $finance_config = Self::handleStoreUpdateConfig($request, $request->configuration_id);
                if (!is_null($finance_config->id)) FinanceConfigurationDetail::where('finance_configuration_id', $finance_config->id)->delete(); // flush and save
            } else {
                $title_button = 'Save';
                $message_response = 'Configuration has been created';
                $finance_config = Self::handleStoreNewConfig($request);
            }

            $config_details = [];
            foreach ($request->accounts as $key => $account_id) $accounts_id[$key] = $account_id;

            for ($i = 0; $i < count($accounts_id); $i++) {
                $config_details[$i]['id'] = (string) Str::uuid();
                $config_details[$i]['account_id'] = $accounts_id[$i];
                $config_details[$i]['finance_configuration_id'] = $finance_config->id;
            }

            FinanceConfigurationDetail::insert($config_details);

            DB::commit();
            $response = [
                'data' => $finance_config,
                'message' => $message_response,
                'text_button' => $title_button
            ];

            return response()->api(true, [], $response);
        } catch (Exception $e) {
            DB::rollBack();
            abort(400, $e->getMessage());
        }
    }

    protected static function handleStoreNewConfig($data) {
        return FinanceConfiguration::create([
            'company_id' => $data['company_id'],
            'configuration_code' => $data['config_code'],
            'configuration_description' => $data['configuration_description'],
            'configuration_status' => $data['configuration_status']
        ]);
    }

    protected static function handleStoreUpdateConfig($data, $config_id) {
        $configuration = FinanceConfiguration::find($config_id);
        if (is_null($configuration)) return abort(400, 'Configuration not found');

        $configuration->company_id = $data['company_id'];
        $configuration->configuration_code = $data['config_code'];
        $configuration->configuration_description = $data['configuration_description'];
        $configuration->configuration_status = $data['configuration_status'];
        $configuration->save();

        return $configuration;
    }
}
