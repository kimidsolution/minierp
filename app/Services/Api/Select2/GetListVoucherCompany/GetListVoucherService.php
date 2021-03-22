<?php

namespace App\Services\Api\Select2\GetListVoucherCompany;

use App\Models\Transaction;
use App\Models\Voucher;
use League\Fractal\Manager;
use Illuminate\Http\Request;
use League\Fractal\Resource\Collection;

class GetListVoucherService
{
    public function handle(Request $request)
    {
        $query = Voucher::where('is_posted', Voucher::POSTED_YES);
        if (!is_null($request->company_id)) $query->where('company_id', $request->company_id);
        if (!is_null($request->type)) $query->where('voucher_type', $request->type == Transaction::TYPE_RECEIVABLE ? Voucher::TYPE_RECEIVABLE : Voucher::TYPE_PAYABLE);
        $vouchers = $query->orderBy('voucher_date', 'desc')->get();

        if ($vouchers->count() > 0) {
            $vouchersArray = $vouchers->toArray();
            $fractal = new Manager();
            $resource = new Collection($vouchersArray, function(array $data) {
                return [
                    'id' => $data['id'],
                    'text' => $data['voucher_number'],
                    'note' => $data['note']
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
