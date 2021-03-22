<?php

namespace App\Services\Api\Admin\User\ListByCompany;

use App\User;
use Carbon\Carbon;
use App\Models\Company;
use League\Fractal\Manager;
use Illuminate\Http\Request;
use League\Fractal\Resource\Collection;

class GetDataUser
{
    public static function handle(Request $request)
    {
        $user = User::with(['company'])->where('company_id', $request->company_id)->get();

        if (is_null($user))
            abort('Data user not found');
        
        if ($user->count() < 1) {
            $request->request->add([
                'data_response' => []
            ]);
            return true;
        }

        $userArray = $user->toArray();
            
        $fractal = new Manager();
        $resource = new Collection($userArray, function(array $pi) {
            return [
                'id' => (int) $pi['id'],
                'text' => $pi['name']
            ];
        });

        $array = $fractal->createData($resource)->toArray();
        $request->request->add([
            'data_response' => $array['data']
        ]);
    }
}
