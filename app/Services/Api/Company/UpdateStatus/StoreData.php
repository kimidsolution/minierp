<?php

namespace App\Services\Api\Company\UpdateStatus;

use DB;
use App\Models\Company;
use App\User;
use Illuminate\Http\Request;

class StoreData
{
    public static function handle(Request $request)
    {
        DB::beginTransaction();
        try { 
            //? try get match status company based on model status const
            $match_status = Company::validateStatusCompany($request->status_id);
            if (!$match_status) {
                abort(400, 'Company status does not match');
            }
            
            //? update data company
            $company = Company::find($request->company_id);
            $user = User::find($request->user_id);
            $company->status = $request->status_id;
            $company->updated_by = $user->name;

            if (!$company->save()) {
                abort(400, 'Update status not succesfull');
            }

            //? it works if change status to deleted
            if ($request->status_id == Company::STATUS_DELETED) {  
                $company->delete();
            }

            if (is_null($company)) {
                abort(400, 'Company not found');
            }

            //? it works if change status to active
            if ($request->status_id == Company::STATUS_ACTIVE) {                
                //? update status pic in company
                $user_update = User::where('company_id', $request->company_id)->where('id', $company->pic_id)->first();
                $user_update->status = User::STATUS_ACTIVE;
                $user_update->updated_by = $user->name;
                $user_update->save();
            }
            
            DB::commit();

            return response()->api(true, [], $company);
        } catch (\Exception $e) {
            DB::rollback();
            
            if ($e instanceof ValidationException) {
                $errors = $e->validator->errors()->toArray();
                return response()->api(true, [], $errors);
            }
            
            return response()->api(true, [], $e->getMessage());
        }
    }
}
