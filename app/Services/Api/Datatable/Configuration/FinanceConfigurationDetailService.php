<?php

namespace App\Services\Api\Datatable\Configuration;

use Illuminate\Support\Facades\DB;

class FinanceConfigurationDetailService
{
    public function handle()
    {
        $result = [];
        if (!is_null(request()->id)) {
            $query = DB::table('finance_configuration_details')->select([DB::raw('
                finance_configuration_details.id,
                finance_configuration_details.account_id,
                finance_configuration_details.finance_configuration_id,
                CONCAT(
                    accounts.account_code, " - ",
                    IF ((accounts.account_text IS NOT NULL OR accounts.account_text != ""),
                    accounts.account_text, accounts.account_name)
                ) AS account_naming,
                accounts.account_type
            ')])
            ->join('accounts', 'finance_configuration_details.account_id', '=', 'accounts.id')->where('finance_configuration_details.finance_configuration_id', request()->id);

            $result = $query->orderBy('accounts.account_type', 'asc')->get();
        }

        return $result;
    }
}
