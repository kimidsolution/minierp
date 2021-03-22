<?php

namespace App\Services\Api\Account\Lists\ListsAccountNested;

use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class ListsAccountService
{
    public function handle(Request $request)
    {
        try {
            app()->make('App\Http\Requests\ListAccountByCompanyRequest');
            GetDataAccount::handle($request);
            return response()->api(true, [], $request->data_response);
        } catch (QueryException $e) {
            return response()->json([
                'status' => false,
                'messages' => $e->getMessage()
            ]);
        }
    }
}
