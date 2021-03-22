<?php

namespace App\Services\Api\Datatable\Voucher\Payable;

use App\User;
use Datatables;
use Carbon\Carbon;
use App\Models\Voucher;
use Illuminate\Http\Request;

class VoucherPayableService
{
    public function handle(Request $request)
    {
        if ('false' !=  $request->is_active) {
            $getData = Voucher::where('type', Voucher::TYPE_PAYABLE);
        } else {
            $getData = Voucher::withTrashed()->where('type', Voucher::TYPE_PAYABLE);
        }

        if (!is_null($request->company_id)) {
            $getData->whereHas('invoices', function ($q) use ($request) {
                $q->where('company_id', $request->company_id);
            });
        }

        $vouchers = $getData->orderBy('created_at', 'desc')->get();

        return Datatables::of($vouchers)
            ->addColumn('datehuman', function ($vouchers) {
                return $vouchers->date->toFormattedDateString();
            })
            ->addColumn('type', function($row){
                $val_type = Voucher::getTypeOfVoucher((int)$row->type);
                $class = Voucher::getTypeColorVoucher((int)$row->type);
                return '<span class="badge badge-soft-'.$class.'">'.$val_type.'</span>';
            })
            ->addColumn('is_posted', function($row){
                $val_status = Voucher::getPostedOfVoucher((int)$row->is_posted);
                $class = Voucher::getPostedColorVoucher((int)$row->is_posted);
                return '<span class="badge badge-soft-'.$class.'">'.$val_status.'</span>';
            })
            ->addColumn('action', function ($vouchers) {
                return view('datatable.voucher.payable.link-action', compact('voucher'));
            })
            ->rawColumns(['action', 'is_posted', 'type', 'datehuman'])
            ->make(true);
    }
}
