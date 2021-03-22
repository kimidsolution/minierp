<?php

namespace App\Http\Middleware\Finance;

use Auth;
use Closure;
use App\User;
use App\Models\Account;
use App\Models\Invoice;
use App\Models\Partner;
use App\Models\Product;
use App\Models\Voucher;
use App\Models\AccountType;
use App\Models\UsersCompany;
use Illuminate\Support\Facades\Gate;

class CollectSupportingDataBeforeUpdateVoucher
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

        //? collect data for assets
        $assetAccounts = Account::where('company_id', $user->company_id)->where('account_type', Account::ASSETS)->where('level', 3)->get();
        $assetAccountIds = $assetAccounts->pluck('name', 'id')->toArray();

        //? collect data asset accounts -> expense
        $expenseAccounts = Account::where('company_id', $user->company_id)->where('account_type', Account::OTHER_EXPENSES)->where('level', 3)->get();
        $expenseAccountIds = $expenseAccounts->pluck('name', 'id')->toArray();

        $request->request->add([
            'data_collect_for_voucher' => [
                'data_aset_accounts' => $assetAccountIds,
                'data_expense_accounts_json' => json_encode($expenseAccountIds),
            ]
        ]);

        return $next($request);
    }
}
