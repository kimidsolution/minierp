<?php

namespace App\Services\Api\Configuration\Finance\Check\Usage;

use App\Models\Account;
use App\Models\FinanceConfiguration;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class CheckUsageCompanyService
{

    CONST MESSAGE_ALERT = 'Finance Configuration Not Ready Yet';
    CONST MESSAGE_ALERT_ACCOUNT = 'And Accounts Of Company Not Available';

    public function handle(Request $request)
    {
        try {
            app()->make('App\Http\Requests\ConfigurationFinanceCheckUsageRequest');

            $config_id_list = Self::getConfigIdList($request);
            $get_config_list = Self::getConfigList($config_id_list);
            $get_config_exist = Self::getConfigurationCompany($request->company_id);
            $array_description = Self::getDescriptionConfigReady([], $get_config_list);
            $check_accounts = Account::where('company_id', $request->company_id)->get()->toArray();

            if (!empty($get_config_exist)) {
                $config_id_exist = array_column($get_config_exist, 'configuration_code');
                $compare_merge_id_value = app('array.helper')->setArrayValueBoolWithComparison(
                    $config_id_exist, $config_id_list
                );
                $filter_from_merge = app('array.helper')->setArrayFilterWithComparisonKey(
                    $compare_merge_id_value, $config_id_list
                );
                $array_description = Self::getDescriptionConfigReady($filter_from_merge, $get_config_list);
                return response()->json([
                    'status' => true,
                    'status_config' => !in_array(false, array_values($filter_from_merge)),
                    'uncomplete_config' => $array_description,
                    'messages' => !in_array(false, array_values($filter_from_merge)) ?
                        '' : Self::MESSAGE_ALERT
                ]);
            }
            return response()->json([
                'status' => true,
                'status_config' => false,
                'uncomplete_config' => $array_description,
                'messages' => !empty($check_accounts) ?
                    Self::MESSAGE_ALERT : Self::MESSAGE_ALERT . ' ' . Self::MESSAGE_ALERT_ACCOUNT
            ]);
        } catch (QueryException $e) {
            return response()->json([
                'status' => false,
                'messages' => $e->getMessage()
            ]);
        }
    }

    protected static function getConfigIdList($request) {
        $config_list = config('sempoa.config_finance');
        $config_id_list = !is_null($request->config_code) && !empty($request->config_code) ?
            array_map(function($value) { return (int) $value; }, $request->config_code) :
                array_column($config_list, 'code');

        return $config_id_list;
    }

    protected static function getConfigList($config_id_list) {
        $result = [];
        $config_list = config('sempoa.config_finance');
        foreach ($config_list as $value) {
            if (in_array($value['code'], $config_id_list)) {
                array_push($result, $value);
            }
        }
        return $result;
    }

    protected static function getConfigurationCompany($company_id) {
        $result = FinanceConfiguration::where('company_id', $company_id)
            ->where('configuration_status', FinanceConfiguration::STATUS_ACTIVE)
            ->get()->toArray();

        return $result;
    }

    protected static function getDescriptionConfigReady($list_id_config, $list_config) {
        $result = [];

        if (!empty($list_id_config)) {
            foreach ($list_id_config as $key => $value) {
                foreach ($list_config as $config) {
                    if ($config['code'] == $key && $value == false) {
                        array_push($result, $config['description']);
                    }
                }
            }
        } else {
            foreach ($list_config as $config) array_push($result, $config['description']);
        }

        return $result;
    }
}
