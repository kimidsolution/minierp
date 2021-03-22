<?php

namespace App\Services\Api\Company\Detail;

use Illuminate\Http\Request;
use App\Models\Company;

class GetDetailCompanyService
{
    public function handle(Request $request)
    {
        $company = Company::where('id', $request->company_id)->first();

        if (is_null($company))
            abort(400, 'Company not found.');

        $request->request->add([
            'data_response' => $company
        ]);

        return response()->api(true, [], $request->data_response);
    }
}