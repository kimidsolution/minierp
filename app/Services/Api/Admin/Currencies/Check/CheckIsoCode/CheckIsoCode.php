<?php

namespace App\Services\Api\Admin\Currencies\Check\CheckIsoCode;

use App\Models\Currency;
use Illuminate\Http\Request;

class CheckIsoCode
{
    public static function handle(Request $request)
    {
        $iso_code = preg_replace('/\s+/', '', $request->iso_code);
        $get_data = Currency::where('iso_code', $iso_code);
        if ($request->except_id) {
            $get_data->whereNotIn('id', [$request->except_id]);
        }
        $currencies = $get_data->get();
        $request->request->add([
            'is_unique' => count($currencies) == 0,
            'data_response' => $currencies
        ]);
    }
}
