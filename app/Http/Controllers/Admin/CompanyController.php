<?php

namespace App\Http\Controllers\Admin;

use App\Configurations\Finance\FinanceConfigurationDefault;
use File;
use App\User;
use App\Models\Company;
use App\Models\Currency;
use Illuminate\Http\Request;
use App\Models\ProductCategory;
use App\Imports\AccountImport;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Controllers\Controller;
use App\Models\Partner;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class CompanyController extends Controller
{
    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function index()
    {
        $urlDatatable = route('api.datatable.company.route');
        $is_admin = Auth::user()->hasRole(['Super Admin', 'Ops Admin']);

        if ($is_admin) {
            return view(
                'admin.company.index',
                compact('urlDatatable')
            );
        }

        return redirect()->back();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $currencies = Currency::whereNull('deleted_at')->get();
        return view('admin.company.create', compact('currencies'));
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

            //! validation
            app()->make('App\Http\Requests\CompanyStoreRequest');

            $uploadedFile = $request->file('logo');

            //? create company
            $company = new Company;
            $company->company_name  = $request->company_name;
            $company->brand_name    = $request->brand_name;
            $company->email         = $request->email;
            $company->phone_number  = $request->phone;
            $company->address       = $request->address;
            $company->tax_id_number = $request->tax_id_number;
            $company->fax           = $request->fax;
            $company->website       = $request->website;
            $company->vat_enabled   = ($request->vat_enabled == "true") ? true : false;
            $company->status        = Company::STATUS_NEW;
            $company->type          = $request->type;
            $company->created_by    = Auth::user()->name;
            $company->country       = $request->country;
            $company->city          = $request->city;
            $company->currency_id   = $request->currency_id;

            if ($uploadedFile !== NULL) {
                $fileExtension = $uploadedFile->getClientOriginalExtension();
                $fileName = app('string.helper')->setNamelogoCompany($request->company_name);
                $fileNameWithExtenstion = $fileName . '.' . $fileExtension;

                $request->request->add([
                    'logo' => $fileNameWithExtenstion
                ]);

                $company->logo = $fileNameWithExtenstion;

                Storage::disk('logo_company')->put(
                    $fileNameWithExtenstion,
                    File::get($uploadedFile)
                );
            }

            // proses simpan company
            if($company->save()) {
                //? create user
                $user = new User;
                $user->name         = $request->pic_name;
                $user->email        = $request->pic_email;
                $user->password     = bcrypt($request->password);
                $user->company_id   = $company->id;
                $user->phone_number = $request->pic_phone;
                $user->status       = User::STATUS_NEW;
                $user->created_by   = Auth::user()->name;
                $user->save();

                //? do update pic
                $company_update = Company::find($company->id);
                $company_update->pic_id = $user->id;
                $company_update->save();

                //? do create product category default
                $listProductCategory = config('sempoa.product.category');
                foreach ($listProductCategory as $valueCategory) {
                    ProductCategory::create([
                        'category_name'     => $valueCategory,
                        'company_id'        => $company->id,
                        'created_at'        => now(),
                        'updated_at'        => now(),
                        'created_by'        => Auth::user()->name,
                        'updated_by'        => Auth::user()->name
                    ]);
                }

                //? do create account & account balance default based on type company
                $data_send = [
                    'company_id'    => $company->id,
                    'company_type'  => $company->type,
                    'user_name'     => Auth::user()->name,
                ];
                Excel::import(new AccountImport($data_send), public_path('COA_Template_Sempoa_Update.xlsx'));

                if ($company->type == Company::TYPE_UMKM) {
                    // do finance config default
                    $finance_configuration = new FinanceConfigurationDefault($company->id);
                    $finance_configuration->execute();

                    //? do create default partner when umkm type : general customer
                    Partner::create([
                        'id'                => Str::uuid(),
                        'partner_name'      => 'General Customer',
                        'email'             => 'customer@customer.com',
                        'phone_number'      => '',
                        'address'           => 'Jakarta',
                        'tax_id_number'     => '',
                        'city'              => 'Jakarta',
                        'country'           => 'Indonesia',
                        'pic_name'          => '',
                        'pic_email'         => '',
                        'pic_phone_number'  => '',
                        'is_vendor'         => false,
                        'is_client'         => true,
                        'company_id'        => $company->id,
                        'created_by'        => 'System',
                        'updated_by'        => 'System',
                        'created_at'        => now(),
                        'updated_at'        => now()
                    ]);
                }
            }

            DB::commit();
            return redirect('admin/companies')->with('info', 'Success add company');
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
        //? find id
        $company = Company::find($id);

        if (is_null($company))
            return redirect()->back()->with('info', 'Id not found');

        // get data user
        $list_user_pic = User::where('company_id', $id)->where('id', $company->pic_id)->first();
        $data_user = User::where('company_id', $id)->get();
        $list_user = ($data_user->count() > 0) ? $data_user->pluck('name', 'id')->toArray() : [];
        $currencies = Currency::whereNull('deleted_at')->get();

        return view('admin.company.edit', compact([
            'company',
            'list_user',
            'list_user_pic',
            'currencies'
        ]));
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
        DB::beginTransaction();

        try {

            //? find id
            $company = Company::find($id);

            if (is_null($company))
                return redirect()->back()->with('info', 'Id not found');

            //! validation
            app()->make('App\Http\Requests\CompanyUpdateRequest');

            $uploadedFile = $request->file('logo');

            //? update company
            $company->company_name  = $request->company_name;
            $company->brand_name    = $request->brand_name;
            $company->email         = $request->email;
            $company->phone_number  = $request->phone;
            $company->address       = $request->address;
            $company->tax_id_number = $request->tax_id_number;
            $company->fax           = $request->fax;
            $company->website       = $request->website;
            $company->vat_enabled   = ($request->vat_enabled == "true") ? true : false;
            $company->updated_by    = Auth::user()->name;
            $company->type          = $request->type;
            $company->country       = $request->country;
            $company->city          = $request->city;
            $company->pic_id        = $request->pic_id;
            $company->currency_id   = $request->currency_id;

            if ($uploadedFile !== NULL) {
                $fileExtension = $uploadedFile->getClientOriginalExtension();
                $fileName = app('string.helper')->setNamelogoCompany($request->company_name);
                $fileNameWithExtenstion = $fileName . '.' . $fileExtension;

                $request->request->add([
                    'logo' => $fileNameWithExtenstion
                ]);

                Storage::disk('logo_company')->put(
                    $fileNameWithExtenstion,
                    File::get($uploadedFile)
                );

                $company->logo = $fileNameWithExtenstion;
            }

            $company->save();

            DB::commit();
            return redirect('admin/companies')->with('info', 'Success update company');

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
