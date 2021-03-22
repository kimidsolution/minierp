<?php

namespace App\Configurations\Finance;

use App\Models\FinanceConfiguration;
use App\Models\FinanceConfigurationDetail;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SavingConfiguration {

    public static function handle(
        $company_id,
        $configuration_code,
        $configuration_description,
        $accounts
    ) {
        DB::beginTransaction();
        try {
            $configuration = Self::handleHeader($company_id, $configuration_code, $configuration_description);
            Self::handleDetails($configuration->id, $accounts);
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            abort(400, $e->getMessage());
        }
    }

    protected static function handleHeader(
        $company_id,
        $configuration_code,
        $configuration_description
    ) {
        $configuration = FinanceConfiguration::create([
            'company_id' => $company_id,
            'configuration_code' => $configuration_code,
            'configuration_description' => $configuration_description,
            'configuration_status' => FinanceConfiguration::STATUS_ACTIVE
        ]);

        return $configuration;
    }

    protected static function handleDetails($configuration_id, $accounts) {
        $config_details = [];

        if (is_array($accounts)) {
            foreach ($accounts as $key => $account_id) $accounts_id[$key] = $account_id['id'];
            for ($i = 0; $i < count($accounts_id); $i++) {
                $config_details[$i]['id'] = (string) Str::uuid();
                $config_details[$i]['account_id'] = $accounts_id[$i];
                $config_details[$i]['finance_configuration_id'] = $configuration_id;
            }
        } else {
            $detail['id'] = (string) Str::uuid();
            $detail['account_id'] = $accounts->id;
            $detail['finance_configuration_id'] = $configuration_id;
            array_push($config_details, $detail);
        }

        FinanceConfigurationDetail::insert($config_details);
    }
}
