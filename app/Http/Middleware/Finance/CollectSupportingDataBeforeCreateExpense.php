<?php

namespace App\Http\Middleware\Finance;

use Closure;
use App\User;
use App\Models\Account;
use App\Models\Expense;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class CollectSupportingDataBeforeCreateExpense
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
        $userCompany = User::where('id', Auth::user()->id)->first();
        $lastExpense = Expense::latest()->where('company_id', $userCompany->company_id)->first();
        $nextIdExpense = (is_null($lastExpense)) ? 0 : $lastExpense->id;
        $numberSequenceExpense = sprintf('%06d', intval($nextIdExpense) + 1);

        //? collect data accounts expense
        $expenseAccounts = Account::select(
                DB::raw("CONCAT(
                    account_code, ' - ',
                    IF ((account_text IS NOT NULL OR account_text != ''), account_text, account_name)
                ) AS account_naming"),'id'
            )
            ->where('company_id', $userCompany->company_id)
            ->whereIn('account_type', [Account::EXPENSES, Account::OTHER_EXPENSES])
            ->orderBy('account_code', 'asc')
            ->get();

        $expenseAccountIds = $expenseAccounts->pluck('account_naming', 'id')->toArray();
        $expenseAccountDb = ($expenseAccounts->count() > 0) ? $expenseAccounts->toArray() : [];

        $expenseFormatted[] = ['id' => '', 'text' => ''];
        foreach ($expenseAccountDb as $key => $value) {
            $expenseFormatted[] = ['id' => $value['id'], 'text' => $value['account_naming']];
        }
        $expenseAccountsFormat = $expenseFormatted;

        //? collect data asset accounts
        $assetAccounts = Account::select(
                DB::raw("CONCAT(
                    account_code, ' - ',
                    IF ((account_text IS NOT NULL OR account_text != ''), account_text, account_name)
                ) AS account_naming"),'id'
            )
            ->where('company_id', $userCompany->company_id)
            ->where('account_type', Account::ASSETS)
            ->orderBy('account_code', 'asc')
            ->get();

        $assetAccountIds = $assetAccounts->pluck('account_naming', 'id')->toArray();

        //TODO generate invoice number
        $expenseNumber = $random->prefix('exp')->numeric()->start(100)->end(200)->get() . $numberSequenceExpense;

        $request->request->add([
            'data_collect_for_expense' => [
                'user_company' => $userCompany,
                'data_expense_accounts' => $expenseAccountIds,
                'data_expense_accounts_json' => json_encode($expenseAccountsFormat),
                'expense_reference_number' => $expenseNumber,
                'data_asset_accounts' => $assetAccountIds,
            ]
        ]);

        return $next($request);
    }
}
