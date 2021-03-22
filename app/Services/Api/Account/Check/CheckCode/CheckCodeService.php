<?php

namespace App\Services\Api\Account\Check\CheckCode;

use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class CheckCodeService
{
    public function handle(Request $request)
    {
        try {
            app()->make('App\Http\Requests\CheckCodeAccountRequest');
            CheckAccount::handle($request);
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
