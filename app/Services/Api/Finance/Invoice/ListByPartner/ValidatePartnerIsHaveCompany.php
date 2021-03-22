<?php

namespace App\Services\Api\Finance\Invoice\ListByPartner;

use Illuminate\Http\Request;

class ValidatePartnerIsHaveCompany
{
    public static function handle(Request $request)
    {
        $userCompany = $request->user_company;
        $partner = \App\Models\Partner::where('id', $request->partner_id)->where('company_id', $userCompany->company_id)->first();

        if (is_null($partner))
            abort(400, 'Partner not found in your company');
    }
}
