<?php

namespace App\Http\Controllers\Master;

use Str;
use Auth;
use App\Models\Product;
use App\Models\Company;
use App\Models\UsersCompany;
use Illuminate\Http\Request;
use App\Models\ProductCategory;
use App\Http\Controllers\Controller;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $list_company = [];
        $urlDatatable = route('api.datatable.product.route');
        $isAdmin = Auth::user()->hasRole(['Super Admin', 'Ops Admin']);
        if ($isAdmin) {
            $list_company = Company::whereNull('deleted_at')
                ->where('status', Company::STATUS_ACTIVE)
                ->orderBy('company_name', 'asc')->get();
        }

        return view(
            'master.product.index',
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
            $list_company = Company::whereNull('deleted_at')
                ->where('status', Company::STATUS_ACTIVE)
                ->orderBy('company_name', 'asc')->get();
        }

        return view('master.product.create', compact('isAdmin', 'list_company'));
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
            app()->make('App\Http\Requests\ProductStoreRequest');

            //? create product
            $product = new Product;
            $product->id                    = Str::uuid();
            $product->product_name          = $request->product_name;
            $product->product_category_id   = $request->product_category;
            $product->sku                   = $request->sku;
            $product->price                 = ($request->price == "") ? 0 : preg_replace("/[^0-9-]+/", "", $request->price);
            $product->status                = Product::STATUS_ACTIVE;
            $product->type                  = $request->type;
            $product->created_by            = Auth::user()->name;
            $product->updated_by            = Auth::user()->name;
            $product->company_id            = is_null($request->company_id) ? Auth::user()->company_id : $request->company_id;
            $product->save();

            return redirect('master/product')->with('info', 'Success add product');
        } catch (\Exception $e) {
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
        //? find id
        $product = Product::find($id);
        
        if (is_null($product))
            return redirect()->back()->with('info', 'Id not found');
        
        $list_company = [];
        $isAdmin = Auth::user()->hasRole(['Super Admin', 'Ops Admin']);
        if ($isAdmin) {
            $list_company = Company::whereNull('deleted_at')
                ->where('status', Company::STATUS_ACTIVE)
                ->orderBy('company_name', 'asc')->get();
        }

        return view('master.product.edit', compact(['product', 'list_company', 'isAdmin']));
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
            $product = Product::find($id);
            
            if (is_null($product))
                return redirect()->back()->with('info', 'Id not found');

            //? update product
            $product->product_name = $request->product_name;
            $product->sku = $request->sku;
            $product->price = ($request->price == "") ? 0 : preg_replace("/[^0-9-]+/", "", $request->price);
            $product->product_category_id = $request->product_category;
            $product->type = $request->type;
            $product->save();

            return redirect('master/product')->with('info', 'Success update product');

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
