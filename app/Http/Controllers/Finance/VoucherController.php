<?php

namespace App\Http\Controllers\Finance;

use DB;
use Auth;
use App\User;
use App\Models\Company;
use App\Models\Voucher;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Models\VoucherDetail;
use App\Http\Controllers\Controller;
use App\Models\FinanceConfiguration;

class VoucherController extends Controller
{
    public function __construct()
    {
        $this->middleware('finance.collect.supporting.data.before.create.voucher', ['only' => ['create']]);
        $this->middleware('finance.collect.supporting.data.before.update.voucher', ['only' => ['edit']]);
    }

    private function getAccountConfigurationCode() {
        return [
            FinanceConfiguration::CODE_ACCOUNT_ASSETS_VOUCHER,
            FinanceConfiguration::CODE_ACCOUNT_OTHER_EXPENSE_VOUCHER,
            FinanceConfiguration::CODE_ACCOUNT_AR_SALES,
            FinanceConfiguration::CODE_ACCOUNT_TRADE_PAYABLE,
            FinanceConfiguration::CODE_ACCOUNT_REVENUE_RECEIVED_IN_ADVANCED,
            FinanceConfiguration::CODE_ACCOUNT_RECEIVABLE
        ];
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $list_company = [];
        $urlDatatable = route('api.datatable.voucher.route');
        $isAdmin = Auth::user()->hasRole(['Super Admin', 'Ops Admin']);
        if ($isAdmin) {
            $list_company = Company::whereNull('deleted_at')->where('status', Company::STATUS_ACTIVE)->orderBy('company_name', 'asc')->get();
        }

        return view('finance.voucher.index',
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

        $collectDataForVoucher = app('request')->data_collect_for_voucher;
        $config_code = $this->getAccountConfigurationCode();

        return view('finance.voucher.create', [
            'isAdmin' => $isAdmin,
            'list_company' => $list_company,
            'data_aset_accounts' => $collectDataForVoucher['data_aset_accounts'],
            'voucher_number' => $collectDataForVoucher['voucher_number'],
            'data_expense_accounts_json' => $collectDataForVoucher['data_expense_accounts_json'],
            'config_code' => $config_code
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //? find id voucher
        $voucher = Voucher::with(['partner', 'invoices', 'company'])->find($id);

        if (is_null($voucher))
            return redirect()->back()->with('info', 'Id not found');

        // get info invoice
        $invoices = $voucher->invoices->toArray();
        // get info company
        $company = $voucher->company;
        // get info partner
        $partner = $voucher->partner;


        // get info voucher invoice
        $transaction_id = null;
        // get value voucher detail
        $voucher_detail = VoucherDetail::with('invoice', 'voucher_detail_expenses')->where('voucher_id', $voucher->id)->get();
        // get financial manager at company
        $financialManager = User::role('Finance Manager')->where('company_id', $company->id)->first();

        return view('finance.voucher.show', compact([
            'voucher',
            'invoices',
            'company',
            'partner',
            'voucher_detail',
            'financialManager'
        ]));
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
        $voucher = Voucher::with(['voucher_details.voucher_detail_expenses', 'company', 'partner'])->find($id);
        if (is_null($voucher))
            return redirect()->back()->with('info', 'Id not found');

        $voucher_detail = $voucher->voucher_details->toArray();
        $company = $voucher->company;
        $partner = $voucher->partner;

        // dd($voucher, $voucher_detail, $company, $partner);

        $list_company = [];
        $isAdmin = Auth::user()->hasRole(['Super Admin', 'Ops Admin']);

        if ($isAdmin) {
            $list_company = Company::whereNull('deleted_at')->where('status', Company::STATUS_ACTIVE)->orderBy('company_name', 'asc')->get();
        }

        $collectDataForVoucher = app('request')->data_collect_for_voucher;
        $config_code = $this->getAccountConfigurationCode();

        return view('finance.voucher.edit', [
            'isAdmin' => $isAdmin,
            'list_company' => $list_company,
            'data_aset_accounts' => $collectDataForVoucher['data_aset_accounts'],
            'data_expense_accounts_json' => $collectDataForVoucher['data_expense_accounts_json'],
            'voucher' => $voucher,
            'voucher_json' => json_encode($voucher),
            'voucher_detail' => $voucher_detail,
            'company' => $company,
            'config_code' => $config_code
        ]);
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

    /**
     * posted invoice.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function posted($id)
    {
        $voucher = Voucher::where('id', $id)->where('is_posted', Voucher::POSTED_NO)->first();


        if (is_null($voucher))
            return redirect()->route('finance.vouchers.index')->with('info', 'Voucher tidak ditemukan / sudah diposted');


        DB::beginTransaction();


        try {


            // update voucher
            Voucher::where('id', $id)->update([
                'is_posted' => Voucher::POSTED_YES,
                'updated_by' => Auth::user()->name
            ]);


            // update transaction
            Transaction::where('model_id', $id)->where('model_type', '\App\Models\Voucher')->update([
                'transaction_status' => Transaction::STATUS_POSTED,
                'updated_by' => Auth::user()->name
            ]);

            DB::commit();
            return redirect()->route('finance.vouchers.index')->with('info', 'Voucher has been posted');

        } catch (\Exception $e) {

            DB::rollback();
            return redirect()->route('finance.vouchers.index')->with('info', $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete($id)
    {
        // get data voucher
        $voucher = Voucher::find($id);


        if (is_null($voucher))
            return redirect()->route('finance.vouchers.index')->with('info', 'Voucher tidak ditemukan');


        if ('yes' == $voucher->is_posted)
            return redirect()->route('finance.vouchers.index')->with('info', 'Voucher has been posted, call your administrator if you want destroy it');


        DB::beginTransaction();

        try {

            // get data user company
            $userCompany = \App\Models\UsersCompany::where('user_id', Auth::user()->id)->first();
            if (is_null($userCompany))
                throw new \Exception('User company not found');


            $transactionTemp = TransactionTemp::where('model_id', $id)->where('model_type', '\App\Models\Voucher')
                                ->where('company_id', $userCompany->company_id)
                                ->first();


            if (is_null($transactionTemp))
                throw new \Exception('Transaksi voucher tidak ditemukan');


            // delete transaction & voucher
            TransactionDetailTemp::where('transaction_temp_id', $transactionTemp->id)->delete();
            TransactionTemp::where('id', $transactionTemp->id)->delete();
            Voucher::where('id', $voucher->id)->delete();

            DB::commit();
            return redirect()->route('finance.vouchers.index')->with('info', 'Voucher has been deleted');

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->route('finance.vouchers.index')->with('info', $e->getMessage());
        }
    }
}
