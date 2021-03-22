<?php

namespace App\Http\Controllers\Finance;

use DB;
use App\User;
use App\Models\Invoice;
use Illuminate\Http\Request;
use App\Models\InvoiceDetail;
use App\Models\TransactionTemp;
use App\Models\TransactionDetail;
use App\Http\Controllers\Controller;
use App\Models\Partner;
use Illuminate\Support\Facades\Auth;
use App\Models\TransactionDetailTemp;

class InvoiceController extends Controller
{
    public function __construct()
    {
        $this->middleware('finance.collect.supporting.data.before.create.invoice', ['only' => ['create', 'edit']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $partner_id = null;
        $urlDatatable = route('api.datatable.invoice.route');
        $isAdmin = Auth::user()->hasRole(['Super Admin', 'Ops Admin']);

        return view('finance.invoice.index', compact('urlDatatable', 'partner_id', 'isAdmin'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $collectDataForInvoice = app('request')->data_collect_for_invoice;

        return view('finance.invoice.create', [
            'data_partner' => $collectDataForInvoice['data_partners'],
            'data_products' => $collectDataForInvoice['data_products'],
            'data_products_2' => $collectDataForInvoice['data_products_2'],
            'data_aset_accounts' => $collectDataForInvoice['data_aset_accounts'],
            'invoice_number' => $collectDataForInvoice['invoice_number']
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
        $invoice = Invoice::with(['invoice_details'])->find($id);

        if (is_null($invoice))
            return redirect()->back()->with('info', 'Id not found');

        $invoice_detail = $invoice->invoice_details->toArray();
        $company = $invoice->company;
        $partner = $invoice->partner;

        // get financial manager at company
        $financialManager = User::role('Finance Manager')->where('company_id', $company->id)->first();

        return view('finance.invoice.show', compact(['invoice', 'invoice_detail', 'company', 'partner', 'financialManager']));
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
        $invoice = Invoice::with(['invoice_details'])->find($id);


        if (is_null($invoice))
            return redirect()->back()->with('info', 'Id not found');


        if ('yes' == $invoice->is_posted)
            return redirect()->route('finance.invoices.index')->with('info', 'Invoice has been posted');


        $detail_invoice = $invoice->invoice_details;
        $collectDataForInvoice = app('request')->data_collect_for_invoice;

        return view('finance.invoice.edit', [
            'data_partner' => $collectDataForInvoice['data_partners'],
            'data_products' => $collectDataForInvoice['data_products'],
            'data_products_2' => $collectDataForInvoice['data_products_2'],
            'data_products_db' => $collectDataForInvoice['data_products_db'],
            'data_aset_accounts' => $collectDataForInvoice['data_aset_accounts'],
            'invoice' => $invoice,
            'detail_invoice' => $detail_invoice,
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
        $invoice = \App\Models\Invoice::find($id);

        if (is_null($invoice))
            return redirect()->route('finance.invoices.index')->with('info', 'Invoice not found');

        DB::beginTransaction();

        try {

            // update invoice
            $invoice->is_posted = 'yes';
            $invoice->save();


            // get data user company
            $userCompany = \App\User::where('id', Auth::user()->id)->first();
            if (is_null($userCompany))
                throw new \Exception('User not found');


            // move transaction from temp to ordinary
            $transactionTemp = \App\Models\TransactionTemp::where('model_id', $invoice->id)
                                    ->where('model_type', '\App\Models\Invoice')
                                    ->where('company_id', $userCompany->company_id)
                                    ->first();


            if (is_null($transactionTemp))
                throw new \Exception('Transaksi invoice tidak ditemukan');


            $transactionDetailTemps = \App\Models\TransactionDetailTemp::where('transaction_temp_id', $transactionTemp->id)->get();
            if ($transactionDetailTemps->count() < 1)
                throw new \Exception('Transaksi detail invoice tidak ditemukan');


            $transaction = \App\Models\Transaction::create([
                'uuid' => $transactionTemp->uuid,
                'date' => $transactionTemp->date,
                'model_id' => $transactionTemp->model_id,
                'model_type' => $transactionTemp->model_type,
                'reference_number' => $transactionTemp->reference_number,
                'description' => $transactionTemp->description,
                'company_id' => $transactionTemp->company_id
            ]);


            foreach ($transactionDetailTemps as $key => $value) {
                TransactionDetail::create([
                    'date' => $value->date,
                    'debit_amount' => $value->debit_amount,
                    'credit_amount' => $value->credit_amount,
                    'transaction_id' => $transaction->id,
                    'account_id' => $value->account_id,
                    'value_rate' => $value->value_rate,
                    'exchange_rate_from' => $value->exchange_rate_from,
                    'exchange_rate_to' => $value->exchange_rate_to,
                    'transaction_id' => $transaction->id
                ]);
            }


            $transactionTemp->forceDelete();
            $transactionDetailTemps->each->forceDelete();

            DB::commit();
            return redirect()->route('finance.invoices.index')->with('info', 'Invoice has been posted');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->route('finance.invoices.index')->with('info', $e->getMessage());
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
        $invoice = \App\Models\Invoice::find($id);


        if (is_null($invoice))
            return redirect()->route('finance.invoices.index')->with('info', 'Invoice tidak ditemukan');


        if ('yes' == $invoice->is_posted)
            return redirect()->route('finance.invoices.index')->with('info', 'Invoice has been posted, call your administrator if you want destroy it');


        DB::beginTransaction();


        try {

            // get data user company
            $userCompany = \App\User::where('user_id', Auth::user()->id)->first();
            if (is_null($userCompany))
                throw new \Exception('User not found');


            // get transaction temp by invoice id
            $transaction = \App\Models\TransactionTemp::where('company_id', $userCompany->company_id)
                            ->where('model_id', $invoice->id)
                            ->where('model_type', '\App\Models\Invoice')
                            ->first();

            // delete transsaction & invoice
            TransactionDetailTemp::where('transaction_temp_id', $transaction->id)->delete();
            TransactionTemp::where('id', $transaction->id)->delete();
            InvoiceDetail::where('invoice_id', $invoice->id)->delete();
            Invoice::where('id', $invoice->id)->delete();

            DB::commit();
            return redirect()->route('finance.invoices.index')->with('info', 'Invoice has been deleted');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->route('finance.invoices.index')->with('info', $e->getMessage());
        }
    }

    /**
     *
     *
     * @param  int  $id partner
     * @return \Illuminate\Http\Response
     */
    public function invoicespartner(Request $request, $partner_id)
    {
        $urlDatatable = route('api.datatable.invoice.route');
        $isAdmin = Auth::user()->hasRole(['Super Admin', 'Ops Admin']);
        if (!$isAdmin) {
            $check_user_company = Auth::user()->company_id;
            $check_partner = Partner::withoutTrashed()->where('id', $partner_id)->first();
            if ($check_partner->company_id != $check_user_company) {
                return redirect()->route('home');
            }
        }

        return view('finance.invoice.index', compact('urlDatatable', 'partner_id', 'isAdmin'));
    }
}
