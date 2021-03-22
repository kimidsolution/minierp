<?php

namespace App\Services\Api\Datatable\Configuration;

use App\Models\FinanceConfiguration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class FinanceConfigurationAccountService
{
    public function handle(Request $request)
    {
        $get_data = DB::table('finance_configurations')->select([
            'finance_configurations.id',
            'finance_configurations.configuration_code',
            'finance_configurations.configuration_description',
            'finance_configurations.configuration_status',
            'finance_configurations.created_at',
            'finance_configurations.updated_at',
            'finance_configurations.deleted_at',
            'companies.id AS company_id',
            'companies.company_name'
        ])->join('companies', 'finance_configurations.company_id', '=', 'companies.id');

        if ($request->is_active) {
            if ($request->is_active == "true") {
                $get_data->whereNull('finance_configurations.deleted_at')
                    ->whereIn('finance_configurations.configuration_status', [FinanceConfiguration::STATUS_ACTIVE, FinanceConfiguration::STATUS_INACTIVE]);
            } else {
                $get_data->whereIn('finance_configurations.configuration_status', [FinanceConfiguration::STATUS_ACTIVE, FinanceConfiguration::STATUS_INACTIVE]);
            }
        }

        $get_data->where('finance_configurations.company_id', $request->company_id ? $request->company_id : null);
        $data = $get_data->orderBy('finance_configurations.configuration_description', 'ASC')->get();

        return DataTables::of($data)
            ->addColumn('configuration_status', function ($data) {
                return $data->configuration_status == true ? "Active" : "Not Active";
            })
            ->addColumn('last_updated', function ($data) {
                $date = !is_null($data->updated_at) ? $data->updated_at : $data->created_at;
                return date('d-m-Y', strtotime($date));
            })
            ->addColumn('details_url', function($data) {
                return url(route('api.datatable.configuration.details.route', ['id' => $data->id]));
            })
            ->addColumn('action', function ($data) {
                return view('datatable.configuration.finance.link-action-accounts', compact('data'));
            })
            ->rawColumns(['action'])
            ->make(true);
    }
}
