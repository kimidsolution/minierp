<?php

namespace App\Services\Api\Datatable\Invoice;

use App\User;
use Datatables;
use Carbon\Carbon;
use App\Models\Invoice;
use Illuminate\Http\Request;

class InvoiceService
{
    public function handle(Request $request)
    {
        $get_data = Invoice::with(['partner', 'user']);
        if (is_null($request->is_admin)) {
            $userId = $request->header('user_id');
            $user = User::where('id', $userId)->first();
            $get_data->where('company_id', $user->company_id);
        }
        if (!is_null($request->partner_id)) {
            $get_data->where('partner_id', $request->partner_id);
        }
        $invoice = $get_data->get();

        return Datatables::of($invoice)
            ->addColumn('datehuman', function ($invoice) {
                return $invoice->date->toFormattedDateString();
            })
            ->addColumn('duedatehuman', function ($invoice) {
                return $invoice->due_date->toFormattedDateString();
            })
            ->addColumn('final_amount', function ($invoice) {
                return view('datatable.invoice.variable', compact('invoice'));
            })
            ->addColumn('action', function ($invoice) {
                return view('datatable.invoice.link-action', compact('invoice'));
            })
            ->rawColumns(['action'])
            ->make(true);
    }
}
