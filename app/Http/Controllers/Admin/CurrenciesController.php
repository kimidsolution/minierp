<?php

namespace App\Http\Controllers\Admin;

use App\Models\Currency;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class CurrenciesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $urlDatatable = route('api.datatable.currencies.route');
        return view('admin.currencies.index', compact('urlDatatable'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.currencies.create');
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
            //! validation
            app()->make('App\Http\Requests\CurrencyStoreRequest');

            $currency = new Currency;
            $currency->currency_name = $request->currency_name;
            $currency->currency_code = $request->currency_code;
            $currency->iso_code = $request->iso_code;
            $currency->symbol = $request->symbol;
            $currency->save();

            return redirect('admin/currencies')->with('info', 'Success add currency');
        } catch (\Exception $e) {
            if ($e instanceof ValidationException) {
                $errors = $e->validator->errors()->toArray();
                return redirect()->back()->withErrors($errors);
            }
            return redirect()->back()->with('info', $e->getMessage());
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
        //? find id
        $currency = Currency::find($id);


        if (is_null($currency))
            return redirect()->back()->with('info', 'Id not found');


        return view('admin.currencies.edit', compact('currency'));
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
            //? find id
            $currency = Currency::find($id);
            if (is_null($currency)) return redirect()->back()->with('info', 'Id not found');
            //! validation
            app()->make('App\Http\Requests\CurrencyUpdateRequest');

            //? update currency
            $currency->currency_name = $request->currency_name;
            $currency->currency_code = $request->currency_code;
            $currency->iso_code = $request->iso_code;
            $currency->symbol = $request->symbol;
            $currency->save();

            return redirect('admin/currencies')->with('info', 'Success update currency');
        } catch (\Exception $e) {
            if ($e instanceof ValidationException) {
                $errors = $e->validator->errors()->toArray();
                return redirect()->back()->withErrors($errors);
            }
            return redirect()->back()->with('info', $e->getMessage());
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
