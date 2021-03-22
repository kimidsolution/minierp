<?php

namespace App\Services\Api\Admin\Currencies\Check\CheckIsoCode;

use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class CheckIsoCodeService
{
    public function handle(Request $request)
    {
        try {
            app()->make('App\Http\Requests\CheckIsoCodeCurrenciesRequest');
            CheckIsoCode::handle($request);
            $message = $request->is_unique ? "Account code available" : "Account code has been registered";
            return response()->api($request->is_unique, [], $request->data_response, $message);
        } catch (QueryException $e) {
            return response()->json([
                'status' => false,
                'messages' => $e->getMessage()
            ]);
        }
    }
}
