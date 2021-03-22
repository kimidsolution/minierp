<?php

namespace App\Services\Api\Select2\GetListInvoiceCompany;

use App\Models\Invoice;
use App\Models\Transaction;
use League\Fractal\Manager;
use Illuminate\Http\Request;
use League\Fractal\Resource\Collection;

class GetListInvoiceService
{
    public function handle(Request $request)
    {
        $query = Invoice::where('is_posted', Invoice::POSTED_YES);
        if (!is_null($request->company_id)) $query->where('company_id', $request->company_id);
        if (!is_null($request->type)) $query->where('type', $request->type == Transaction::TYPE_RECEIVABLE ? Invoice::TYPE_RECEIVABLE : Invoice::TYPE_PAYABLE);
        $invoices = $query->orderBy('invoice_date', 'desc')->get();

        if ($invoices->count() > 0) {
            $invoicesArray = $invoices->toArray();
            $fractal = new Manager();
            $resource = new Collection($invoicesArray, function(array $data) {
                return [
                    'id' => $data['id'],
                    'text' => $data['invoice_number'],
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
