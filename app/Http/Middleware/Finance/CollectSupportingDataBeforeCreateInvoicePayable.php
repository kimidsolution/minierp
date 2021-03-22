<?php

namespace App\Http\Middleware\Finance;

use Auth;
use Closure;
use App\User;
use App\Models\Account;
use App\Models\Invoice;
use App\Models\Partner;
use App\Models\Product;
use App\Models\Company;
use Illuminate\Support\Facades\Gate;

class CollectSupportingDataBeforeCreateInvoicePayable
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (Gate::denies('user-company'))
            return redirect()->back()->with('info', 'Forbidden!');

        $random = new \PragmaRX\Random\Random();
        $user = User::where('id', Auth::user()->id)->first();
        
        //? collect data partner for type vendor & both
        $partners = Partner::where('company_id', $user->company_id)->where(function ($query) {
            $query->where('is_vendor', '=', true)
                  ->orWhere('is_client', '=', true);
        })->where(function ($query) {
            $query->where('is_vendor', '=', true)
                  ->orWhere('is_client', '=', false);
        })->get();

        $dataPartners = ($partners->count() > 0) ? $partners->pluck('name', 'id')->toArray() : [];

        //? collect data for assets
        $assetAccounts = Account::where('company_id', $user->company_id)->where('account_type', Account::ASSETS)->where('level', 3)->get();
        $assetAccountIds = $assetAccounts->pluck('name', 'id')->toArray();

        //? set invoice number
        $lastInvoice = Invoice::latest()->where('company_id', $user->company_id)->first();
        $nextIdInvoice = (is_null($lastInvoice)) ? 0 : $lastInvoice->id;
        $numberSequenceInvoice = sprintf('%06d', intval($nextIdInvoice) + 1);
        //TODO generate invoice number
        $invoiceNumber = $random->prefix('inv')->numeric()->start(100)->end(200)->get() . $numberSequenceInvoice;

        //? collect data company
        $company = Company::find(Auth::user()->company_id);

        $request->request->add([
            'data_collect_for_invoice_payable' => [
                'data_partners' => $dataPartners,
                'data_accounts' => $assetAccountIds,
                'data_company' => $company,
                'invoice_number' => $invoiceNumber
            ]
        ]);

        return $next($request);
    }
}
