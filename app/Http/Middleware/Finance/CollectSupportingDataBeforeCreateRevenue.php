<?php

namespace App\Http\Middleware\Finance;

use Auth;
use Closure;
use App\User;
use App\Models\Account;
use App\Models\Revenue;
use Illuminate\Support\Facades\Gate;

class CollectSupportingDataBeforeCreateRevenue
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
        $lastRevenue = Revenue::latest()->where('company_id', $user->company_id)->first();
        $nextIdRevenue = (is_null($lastRevenue)) ? 0 : $lastRevenue->id;
        $numberSequenceExpense = sprintf('%06d', intval($nextIdRevenue) + 1);
        
        //? collect data accounts revenue
        $accountTypeRevenue = \App\Models\AccountType::where('name', 'pendapatan')->where('company_id', $user->company_id)->first();
        $revenueAccounts = Account::where('company_id', $user->company_id)->where('account_type_id', $accountTypeRevenue->id)->get();
        $revenueAccountIds = $revenueAccounts->pluck('name', 'id')->toArray();
        $revenueAccountDb = ($revenueAccounts->count() > 0) ? $revenueAccounts->toArray() : [];

        $revenueFormatted[] = ['id' => '', 'text' => ''];
        foreach ($revenueAccountDb as $key => $value) {
            $revenueFormatted[] = ['id' => $value['id'], 'text' => $value['name']];
        }
        $revenueAccountsFormat = $revenueFormatted;

        //? collect data asset accounts
        $accountTypeActiva = \App\Models\AccountType::where('name', 'harta')->where('company_id', $user->company_id)->first();
        $assetAccounts = Account::where('company_id', $user->company_id)->where('account_type_id', $accountTypeActiva->id)->where('level', 2)->get();
        $assetAccountIds = $assetAccounts->pluck('name', 'id')->toArray();
        
        //TODO generate invoice number
        $revenueNumber = $random->prefix('rev')->numeric()->start(100)->end(200)->get() . $numberSequenceExpense;

        $request->request->add([
            'data_collect_for_revenue' => [
                'user' => $user,
                'data_revenue_accounts' => $revenueAccountIds,
                'data_revenue_accounts_json' => json_encode($revenueAccountsFormat),
                'revenue_reference_number' => $revenueNumber,                
                'data_aset_accounts' => $assetAccountIds,
            ]
        ]);

        return $next($request);
    }
}
