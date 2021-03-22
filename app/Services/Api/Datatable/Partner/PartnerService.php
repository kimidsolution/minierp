<?php

namespace App\Services\Api\Datatable\Partner;

use Datatables;
use App\Models\Partner;
use Illuminate\Http\Request;

class PartnerService
{
    public function handle(Request $request)
    {
        $get_partner = Partner::whereNull('deleted_at');
        if ($request->is_active) {
            if ($request->is_active == "true") {
                $get_partner = Partner::whereNull('deleted_at');
            } else {
                $get_partner = Partner::withTrashed();
            }
        }
        $get_partner->where('company_id', $request->company_id ? $request->company_id : null);
        $partner = $get_partner->orderBy('created_at', 'desc')->get();

        return Datatables::of($partner)
            ->addColumn('action', function ($partner) {
                return view('datatable.partner.link-action', compact('partner'));
            })
            ->addColumn('partner_name', function($row){
                return '<a class="title-link-table"
                    title="Detail & Edit"
                    data-id="'.$row->id.'"
                    href="' . route('master.partner.edit', ['partner' => $row->id]) . '"
                >'
                    . $row->partner_name .
                '</a>';
            })
            ->addColumn('type', function($partner) {
                if ($partner->is_client == true && $partner->is_vendor == false) {
                    return 'Customer';
                }  else if ($partner->is_client == false && $partner->is_vendor == true) {
                    return 'Vendor';
                } else {
                    return 'Both';
                }
            })
            ->addColumn('status', function($partner) {
                $title = is_null($partner->deleted_at) ? 'Active' : 'Not Active';
                $class = is_null($partner->deleted_at) ? 'success' : 'danger';
                return '<span class="badge badge-soft-'.$class.'">'.$title.'</span>';
            })
            ->rawColumns(['action', 'partner_name', 'status'])
            ->make(true);
    }
}
