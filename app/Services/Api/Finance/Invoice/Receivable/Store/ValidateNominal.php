<?php

namespace App\Services\Api\Finance\Invoice\Receivable\Store;

use Illuminate\Http\Request;

class ValidateNominal
{
    public static function handle(Request $request)
    {
        $nominalRemaining = $request->nominal_remaining;
        $nominalDownPayment = $request->nominal_down_payment;
        $nominalSubTotalAmount = $request->nominal_sub_total_amount;

        if ($nominalRemaining != ($nominalSubTotalAmount - $nominalDownPayment))
            abort('400', 'Calculation not match');

        if (0 >= $nominalRemaining)
            abort(400, 'Nilai sisa tidak boleh kurang atau sama dengan 0');
    }
}
