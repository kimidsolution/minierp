<?php

namespace App\Http\Controllers\Configuration;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\FinanceConfiguration;
use Illuminate\Support\Facades\Auth;

class FinanceConfigurationController extends Controller
{

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
    public function indexAccounts(Request $request)
    {
        $list_company = [];
        $company =  app('data.helper')->getUserCompany();
        $isAdmin = $this->getIsAdmin();
        $list_company = $this->getListCompany();
        return view('configuration.finance.accounts', compact('company', 'isAdmin', 'list_company'));
    }

    public function createAccounts()
    {
        $isAdmin = $this->getIsAdmin();
        $list_company = $this->getListCompany();

        return view('configuration.finance.create-accounts', compact('isAdmin', 'list_company'));
    }

    public function editAccounts($id)
    {
        $isAdmin = $this->getIsAdmin();
        $list_company = $this->getListCompany();
        $configuration = FinanceConfiguration::find($id);
        if (!$isAdmin) {
            if ($configuration->company_id != Auth::user()->company_id) {
                return redirect()->back()->with('info', 'You dont have a permission');
            }
        }

        return view('configuration.finance.edit-accounts', compact('configuration', 'isAdmin', 'list_company'));
    }
}
