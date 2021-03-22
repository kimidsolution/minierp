<?php

namespace App\Http\Controllers\Finance;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function indexGeneralLedger(Request $request)
    {
        $list_company = [];
        $company =  app('data.helper')->getUserCompany();
        $isAdmin = Auth::user()->hasRole(['Super Admin', 'Ops Admin']);
        if ($isAdmin) {
            $list_company = Company::whereNull('deleted_at')->where('status', Company::STATUS_ACTIVE)->orderBy('company_name', 'asc')->get();
        }

        return view('finance.report.general-ledger.index', compact('company', 'isAdmin', 'list_company'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function indexProfitLoss(Request $request)
    {
        $list_company = [];
        $company =  app('data.helper')->getUserCompany();
        $isAdmin = Auth::user()->hasRole(['Super Admin', 'Ops Admin']);
        if ($isAdmin) {
            $list_company = Company::whereNull('deleted_at')->where('status', Company::STATUS_ACTIVE)->orderBy('company_name', 'asc')->get();
        }
        return view('finance.report.profit-loss.index', compact('company', 'isAdmin', 'list_company'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function indexTrialBalance(Request $request)
    {
        $list_company = [];
        $company =  app('data.helper')->getUserCompany();
        $isAdmin = Auth::user()->hasRole(['Super Admin', 'Ops Admin']);
        if ($isAdmin) {
            $list_company = Company::whereNull('deleted_at')->where('status', Company::STATUS_ACTIVE)->orderBy('company_name', 'asc')->get();
        }
        return view('finance.report.trial-balance.index', compact('company', 'isAdmin', 'list_company'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function indexJournal(Request $request)
    {
        $list_company = [];
        $company =  app('data.helper')->getUserCompany();
        $isAdmin = Auth::user()->hasRole(['Super Admin', 'Ops Admin']);
        if ($isAdmin) {
            $list_company = Company::whereNull('deleted_at')->where('status', Company::STATUS_ACTIVE)->orderBy('company_name', 'asc')->get();
        }

        return view('finance.report.journal.index', compact('company', 'isAdmin', 'list_company'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function indexJournalTransaction($id)
    {
        $data = Transaction::with('transaction_details')->find($id);
        if (is_null($data)) return redirect()->back()->with('info', 'Transaction not found');
        $account_name = $data->transaction_details[0]->account->account_name;
        $company =  app('data.helper')->getUserCompany();

        $isAdmin = Auth::user()->hasRole(['Super Admin', 'Ops Admin']);
        if (!$isAdmin) {
            if ($data->company_id != Auth::user()->company_id) return redirect()->back()->with('info', 'You dont have a permission');
        }

        return view('finance.report.journal.transaction.index', compact('company', 'isAdmin', 'data', 'account_name'));
    }
}
