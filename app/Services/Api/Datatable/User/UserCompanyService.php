<?php

namespace App\Services\Api\Datatable\User;

use Auth;
use Datatables;
use App\Models\User;
use Illuminate\Http\Request;

class UserCompanyService
{
    public function handle(Request $request)
    {
        $users = User::with(['company'])->where('company_id', $request->company_id)->get();

        return Datatables::of($users)
            ->addColumn('action', function() {
                $btn = '<a href="#" class="btn btn-sm btn-gradient-danger waves-effect waves-light sa-information" title="klik untuk hapus"><i class="far fa-trash-alt ml-1"></i></a>';
                return $btn;
            })
            ->addColumn('email', function($users) {
                return '<a href="' . route('admin.users.edit', ['user' => $users->id]) . '" title="klik untuk detail dan edit">' . $users->email . '</a>';
            })
            ->addColumn('user_status', function($users) {
                switch ($users->status) {
                    case '1':
                        $status = 'Active';
                        break;
                    case '2':
                        $status = 'Inactive';
                        break;
                    default:
                        $status = 'New';
                        break;
                }

                return $status;
            })
            ->rawColumns(['action', 'email', 'user_status'])
            ->make(true);
    }
}