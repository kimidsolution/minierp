<?php

namespace App\Services\Api\Finance\Transaction\Check\CheckRefNumber;

use App\Models\Transaction;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class CheckRefNumberService
{
    public function handle(Request $request)
    {
        try {
            app()->make('App\Http\Requests\CheckCodeAccountRequest');
            $ref_number = preg_replace('/\s+/', '', $request->code);

            $get_data = Transaction::where('company_id', $request->company_id)->where('reference_number', $ref_number);
            if ($request->except_id) $get_data->whereNotIn('id', [$request->except_id]);

            $ref_number_transaction = $get_data->get();
            $request->request->add([
                'is_unique' => count($ref_number_transaction) == 0,
                'data_response' => $ref_number_transaction
            ]);
            $message = $request->is_unique ? "Reference number available" : "Reference number has been registered";
            return response()->api($request->is_unique, [], $request->data_response, $message);
        } catch (QueryException $e) {
            return response()->json([
                'status' => false,
                'messages' => $e->getMessage()
            ]);
        }
    }
}
