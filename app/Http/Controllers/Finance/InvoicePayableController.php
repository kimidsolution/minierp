<?php

namespace App\Http\Controllers\Finance;

use DB;
use Auth;
use App\Models\User;
use App\Models\Account;
use App\Models\Company;
use App\Models\Invoice;
use App\Models\Partner;
use App\Models\InvoiceTax;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Models\TransactionDetail;
use App\Http\Controllers\Controller;
use App\Models\FinanceConfiguration;

class InvoicePayableController extends Controller
{
    public function __construct()
    {
        $this->middleware('finance.collect.supporting.data.before.create.invoice.payable', ['only' => ['create', 'edit']]);
    }

    private function getAccountConfigurationCode() {
        return [
            FinanceConfiguration::CODE_ACCOUNT_DP_INVOICE_PAYABLE,
            FinanceConfiguration::CODE_ACCOUNT_VAT_OUT,
            FinanceConfiguration::CODE_ACCOUNT_INCOME_TAX23_PREPAID,
            FinanceConfiguration::CODE_ACCOUNT_OPERATIONAL_EXPENSES,
            FinanceConfiguration::CODE_ACCOUNT_TRADE_PAYABLE,
            FinanceConfiguration::CODE_ACCOUNT_VAT_IN,
            FinanceConfiguration::CODE_ACCOUNT_INCOME_TAX23_PAYABLE,
        ];
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $partner_id = null;
        $list_company = [];
        $urlDatatable = route('api.datatable.invoice.payable.route');
        $isAdmin = Auth::user()->hasRole(['Super Admin', 'Ops Admin']);
        if ($isAdmin) {
            $list_company = Company::whereNull('deleted_at')->where('status', Company::STATUS_ACTIVE)->orderBy('company_name', 'asc')->get();
        }

        return view('finance.invoice.payable.index',
            compact('urlDatatable', 'isAdmin', 'list_company', 'partner_id')
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

        $collectDataForInvoice = app('request')->data_collect_for_invoice_payable;
        $config_code = $this->getAccountConfigurationCode();

        return view('finance.invoice.payable.create', [
            'isAdmin' => $isAdmin,
            'list_company' => $list_company,
            'data_partner' => $collectDataForInvoice['data_partners'],
            'data_account' => $collectDataForInvoice['data_accounts'],
            'invoice_number' => $collectDataForInvoice['invoice_number'],
            'data_company' => $collectDataForInvoice['data_company'],
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
        //? find id
        $invoice = Invoice::with('invoice_details')->where('id', $id)->first();

        if (is_null($invoice))
            return redirect()->back()->with('info', 'Id not found');

        $invoice_detail = $invoice->invoice_details->toArray();
        $company = $invoice->company;
        $partner = $invoice->partner;

        // get info invoice tax
        $inv_tax = [];
        $invoice_tax = InvoiceTax::where('invoice_id', $id)->get();
        foreach ($invoice_tax as $key => $details) {
            $account = Account::where('id', $details->account_id)->where('company_id', $company->id)->first();
            $finance_config_tax23 = app('data.helper')->getFinanceConfigurationActiveBySpec(
                $company->id, FinanceConfiguration::CODE_ACCOUNT_INCOME_TAX23_PREPAID
            );
            $account_tax23 = app('data.helper')->getFinanceConfigurationDetailAccount($finance_config_tax23);
            if (!is_null($account_tax23)) $tax_name = ($account->account_name == $account_tax23->account_name) ? 'pph23' : 'ppn';
            else $tax_name = 'ppn';
            $inv_tax[$tax_name] =  $details->amount;
        }

        // get financial manager at company
        $financialManager = User::role('Finance Manager')->where('company_id', $company->id)->first();

        return view('finance.invoice.payable.show', compact(['invoice', 'invoice_detail', 'company', 'partner', 'financialManager', 'inv_tax']));
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
        $invoice = Invoice::with(['invoice_details', 'invoice_taxes'])->find($id);

        if (is_null($invoice))
            return redirect()->back()->with('info', 'Id not found');

        $invoice_detail = $invoice->invoice_details->toArray();
        $company = $invoice->company;
        $partner = $invoice->partner;

        // collect data company
        $list_company = [];
        $isAdmin = Auth::user()->hasRole(['Super Admin', 'Ops Admin']);
        if ($isAdmin) {
            $list_company = Company::whereNull('deleted_at')->where('status', Company::STATUS_ACTIVE)->orderBy('company_name', 'asc')->get();
        }

        // get info invoice tax
        $inv_tax = [];
        $invoice_tax = InvoiceTax::where('invoice_id', $id)->get();
        foreach ($invoice_tax as $key => $details) {
            $account = Account::where('id', $details->account_id)->where('company_id', $company->id)->first();
            $finance_config_tax23 = app('data.helper')->getFinanceConfigurationActiveBySpec(
                $company->id, FinanceConfiguration::CODE_ACCOUNT_INCOME_TAX23_PREPAID
            );
            $account_tax23 = app('data.helper')->getFinanceConfigurationDetailAccount($finance_config_tax23);
            if (!is_null($account_tax23)) $tax_name = ($account->account_name == $account_tax23->account_name) ? 'pph23' : 'ppn';
            else $tax_name = 'ppn';
            $inv_tax[$tax_name] =  $details->amount;
        }

        // collect invoice payable
        $collectDataForInvoice = app('request')->data_collect_for_invoice_payable;
        $config_code = $this->getAccountConfigurationCode();

        return view('finance.invoice.payable.edit', [
            'isAdmin' => $isAdmin,
            'list_company' => $list_company,
            'list_partner' => $collectDataForInvoice['data_partners'],
            'data_company' => $company,
            'data_invoice' => $invoice,
            'data_invoice_detail' => $invoice_detail,
            'inv_tax_json' => json_encode($inv_tax),
            'invoice_json' => json_encode($invoice),
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
     *
     *
     * @param  int  $id partner
     * @return \Illuminate\Http\Response
     */
    public function invoicespartner(Request $request, $partner_id)
    {
        $list_company = [];
        $urlDatatable = route('api.datatable.invoice.payable.route');
        $isAdmin = Auth::user()->hasRole(['Super Admin', 'Ops Admin']);
        if (!$isAdmin) {
            $check_user_company = Auth::user()->company_id;
            $check_partner = Partner::withoutTrashed()->where('id', $partner_id)->first();
            if ($check_partner->company_id != $check_user_company) {
                return redirect()->route('home');
            }
        } else {
            $list_company = Company::whereNull('deleted_at')->where('status', Company::STATUS_ACTIVE)->orderBy('name', 'asc')->get();
        }

        return view('finance.invoice.payable.index',
            compact('urlDatatable', 'isAdmin', 'list_company', 'partner_id')
        );
    }

    /**
     * posted invoice.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function posted($id)
    {
        $invoice = Invoice::find($id);

        if (is_null($invoice))
            return redirect()->route('finance.invoices.payable.index')->with('info', 'Invoice not found');

        DB::beginTransaction();

        try {

            // update invoice
            $invoice->is_posted = Invoice::POSTED_YES;
            $invoice->save();


            // get data user company
            $userCompany = User::where('id', Auth::user()->id)->first();
            if (is_null($userCompany))
                throw new \Exception('User not found');

            // check admin or not
            $isAdmin = Auth::user()->hasRole(['Super Admin', 'Ops Admin']);

            // get info transaction
            $transactionData = Transaction::where('model_id', $invoice->id)
                                    ->where('model_type', '\App\Models\Invoice')
                                    ->where('company_id', ($isAdmin) ? $invoice->company_id : $userCompany->company_id)
                                    ->first();

            if (is_null($transactionData))
                throw new \Exception('Invoice transaction is not found');


            $transactionDetail = TransactionDetail::where('transaction_id', $transactionData->id)->get();
            if ($transactionDetail->count() < 1)
                throw new \Exception('Invoice transaction detail is not found');

            // update transaction to posted
            $transactionData->transaction_status = Transaction::STATUS_POSTED;
            $transactionData->save();

            DB::commit();
            return redirect()->route('finance.invoices.payable.index')->with('info', 'Invoice has been posted');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->route('finance.invoices.payable.index')->with('info', $e->getMessage());
        }
    }
}
