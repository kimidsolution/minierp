<?php

namespace App\Services\Api\Company\ListsPartner;

use App\Models\Partner;
use League\Fractal\Manager;
use Illuminate\Http\Request;
use League\Fractal\Resource\Collection;

class GetPartnerOfCompany
{
    public static function handle(Request $request)
    {
        $userCompany = $request->user_company;
        $partnerCompany = Partner::where('company_id', $userCompany->company_id)->get();
        
        if ($partnerCompany->count() < 1) {
            $request->request->add([
                'data_response' => []
            ]);
            return true;
        }

        $fractal = new Manager();
        $partnerCompanyArray = $partnerCompany->toArray();
        $resource = new Collection($partnerCompanyArray, function(array $pc) {
            return [
                'id' => (int) $pc['id'],
                'text' => $pc['name']
            ];
        });

        $array = $fractal->createData($resource)->toArray();
        $request->request->add([
            'data_response' => $array['data']
        ]);   
    }
}
