<?php

namespace App\Http\Controllers\Master;

use App\Models\Partner;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Company;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class PartnerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $list_company = [];
        $urlDatatable = route('api.datatable.partner.route');
        $isAdmin = Auth::user()->hasRole(['Super Admin', 'Ops Admin']);
        if ($isAdmin) {
            $list_company = Company::whereNull('deleted_at')->where('status', Company::STATUS_ACTIVE)->orderBy('company_name', 'asc')->get();
        }

        return view(
            'master.partner.index',
            compact('urlDatatable', 'isAdmin', 'list_company')
        );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $list_company = [];
        $isAdmin = Auth::user()->hasRole(['Super Admin', 'Ops Admin']);
        if ($isAdmin) {
            $list_company = Company::whereNull('deleted_at')->where('status', Company::STATUS_ACTIVE)->orderBy('company_name', 'asc')->get();
        }
        return view('master.partner.create', compact('isAdmin', 'list_company'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        DB::beginTransaction();

        try {
            app()->make('App\Http\Requests\PartnerStoreRequest');

            $partner                    = new Partner;
            $partner->partner_name      = $request->name;
            $partner->email             = $request->email;
            $partner->fax               = $request->fax;
            $partner->company_id        = $request->company_id == null ? Auth::user()->company_id : $request->company_id;
            $partner->phone_number      = $request->phone_number;
            $partner->address           = $request->address;
            $partner->tax_id_number     = $request->tax_id_number;
            $partner->country           = $request->country;
            $partner->city              = $request->city;
            $partner->pic_name          = $request->pic_name;
            $partner->pic_email         = $request->pic_email;
            $partner->pic_phone_number  = $request->pic_phone_number;
            switch ($request->partner_status) {
                case 'vendor':
                    $partner->is_vendor = true;
                    $partner->is_client = false;
                    break;
                case 'client':
                    $partner->is_vendor = false;
                    $partner->is_client = true;
                    break;
                default:
                    $partner->is_vendor = true;
                    $partner->is_client = true;
                    break;
            }

            $partner->save();

            DB::commit();
            return redirect('master/partner')->with('info', 'Success add partner');
        } catch (\Exception $e) {
            DB::rollback();
            if ($e instanceof ValidationException) {
                $errors = $e->validator->errors()->toArray();
                return redirect()->back()->withInput()->withErrors($errors);
            }
            return redirect()->back()->withInput()->with('info', $e->getMessage());
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
        $isAdmin = Auth::user()->hasRole(['Super Admin', 'Ops Admin']);
        $partner = Partner::withTrashed()->where('id', $id)->first();
        if (!$isAdmin) {
            if ($partner->company_id != Auth::user()->company_id) {
                return redirect()->back()->with('info', 'You dont have a permission');
            }
        }

        if (is_null($partner))
            return redirect()->back()->with('info', 'Id not found');

        return view('master.partner.edit', compact(['partner', 'isAdmin']));
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
        try {
            $partner = Partner::withTrashed()->where('id', $id)->first();
            if (is_null($partner)) {
                return redirect()->back()->with('info', 'Id not found');
            }
            app()->make('App\Http\Requests\PartnerStoreRequest');

            $partner->partner_name = $request->name;
            $partner->email = $request->email;

            if (!is_null($request->fax)) $partner->fax = $request->fax;
            $partner->company_id = !is_null($request->company_id) ? $request->company_id : Auth::user()->company_id;

            $partner->phone_number = $request->phone_number;
            $partner->address = $request->address;
            $partner->tax_id_number = $request->tax_id_number;
            $partner->country = $request->country;
            $partner->city = $request->city;
            $partner->pic_name = $request->pic_name;
            $partner->pic_email = $request->pic_email;
            $partner->pic_phone_number = $request->pic_phone_number;
            switch ($request->partner_status) {
                case 'vendor':
                    $partner->is_vendor = true;
                    $partner->is_client = false;
                    break;
                case 'client':
                    $partner->is_vendor = false;
                    $partner->is_client = true;
                    break;
                default:
                    $partner->is_vendor = true;
                    $partner->is_client = true;
                    break;
            }

            $partner->save();

            DB::commit();
            return redirect('master/partner')->with('info', 'Success update partner');
        } catch (\Exception $e) {
            DB::rollback();
            if ($e instanceof ValidationException) {
                $errors = $e->validator->errors()->toArray();
                return redirect()->back()->withInput()->withErrors($errors);
            }
            return redirect()->back()->withInput()->with('info', $e->getMessage());
        }
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
