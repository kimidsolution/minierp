<?php

namespace App\Services\Api\Configuration\Finance\Store;

use App\Models\Company;
use App\Models\User;
use Illuminate\Http\Request;

class BeforeStore
{
    public static function handle(Request $request)
    {
        $description_config = null;
        $user = User::find($request->user_id);
        $company = Company::find($request->company_id);

        if (is_null($user)) abort(400, 'User not found');
        if (is_null($company)) abort(400, 'Company not found');
        if (empty($request->accounts)) abort(400, 'Account has required');

        foreach (config('sempoa.config_finance') as $config) {
            if ($config['code'] == $request->config_code) $description_config = $config['description'];
        }

        $request->request->add([
            'user_id' => $user->id,
            'configuration_status' => $request->config_status == 'true' ? true : false,
            'configuration_description' => $description_config
        ]);
    }
}
