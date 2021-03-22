<?php

namespace App\Services\Api\Select2\GetPartnerVendorBoth;

use App\Models\Partner;
use League\Fractal\Manager;
use Illuminate\Http\Request;
use League\Fractal\Resource\Collection;

class GetPartnerVendorBothService
{
    public function handle(Request $request)
    {
        //? collect data partner for type vendor & both
        $partners = Partner::where('company_id', $request->company_id)->where(function ($query) {
            $query->where('is_vendor', '=', true)
                  ->orWhere('is_client', '=', true);
        })->where(function ($query) {
            $query->where('is_vendor', '=', true)
                  ->orWhere('is_client', '=', false);
        })->get();

        if ($partners->count() > 0) {

            $partnersArray = $partners->toArray();
            $fractal = new Manager();
            $resource = new Collection($partnersArray, function(array $partner) {
                return [
                    'id' => $partner['id'],
                    'text' => $partner['partner_name']
                ];
            });

            $source = $fractal->createData($resource)->toArray();
            $array = $source['data'];
        } else {
            $array = [];
        }

        return response()->api(true, [], $array, '', 200);
    }
}
