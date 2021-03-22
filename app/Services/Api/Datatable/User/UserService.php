<?php

namespace App\Services\Api\Datatable\User;

use Auth;
use Datatables;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class UserService
{
    public function handle(Request $request)
    {
        $where = [];
        $isAdmin = (bool) $request->is_admin;
        $userId = $request->user_id;

        if (true == $isAdmin) {
            $where['company_id'] = "0" != $request->company_id ? $request->company_id : null;
        } else {
            $where['company_id'] = $request->company_id;
        }

        if (1 == $request->only_active) {
            $users = User::with(['company'])->where($where)->get();
        } else {
            $users = User::withTrashed()->with(['company'])->where($where)->get();
        }

        return Datatables::of($users)
            ->addColumn('action', function($users) use ($isAdmin, $userId) {
                $html = null;
                $user = User::find($userId);
                $role = $user->getRoleNames()->first();

                if (true == $isAdmin || 'Company Admin' == $role) {
                    $html = '<a href="' . route('admin.reset.user.password', ['id' => Crypt::encryptString($users->id)]) . '" class="btn btn-sm btn-gradient-warning waves-effect waves-light" title="klik untuk reset password"><i class="fas fa-key ml-1"></i></a>';
                }

                if ('Super Admin' == $role) {
                    $html .= view('datatable.user.btndelete', compact('users'));
                }

                return $html;
            })
            ->addColumn('namecolour', function($users) {
                return '<a class="title-link-table"
                    title="Detail & Edit"
                    data-id="'.$users->id.'"
                    href="' . route('admin.users.edit', ['user' => $users->id]) . '"
                >'
                    . $users->name .
                '</a>';
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
            ->addColumn('role', function($users) {
                return $users->getRoleNames()->first();
            })
            ->rawColumns(['action', 'namecolour', 'user_status', 'role'])
            ->make(true);
    }
}
