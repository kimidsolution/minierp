<?php

namespace App\Services\Finance\Revenue\Store;

use Illuminate\Support\Str;
use Illuminate\Http\Request;

class SetDataCreateRevenue
{
    public static function handle(Request $request)
    {
        $dataUser = $request->user;

        
        // set data create revenue
        $dataRevenue = [
            'uuid' => (string) Str::uuid(),
            'date' => $request->date,
            'number' => $request->reference_number,
            'amount' => $request->amount,
            'description' => $request->description,
            'company_id' => $dataUser['company_id'],
            'paid_to' => $request->paid_to,
            'created_by' => $request->user_id,
            'created_at' => now(),
            'updated_at' => now()
        ];


        // set data  detail revenue
        $items = $request->items;
        $dataRevenueDetails = [];

        foreach ($items as $key => $value) {
            $dataRevenueDetails[$key]['nominal'] = $value['nominal'];
            $dataRevenueDetails[$key]['account_id'] = $value['account_id'];
            $dataRevenueDetails[$key]['created_at'] = now();
            $dataRevenueDetails[$key]['updated_at'] = now();
        }


        $request->request->add([
            'data_create_revenue' => $dataRevenue,
            'data_create_revenue_detail' => $dataRevenueDetails
        ]);
    }
}
