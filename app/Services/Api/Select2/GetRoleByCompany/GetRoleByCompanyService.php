<?php

namespace App\Services\Api\Select2\GetRoleByCompany;

use App\Models\Role;
use Illuminate\Http\Request;

class GetRoleByCompanyService
{
    public function handle(Request $request)
    {
        $companyId = $request->company_id;
        $companyIdAdmin = config('sempoa.admin.company_id');


        if ($companyId == $companyIdAdmin) {
            $roles = Role::whereIn('name', config('sempoa.admin.user_role_allowed'))->get(['id', 'name as text'])->toArray();
        } else {
            $roles = Role::whereNotIn('name', config('sempoa.admin.user_role_allowed'))->get(['id', 'name as text'])->toArray();
        }

        
        return response()->json($roles);
    }
}
