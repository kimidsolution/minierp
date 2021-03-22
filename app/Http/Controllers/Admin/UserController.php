<?php

namespace App\Http\Controllers\Admin;

use DB;
use Str;
use Auth;
use Exception;
use App\Models\Role;
use App\Models\User;
use App\Models\Company;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use App\Models\UserEmailVerification;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Validation\ValidationException;
use Illuminate\Contracts\Encryption\DecryptException;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = User::find(Auth::id());
        $companyId = Auth::user()->company_id;
        $urlDatatable = route('api.datatable.user.route');
        $isAdmin = $user->hasRole(['Super Admin', 'Ops Admin']);
        $isAdminInteger = (int) $isAdmin;
        $listCompany = Company::where('status', Company::STATUS_ACTIVE)->orderBy('company_name', 'asc')->get();

        return view('admin.user.index', compact('urlDatatable', 'companyId', 'isAdmin', 'listCompany', 'user', 'isAdminInteger'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('manageUser');

        $dataCompany = [];
        $companies = Company::get();
        $user = User::with(['company'])->find(Auth::id());
        $isSuperOpsAdmin = $user->hasRole(['Super Admin', 'Ops Admin']);

        if (false == $isSuperOpsAdmin) {
            $dataCompany = [$user->company->id => $user->company->name];
        } else {
            $dataCompany = ($companies->count() > 0) ? $companies->pluck('company_name', 'id')->toArray() : [];
        }

        return view('admin.user.create', compact('dataCompany', 'isSuperOpsAdmin', 'user'));
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
            // app()->make('App\Http\Requests\UserStoreRequest');

            print_r($request->company_id);

            // validate role
            $roleAdminAllowed = config('sempoa.admin.user_role_allowed');
            $roleNameRequest = Role::find($request->role)->name;

            if (config('sempoa.admin.company_id') == $request->company_id) {
                if (false == in_array($roleNameRequest, $roleAdminAllowed)) {
                    throw new \Exception('Role does not match with company');
                }
            } else {
                if (true == in_array($roleNameRequest, $roleAdminAllowed)) {
                    throw new \Exception('Role does not match with company');
                }
            }

            //? create user
            $user = new User;
            $user->name = $request->name;
            $user->email = $request->email;
            $user->company_id = $request->company_id;
            $user->status = User::STATUS_NEW;
            $user->job = $request->job;
            $user->title = $request->title;
            $user->phone_number = $request->phone_number;
            $user->address = $request->address;
            $user->password = bcrypt('password');
            $user->created_by = Auth::user()->name;
            $user->save();

            $role = Role::find($request->role);
            $user->assignRole($role->name);

            if ('production' == config('app.env')) {
                UserEmailVerification::create([
                    'name' => $request->name,
                    'email' => $request->email,
                    'status' => 0,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
                
                dispatch(new \App\Jobs\SendEmailVerification([
                    'email' => $request->email,
                    'url' => 'https://youtube.com'
                ]));   
            }

            DB::commit();

            return redirect()->route('admin.users.index')->with('info', 'Success add user');

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
        $this->authorize('manageUser');

        //? find id
        $user = User::with(['company'])->find($id);

        if (is_null($user))
            return redirect()->back()->with('info', 'User tidak ditemukan');

        // data role
        if (strpos($user->company->name, 'Sempoa Prima Teknologi') !== false) {
            $roles = Role::whereIn('id', [1, 2])->get()->pluck('name', 'name')->toArray();
        } else {
            $roles = Role::whereNotIn('id', [1, 2])->get()->pluck('name', 'name')->toArray();
        }

        $isSuperOpsAdmin = $user->hasRole(['Super Admin', 'Ops Admin']);
        $userRole = $user->getRoleNames();
        return view('admin.user.edit', compact('user', 'roles', 'userRole', 'isSuperOpsAdmin'));
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
            $user = User::find($id);

            if (is_null($user))
                return redirect()->back()->with('info', 'User tidak ditemukan');

            //! validation
            app()->make('App\Http\Requests\UserUpdateRequest');

            $emailExist = User::whereNotIn('id', [$id])->where('email', $request->email)->first();
            if ($emailExist) {
                return redirect()->back()->with('info', 'Email sudah digunakan');
            }

            $phoneNumberExist = User::whereNotIn('id', [$id])->where('phone_number', $request->phone_number)->first();
            if ($phoneNumberExist) {
                return redirect()->back()->with('info', 'Phone number sudah digunakan');
            }

            //? update user
            \App\User::where('id', $id)->update([
                'name' => $request->name,
                'email' => $request->email,
                'phone_number' => $request->phone_number,
                'title' => $request->title,
                'job' => $request->job,
                'address' => $request->address,
                'updated_by' => Auth::user()->name
            ]);

            $user = User::where('email', $request->email)->first();
            $user->syncRoles([$request->role]);
            
            return redirect()->route('admin.users.index')->with('info', 'Success update user');

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

    /**
     *
     *
     * @param  int  $id company
     * @return \Illuminate\Http\Response
     */
    public function usercompany(Request $request, $id)
    {
        $isAdmin = Auth::user()->hasRole(['Super Admin', 'Ops Admin']);
        if ($isAdmin) {
            $checkCompany = Company::find($id);
            if ($checkCompany) {
                $companyId = $id;
                $urlDatatable = route('api.datatable.user.company.route');
                return view('admin.user.company', compact('urlDatatable', 'companyId'));
            }
        }

        return redirect()->back();
    }

    /**
     * View user reset password
     *
     * @return \Illuminate\Http\Response
     */
    public function resetpassword()
    {
        return view('admin.user.resetpassword');
    }

    /**
     * User update password
     *
     * @return \Illuminate\Http\Response
     */
    public function updatepassword(Request $request)
    {
        try {

            //! validation
            app()->make('App\Http\Requests\UserUpdatePasswordRequest');

            $user = User::where('id', Auth::user()->id)->first();

            // check old password
            if (false == Hash::check($request->old_password, $user->password))
                throw new Exception("Password lama anda tidak tepat");

            if ($request->new_password != $request->password)
                throw new Exception("Konfirmasi password baru tidak sesuai");

            $user = User::where('id', Auth::user()->id)->first();
            $user->password = bcrypt($request->password);
            $user->save();

            return redirect()->back()->with('info', 'Success update password');

        } catch (Exception $e) {
            if ($e instanceof ValidationException) {
                $errors = $e->validator->errors()->toArray();
                return redirect()->back()->withErrors($errors);
            }
            return redirect()->back()->with('info', $e->getMessage());
        }
    }

    /**
     * View admin reset user password
     *
     * @return \Illuminate\Http\Response
     */
    public function adminresetpassworduser(Request $request, $id)
    {
        $this->authorize('manageUser');
        
        try {

            $decrypted = Crypt::decryptString($id);
            $idDecrypted = $decrypted;
            return view('admin.user.adminresetpassword', compact('idDecrypted'));

        } catch (DecryptException $e) {
            return redirect()->back()->with('info', 'Invalid user id');
        }
    }

    /**
     * Admin update user password
     *
     * @return \Illuminate\Http\Response
     */
    public function adminupdatepassworduser(Request $request)
    {
        try {

            if ($request->new_password != $request->password)
                throw new Exception("Konfirmasi password baru tidak sesuai");

            $user = User::where('id', $request->user_id)->first();

            // check old password
            if (false == Hash::check($request->old_password, $user->password))
                throw new Exception("Password lama anda tidak tepat");

            $user->password = bcrypt($request->password);
            $user->save();

            return redirect()->back()->with('info', 'Success update password');

        } catch (Exception $e) {
            if ($e instanceof ValidationException) {
                $errors = $e->validator->errors()->toArray();
                return redirect()->back()->withErrors($errors);
            }
            return redirect()->back()->with('info', $e->getMessage());
        }
    }
}
