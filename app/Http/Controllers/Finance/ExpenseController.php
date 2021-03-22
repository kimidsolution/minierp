<?php

namespace App\Http\Controllers\Finance;

use DB;
use Auth;
use App\Models\Expense;
use Illuminate\Http\Request;
use App\Models\TransactionTemp;
use App\Models\TransactionDetail;
use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\Company;
use App\Models\Transaction;
use App\Models\TransactionDetailTemp;

class ExpenseController extends Controller
{
    public function __construct()
    {
        $this->middleware('finance.collect.supporting.data.before.create.expense', ['only' => ['create', 'edit']]);
    }

    private function getIsAdmin()
    {
        return Auth::user()->hasRole(['Super Admin', 'Ops Admin']);
    }

    private function getListCompany()
    {
        $list_company = [];
        $isAdmin = $this->getIsAdmin();
        if ($isAdmin) {
            $list_company = Company::whereNull('deleted_at')->where('status', Company::STATUS_ACTIVE)->orderBy('company_name', 'asc')->get();
        }
        return $list_company;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $isAdmin = $this->getIsAdmin();
        $list_company = $this->getListCompany();
        $urlDatatable = route('api.datatable.expense.index.route');

        return view('finance.expense.index', compact('urlDatatable', 'isAdmin', 'list_company'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $isAdmin = $this->getIsAdmin();
        $list_company = $this->getListCompany();
        $collectDataForExpense = app('request')->data_collect_for_expense;

        return view('finance.expense.create', [
            'isAdmin' => $isAdmin,
            'list_company' => $list_company,
            'expense_reference_number' => $collectDataForExpense['expense_reference_number'],
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

            $result = app('finance.expense.store.service')->handle($request);

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
        $isAdmin = $this->getIsAdmin();
        $list_company = $this->getListCompany();

        $expense = Expense::find($id);
        if (is_null($expense)) return redirect()->back()->with('info', 'Id expense not found');

        $transaction = Transaction::where('model_id', $expense->id)->first();
        if (is_null($transaction)) return redirect()->back()->with('info', 'Id transaction not found');

        $expense_detail = TransactionDetail::select(DB::raw('
            transaction_details.*,
            accounts.account_name
        '))
        ->join('accounts', 'accounts.id', '=', 'transaction_details.account_id')
            ->where('transaction_details.transaction_id', $transaction->id)
            ->whereIn('accounts.account_type', [Account::EXPENSES, Account::OTHER_EXPENSES])
            ->first();
        if (is_null($expense_detail)) return redirect()->back()->with('info', 'Id transaction detail not found');

        if (!$isAdmin) {
            if ($expense->company_id != Auth::user()->company_id) {
                return redirect()->back()->with('info', 'You dont have a permission');
            }
        }
        if ($expense->is_posted) {
            return redirect()->route('finance.expenses.index')->with('info', 'Expense has been posted');
        }

        return view('finance.expense.edit', [
            'expense' => $expense,
            'expense_detail' => $expense_detail,
            'transaction' => $transaction,
            'isAdmin' => $isAdmin,
            'list_company' => $list_company
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
}
