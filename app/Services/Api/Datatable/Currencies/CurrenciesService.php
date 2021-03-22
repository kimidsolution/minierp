<?php

namespace App\Services\Api\Datatable\Currencies;

use Datatables;
use App\Models\Currency;
use Illuminate\Http\Request;

class CurrenciesService
{
    public function handle(Request $request)
    {
        $get_data = Currency::whereNull('deleted_at');
        if ($request->is_active) {
            if ($request->is_active == "true") {
                $get_data = Currency::whereNull('deleted_at');
            } else {
                $get_data = Currency::withTrashed();
            }
        }
        $currencies = $get_data->orderBy('created_at', 'desc')->get();

        return Datatables::of($currencies)
            ->addColumn('action', function ($currencies) {
                return view('datatable.currency.link-action', compact('currencies'));
            })
            ->addColumn('name', function($row) {
                return '<a class="title-link-table"
                    title="Detail & Edit"
                    data-id="'.$row->id.'"
                    href="' . route('admin.currencies.edit', ['currency' => $row->id]) . '"
                >'
                    . $row->currency_name .
                '</a>';
            })
            ->rawColumns(['action', 'name'])
            ->make(true);
    }
}
