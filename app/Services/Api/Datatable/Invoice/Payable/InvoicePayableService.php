<?php

namespace App\Services\Api\Datatable\Invoice\Payable;

use App\User;
use Datatables;
use Carbon\Carbon;
use App\Models\Invoice;
use App\Models\Partner;
use Illuminate\Http\Request;

class InvoicePayableService
{
    public function handle(Request $request)
    {
        $get_data = Invoice::with(['partner'])->where('type', Invoice::TYPE_PAYABLE)->whereNull('deleted_at');
        if ($request->is_active) {
            if ($request->is_active == "true") {
                $get_data = Invoice::with(['partner'])->where('type', Invoice::TYPE_PAYABLE)->whereNull('deleted_at');
            } else {
                $get_data = Invoice::with(['partner'])->withTrashed()->where('type', Invoice::TYPE_PAYABLE);
            }
        }

        $get_data->where('company_id', $request->company_id ? $request->company_id : null);

        if (!is_null($request->partner_id)) {
            $get_data->where('partner_id', $request->partner_id);
        }
        $invoice = $get_data->orderBy('created_at', 'desc')->get();

        return Datatables::of($invoice)
            ->addColumn('partner_name', function ($invoice) {
                $partner = Partner::find($invoice->partner_id);
                return !is_null($partner) ? $partner->partner_name : '';
            })
            ->addColumn('datehuman', function ($invoice) {
                return date('d-m-Y', strtotime($invoice->invoice_date));
            })
            ->addColumn('duedatehuman', function ($invoice) {
                return date('d-m-Y', strtotime($invoice->due_date));
            })
            ->addColumn('payment_status', function($row){
                $val_status = Invoice::getStatusOfInvoice((int)$row->payment_status);
                $class = Invoice::getStatusColorInvoice((int)$row->payment_status);
                return '<span class="badge badge-soft-'.$class.'">'.$val_status.'</span>';
            })
            ->addColumn('is_posted', function($row){
                $val_status = Invoice::getPostedOfInvoice((int)$row->is_posted);
                $class = Invoice::getPostedColorInvoice((int)$row->is_posted);
                return '<span class="badge badge-soft-'.$class.'">'.$val_status.'</span>';
            })
            ->addColumn('total_amount', function ($invoice) {
                return view('datatable.invoice.payable.variable', compact('invoice'));
            })
            ->addColumn('action', function ($invoice) {
                return view('datatable.invoice.payable.link-action', compact('invoice'));
            })
            ->rawColumns(['action', 'total_amount', 'payment_status', 'is_posted', 'duedatehuman', 'datehuman'])
            ->make(true);
    }
}
