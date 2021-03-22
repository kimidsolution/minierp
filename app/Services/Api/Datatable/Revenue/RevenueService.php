<?php

namespace App\Services\Api\Datatable\Revenue;

use Datatables;
use Carbon\Carbon;
use App\User;
use Illuminate\Http\Request;
use App\Models\Revenue;

class RevenueService
{
    public function handle(Request $request)
    {
        $userId = $request->header('user_id');
        $check_user = User::where('id', $userId)->first();
        $revenue = Revenue::where('company_id', $check_user->company_id)->get();

        return Datatables::of($revenue)
            ->addColumn('datehuman', function ($revenue) {
                return $revenue->date->toFormattedDateString();
            })
            ->addColumn('action', function ($revenue) {
                return view('datatable.revenue.link-action', compact('revenue'));
            })
            ->addColumn('amount', function ($revenue) {
                return view('datatable.revenue.variable', compact('revenue'));
            })
            ->rawColumns(['action'])
            ->make(true);
    }
}
