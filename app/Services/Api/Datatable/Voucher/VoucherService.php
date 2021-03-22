<?php

namespace App\Services\Api\Datatable\Voucher;

use DB;
use Datatables;
use App\Models\Voucher;
use Illuminate\Http\Request;

class VoucherService
{
    public function handle(Request $request)
    {
        $companyId = $request->company_id;
        $vouchers = DB::table('vouchers')
                        ->leftJoin('partners', 'vouchers.partner_id', '=', 'partners.id')
                        ->orderBy('vouchers.voucher_date', 'DESC');

        if (is_null($companyId)) {
            $vouchers->where('vouchers.company_id', null)->select(['voucher_number', 'voucher_date', 'is_posted', 'vouchers.id', 'partners.partner_name']);
        } else {
            $vouchers->where('vouchers.company_id', $companyId)->select(['voucher_number', 'voucher_date', 'is_posted', 'vouchers.id', 'partners.partner_name']);
        }
                        

        return Datatables::of($vouchers)
                ->addColumn('rekan', function ($vouchers) {
                    if (false == is_null($vouchers->partner_name)) {
                        return $vouchers->partner_name;
                    } else {
                        return '';
                    }
                })
                ->addColumn('voucher_date', function ($vouchers) {
                    return date('d-m-Y', strtotime($vouchers->voucher_date));
                })
                ->addColumn('is_posted', function ($vouchers) {
                    $val_status = Voucher::getPostedOfVoucher((int)$vouchers->is_posted);
                    $class = Voucher::getPostedColorVoucher((int)$vouchers->is_posted);
                    return '<span class="badge badge-soft-'.$class.'">'.$val_status.'</span>';
                })
                ->addColumn('action', function ($vouchers) {
                    return view('datatable.voucher.link-action', compact('vouchers'));
                })
                ->addColumn('total_amount', function ($vouchers) {
                    return view('datatable.voucher.variable', compact('vouchers'));
                })
                ->rawColumns(['is_posted', 'action', 'total_amount'])
                ->make();
    }
}
