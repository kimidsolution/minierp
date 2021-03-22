<?php

namespace App\Http\Controllers\Finance;

use App\Models\Account;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\AccountBalance;
use App\Models\Company;
use App\Services\Api\Account\Lists\ListsParent\GetDataParent;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\ValidationException;

class AccountController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $urlDatatable = route('api.datatable.account.route');
        $isAdmin = Auth::user()->hasRole(['Super Admin', 'Ops Admin']);
        $companies = Company::where('status', Company::STATUS_ACTIVE)
            ->get();
        $user = Auth::user();

        return view(
            'finance.account.index',
            compact('urlDatatable', 'isAdmin', 'companies', 'user')
        );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (Gate::allows('manageUser')) {
            $list_company = [];
            $isAdmin = Auth::user()->hasRole(['Super Admin', 'Ops Admin']);
            if ($isAdmin) {
                $list_company = Company::whereNull('deleted_at')->where('status', Company::STATUS_ACTIVE)->orderBy('company_name', 'asc')->get();
            }
            return view('finance.account.create', compact('isAdmin', 'list_company'));
        }

        return redirect()->route('home');
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
            $nominal_balance = preg_replace("/[^0-9-]+/", "", $request->balance_nominal);
            app()->make('App\Http\Requests\AccountStoreRequest');
            $account                        = new Account();
            $account->company_id            = $request->company_id;
            $account->account_type          = $request->account_type;
            $account->balance               = $request->balance;
            $account->parent_account_id     = $request->parent_account_id;
            $account->level                 = $request->level;
            $account->account_name          = $request->name;
            $account->account_code          = $request->account_type."-".$request->account_code;
            if (!is_null($request->account_text)) {
                $account->account_text = $request->account_text;
            }

            if ($account->save()) {
                $account_balances               = new AccountBalance();
                $account_balances->account_id   = $account->id;
                $account_balances->is_closed    = false;
                $account_balances->balance_date = date("Y-m-d", strtotime($request->balance_date));
                $account_balances->debit_amount = $request->balance == 'debit' ? $nominal_balance : 0;
                $account_balances->credit_amount    = $request->balance == 'credit' ? $nominal_balance : 0;
                $account_balances->description  = $request->description;
                $account_balances->save();
            }
            DB::commit();
            return redirect('finance/accounts')->with('info', 'Success add account');
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
    public function edit(Request $request, $id)
    {
        if (Gate::allows('manageUser')) {
            $account = Account::find($id);
            if (is_null($account)) {
                return redirect()->back()->with('info', 'Id not found');
            }
            $isAdmin = Auth::user()->hasRole(['Super Admin', 'Ops Admin']);
            if (!$isAdmin) {
                if (
                    $account->level == 1 ||
                    $account->company_id != Auth::user()->company_id
                ) {
                    return redirect()->back()->with('info', 'You dont have a permission');
                }
            }
            $request->request->add([
                'company_id' => $account->company_id,
                'account_type' => $account->account_type,
                'except_id' => $account->id
            ]);
            GetDataParent::handle($request);
            $list_parent_existing = $request->data_response;
            $last_account_balance = AccountBalance::where('account_id', $account->id)->orderBy('balance_date', 'desc')->first();
            if (is_null($last_account_balance)) {
                return redirect()->back()->with('info', 'Id balance not found');
            }
            return view('finance.account.edit', compact('isAdmin', 'account', 'last_account_balance', 'list_parent_existing'));
        }

        return redirect()->route('home');
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
            $nominal_balance = preg_replace("/[^0-9-]+/", "", $request->balance_nominal);
            app()->make('App\Http\Requests\AccountStoreRequest');
            $account = Account::find($id);
            $account->company_id = $request->company_id;
            $account->account_type = $request->account_type;
            $account->balance = $request->balance;
            $account->parent_account_id = $request->parent_account_id;
            $account->level = $request->level;
            $account->account_name = $request->name;
            $account->account_code = $request->account_type."-".$request->account_code;
            if (!is_null($request->account_text)) {
                $account->account_text = $request->account_text;
            }

            if ($account->save()) {
                $account_balances = AccountBalance::find($request->account_balance_id);
                $account_balances->account_id = $id;
                $account_balances->is_closed = false;
                $account_balances->balance_date = date("Y-m-d", strtotime($request->balance_date));
                $account_balances->updated_by = Auth::user()->name;
                if ($request->balance == "debit"){
                    $account_balances->debit_amount = $nominal_balance;
                }
                if ($request->balance == "credit") {
                    $account_balances->credit_amount = $nominal_balance;
                }
                if (!is_null($request->description)) {
                    $account_balances->description = $request->description;
                }
                $account_balances->save();
            }
            DB::commit();
            return redirect('finance/accounts')->with('info', 'Success update account');
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
