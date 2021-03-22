<?php

namespace App\Services\Api\Datatable\Account;

use Datatables;
use App\Models\Account;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AccountService
{
    public function handle(Request $request)
    {
        $get_data = Account::whereNull('deleted_at');
        if ($request->is_active) {
            if ($request->is_active == "true") {
                $get_data = Account::whereNull('deleted_at');
            } else {
                $get_data = Account::withTrashed();
            }
        }
        $get_data->where('company_id', $request->company_id ? $request->company_id : null);
        $account = $get_data->orderBy('account_code')->get();

        return Datatables::of($account)->addIndexColumn()
            ->addColumn('action', function($row){
                $btn = '<button style="margin: 5px;" class="btn btn-sm btn-gradient-danger waves-effect waves-light sa-information btn-delete" title="Delete" data-id="'.$row->id.'" data-name="'.$row->account_name.'">
                    <i class="far fa-trash-alt ml-1"></i>
                </button>';

                return $btn;
            })
            ->addColumn('account_name', function($row) {
                if ($row->level > 1) {
                    return '<a class="title-link-table" href="' . route('finance.accounts.edit', ['account' => $row->id]) . '" title="klik untuk detail dan edit">'
                        . $row->naming .
                    '</a>';
                }
                return $row->naming;
            })
            ->addColumn('balance', function($row) {
                $title = ($row->balance == 'debit') ? 'Debit' : 'Credit';
                $class = ($row->balance == 'debit') ? 'info' : 'primary';
                return '<span class="badge badge-soft-'.$class.'">'.$title.'</span>';
            })
            ->rawColumns(['action', 'account_name', 'balance'])
            ->make(true);
    }
}
