<?php

namespace App\Http\Middleware\Finance;

use Auth;
use Closure;
use App\User;
use App\Models\Account;
use App\Models\Invoice;
use App\Models\Partner;
use App\Models\Product;
use App\Models\UsersCompany;
use Illuminate\Support\Facades\Gate;

class CollectSupportingDataBeforeCreateInvoice
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
        $lastInvoice = Invoice::latest()->where('company_id', $user->company_id)->first();
        $nextIdInvoice = (is_null($lastInvoice)) ? 0 : $lastInvoice->id;
        $numberSequenceInvoice = sprintf('%06d', intval($nextIdInvoice) + 1);

        
        //? collect data partner
        $partners = Partner::where('company_id', $user->company_id)->get();
        $dataPartners = ($partners->count() > 0) ? $partners->pluck('name', 'id')->toArray() : [];


        //? collect data asset accounts
        $accountTypeActiva = \App\Models\AccountType::where('name', 'harta')->where('company_id', $user->company_id)->first();
        $assetAccounts = Account::where('company_id', $user->company_id)->where('account_type_id', $accountTypeActiva->id)->where('level', 2)->get();
        $assetAccountIds = $assetAccounts->pluck('name', 'id')->toArray();


        //? collect data products
        $products = Product::where('company_id', $user->company_id)->get();
        $productIds = ($products->count() > 0) ? $products->toArray() : [];
        $productDb = ($products->count() > 0) ? $products->pluck('name', 'id')->toArray() : [];

        $productIdFormatted[] = ['id' => '', 'text' => '', 'harga' => ''];
        foreach ($productIds as $key => $value) {
            $productIdFormatted[] = ['id' => $value['id'], 'text' => $value['name'], 'harga' => (string) $value['price']];
        }

        $productIdFormatted2 = $productIdFormatted;
        array_shift($productIdFormatted);
        $productIdFormatted3 = $productIdFormatted;
        
        //TODO generate invoice number
        $invoiceNumber = $random->prefix('inv')->numeric()->start(100)->end(200)->get() . $numberSequenceInvoice;


        $request->request->add([
            'data_collect_for_invoice' => [
                'data_products' => json_encode($productIdFormatted2),
                'data_products_2' => json_encode($productIdFormatted3),
                'data_products_db' => $productDb,
                'data_partners' => $dataPartners,
                'data_aset_accounts' => $assetAccountIds,
                'invoice_number' => $invoiceNumber
            ]
        ]);

        return $next($request);
    }
}
