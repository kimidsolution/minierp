<?php

namespace App\Services\Api\Datatable\Company;

use Datatables;
use App\Models\Company;
use Illuminate\Http\Request;

class CompanyService
{
    public function handle(Request $request)
    {
        $company = Company::whereNull('deleted_at')->orderBy('created_at', 'desc')->get();
        if ($request->is_active) {
            if ($request->is_active == "true") {
                $company = Company::whereNull('deleted_at')->where('status', '!=', Company::STATUS_DELETED)->orderBy('created_at', 'desc')->get();
            } else {
                $company = Company::withTrashed()->orderBy('created_at', 'desc')->get();
            }
        }
        return Datatables::of($company)
            ->addIndexColumn()
            ->addColumn('action', function($row) {
                $btn = '<button style="margin: 5px;" class="btn btn-sm btn-gradient-danger waves-effect waves-light sa-information btn-delete" title="Delete" data-id="'.$row->id.'" data-value="'.$row->company_name.'">
                    <i class="far fa-trash-alt ml-1"></i>
                </button>';
                $btn .= '<a style="margin: 5px;" href="'.route('admin.users-company', ['id' => $row->id]).'" class="btn btn-sm btn-gradient-primary waves-effect waves-light sa-information" title="Users">
                    <i class="fa fa-users ml-1"></i>
                </a>';

                return $btn;
            })
            ->addColumn('company_name', function($row) {
                return '<a style="color: #3ab5c6;" href="' . route('admin.companies.edit', ['company' => $row->id]) . '" title="Detail & Edit">'
                    . $row->company_name .
                '</a>';
            })
            ->addColumn('city', function($row) {
                return $row->city;
            })
            ->addColumn('pic', function($row) {
                return !is_null($row->pic) ? $row->pic->name : '';
            })
            ->addColumn('type', function($row) {
                $val_type = Company::getTypeOfCompany((int)$row->type);
                $class = Company::getTypeColorCompany((int)$row->type);
                return '<span class="badge badge-soft-'.$class.'">'.$val_type.'</span>';
            })
            ->addColumn('status', function($row) {
                $val_status = Company::getStatusCompany((int)$row->status);
                $class = Company::getStatusColorCompany((int)$row->status);
                return '<span class="badge badge-soft-'.$class.'">'.$val_status.'</span>';
            })
            ->rawColumns(['action', 'company_name', 'status', 'type'])
            ->make(true);
    }
}
