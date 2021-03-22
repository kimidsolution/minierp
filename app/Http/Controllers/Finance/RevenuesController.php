<?php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RevenuesController extends Controller
{
    public function __construct()
    {
        $this->middleware('finance.collect.supporting.data.before.create.revenue', ['only' => ['create']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $urlDatatable = route('api.datatable.revenue.route');

        return view('finance.revenue.index', compact('urlDatatable'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $collectDataForRevenue = app('request')->data_collect_for_revenue;

        return view('finance.revenue.create', [
            'data_aset_accounts' => $collectDataForRevenue['data_aset_accounts'],
            'data_revenue_accounts' => $collectDataForRevenue['data_revenue_accounts_json'],
            'revenue_reference_number' => $collectDataForRevenue['revenue_reference_number'],
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {

            $result = app('finance.revenue.store.service')->handle($request);

            if ($request->ajax()) {
                return $result;
            }

        } catch (\Exception $e) {

            if ($request->ajax()) {
                abort(400, $e->getMessage());
            }
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
