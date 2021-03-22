<?php

namespace App\Helpers;

use App\Models\Account;
use App\Models\AccountBalance;
use App\Models\Product;
use App\Models\Company;
use App\Models\FinanceConfiguration;
use App\Models\Invoice;
use App\Models\Voucher;
use App\Models\ProductCategory;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DataHelper
{
    /**
     * get single message error, from result validate form request laravel
     *
     * @param  array $id     product id
     * @return string     return name of product
     */
    public function getNameproducts($id)
    {
        //? find id
        $product = Product::find($id);

        if (is_null($product))
            return '';

        return $product->product_name;
    }

    /**
     * Get data sum credit, debit, income from transaction
     *
     * @param $request => company_id, period,
     * @param $balance => credit, debit,
     * @param $account_id,
     * @param $parent_account_id,
     * @return object collection of transaction with custom select
     */
    public function getTransactionByOtherSpecification(
        $request = null,
        $account_id = null,
        $parent_account_id = null,
        $balance = null,
        $account_type = null,
        $transaction_id = null
    ) {
        $query = TransactionDetail::select(DB::raw('
            SUM(credit_amount) AS nominal_credit_amount,
            SUM(debit_amount) AS nominal_debit_amount,
            (SUM(credit_amount) - SUM(debit_amount)) AS nominal_income,
            (SUM(debit_amount) - SUM(credit_amount)) AS nominal_net_mutation_debit,
            (SUM(credit_amount) - SUM(debit_amount)) AS nominal_net_mutation_credit
        '))
        ->join('transactions', 'transactions.id', '=', 'transaction_details.transaction_id')
        ->join('accounts', 'accounts.id', '=', 'transaction_details.account_id')
        ->where('transactions.transaction_status', Transaction::STATUS_POSTED);

        if (!is_null($request)) {
            if (!is_null($request->company_id)) {
                $query->where('transactions.company_id', $request->company_id);
            }
            if ($request->is_profit_loss) {
                $query->whereRaw('(accounts.account_type IN (
                    '.Account::INCOME.',
                    '.Account::COGS.',
                    '.Account::EXPENSES.',
                    '.Account::OTHER_INCOME.',
                    '.Account::OTHER_EXPENSES.'
                ))');
            }
            // untuk filter format bulan 'Y-m'
            if (!is_null($request->start_period) && !is_null($request->end_period)) {
                $query->whereBetween('transactions.transaction_date', [
                    app('string.helper')->parseStartOrLastDateOfMonth($request->start_period, 'Y-m-d', false),
                    app('string.helper')->parseStartOrLastDateOfMonth($request->end_period, 'Y-m-d', true)
                ]);
            }
            // untuk filter format tanggal indo 'd-m-Y'
            if (!is_null($request->start_date) && !is_null($request->end_date)) {
                $query->whereBetween('transactions.transaction_date', [
                    app('string.helper')->parseDateFormat($request->start_date),
                    app('string.helper')->parseDateFormat($request->end_date)
                ]);
            }
        }
        // kondisi jika sekaligus account id dan parent id ada
        if (!is_null($account_id) && !is_null($parent_account_id)) {
            if ($account_id == $parent_account_id) {
                $query->whereRaw('(
                    transaction_details.account_id = '.'"'.$account_id.'"'. ' OR  accounts.parent_account_id = '.'"'.$parent_account_id.'"'. '
                )');
            }
        } else {
            if (!is_null($account_id)) {
                if (is_array($account_id)) $query->whereIn('transaction_details.account_id', $account_id);
                else $query->where('transaction_details.account_id', $account_id);
            }
            if (!is_null($parent_account_id)) $query->where('accounts.parent_account_id', $parent_account_id);
        }
        if (!is_null($balance)) $query->where('accounts.balance', $balance);
        if (!is_null($account_type)) $query->where('accounts.account_type', $account_type);
        if (!is_null($transaction_id)) $query->where('transactions.id', $transaction_id);

        $result = $query->first();
        return $result;
    }

    /**
     * check value and object instance
     *
     * @param $value, $object
     * @return boolean
     */
    public function isUnemptyObject($object, $value = null) {
        if (!empty($value)) {
            return !empty($object) && !empty($value);
        }
        return !empty($object);
    }

    /**
     * get value sales discount from coa potongan
     *
     * @param $value object of collection
     * @return float
     */
    public function getSalesDiscounts($object)
    {
        if (app('data.helper')->isUnemptyObject($object, $object->nominal_debit_amount)) {
            return -$object->nominal_debit_amount;
        }
        return 0;
    }

    /**
     * Display a listing of the resource.
     *
     * @return data user company
     */
    public function getUserCompany()
    {
        $user_company = \App\User::with(['company'])->where('id', Auth::user()->id)->first();
        return $user_company ? $user_company : null;
    }

    /**
     * @param $id account id
     * @return string return name of account
     */
    public function getAccountName($id)
    {
        $account = Account::find($id);
        if (is_null($account))return '';
        return $account->account_name;
    }

    /**
     * @param $id company id
     * @return string return name of company
     */
    public function getCompanyName($id)
    {
        $company = Company::find($id);
        if (is_null($company)) return '';
        return $company->company_name;
    }

    /**
     * @param $id company id
     * @param $product_category_id category product id
     * @return string return name of category product
     */
    public function getProductCategoryName($product_category_id, $company_id)
    {
        $product_category = ProductCategory::where('id', $product_category_id)
            ->where('company_id', $company_id)
            ->first();
        if (is_null($product_category)) return '';
        return $product_category->category_name;
    }

    /**
     * @param $status_id status invoice
     * @return string return name of status payment invoice
     */
    public function getStatusOfInvoice($status_id)
    {
        $invoice_status = Invoice::getStatusOfInvoice($status_id);

        return $invoice_status;
    }

    /**
     * @param $status_id status invoice
     * @return string return name of status payment invoice
     */
    public function getTypeOfVoucher($status_id)
    {
        $voucher_type = Voucher::getTypeOfVoucher($status_id);

        return $voucher_type;
    }

    /**
     * @param $product_id product type
     * @return string return name of product type
     */
    public function getTypeOfProduct($product_id)
    {
        $product = Product::find($product_id);
        if (is_null($product))
            return null;

        $product_type = Product::getTypeOfProduct($product->type);

        return $product_type;
    }

    public function getAccounNaming($id)
    {
        $data = Account::find($id);
        return !is_null($data->account_text) ? $data->account_text : $data->account_name;

    }

    /**
     * @param $transaction_id transaction id
     * @return array of transaction_details without sales discounts (Recevaible) or vat in (Payable)
     */
    public function getDetailsInvoicesByTransactionId($transaction_id)
    {
        $query = Transaction::select(DB::raw("
            accounts.id,
            accounts.account_name,
            accounts.account_code,
            accounts.account_type,
            transactions.transaction_date,
            transactions.reference_invoice_number,
            transactions.transaction_date,
            transactions.model_type,
            invoices.invoice_number,
            invoices.type,
            (
            CASE WHEN accounts.account_name = 'Operational Expenses' THEN (
                SELECT
                (
                    SUM(credit_amount) + SUM(debit_amount)
                )
                FROM
                transaction_details td2
                INNER JOIN accounts a2 ON td2.account_id = a2.id
                WHERE
                a2.account_name IN ('Vat In', 'Operational Expenses')
            ) ELSE transaction_details.debit_amount END
            ) AS debit_amount,
            (
            CASE WHEN accounts.account_name = 'Goods Sales' THEN (
                SELECT
                (
                    SUM(credit_amount) - SUM(debit_amount)
                )
                FROM
                transaction_details td2
                INNER JOIN accounts a2 ON td2.account_id = a2.id
                WHERE
                a2.account_name IN (
                    'Sales Discounts', 'Goods Sales'
                )
            ) ELSE transaction_details.credit_amount END
            ) AS credit_amount
        "))
        ->join('transaction_details', 'transactions.id', '=', 'transaction_details.transaction_id')
        ->join('accounts', 'transaction_details.account_id', '=', 'accounts.id')
        ->join('invoices', 'transactions.model_id', '=', 'invoices.id')
        ->whereRaw("(CASE invoices.type WHEN '1' THEN accounts.account_name != 'Sales Discounts' WHEN '2' THEN accounts.account_name != 'Vat In' END) != '0'");
        if ($transaction_id) {
            $query->where('transactions.id', $transaction_id);
        }
        $data = $query->get();
        return $data->toArray();
    }

    public function getAccountChildByParent($parent_account_id)
    {
        $data = Account::where('parent_account_id', $parent_account_id)
            ->orderBy('account_code', 'asc')
            ->get();
        return $data;
    }

    public function getTotalOpenBalance($request, $account_id = null)
    {
        $query = AccountBalance::select(DB::raw('
            SUM(credit_amount) AS nominal_balance_credit,
            SUM(debit_amount) AS nominal_balance_debit
        '))->join('accounts', 'accounts.id', '=', 'account_balances.account_id');

        if ($request->start_period && $request->end_period) {
            $query->whereBetween('balance_date', [
                app('string.helper')->parseStartOrLastDateOfMonth($request->start_period, 'Y-m-d', false),
                app('string.helper')->parseStartOrLastDateOfMonth($request->end_period, 'Y-m-d', true)
            ]);
        }
        if (!is_null($account_id)) $query->where('account_id', $account_id);
        if (!is_null($request->company_id)) $query->where('accounts.company_id', $request->company_id);
        return $query->first();
    }

    public function getTransactionDetailsByTransactionId($transaction_id, $account_id = null)
    {
        if (!is_null($transaction_id)) {
            $query = DB::table('transaction_details')->select([
                'transaction_details.id',
                'transaction_details.account_id',
                'transaction_details.transaction_id',
                'transaction_details.debit_amount',
                'transaction_details.credit_amount',
                'accounts.account_name',
                'accounts.account_type'
            ])
            ->join('accounts', 'transaction_details.account_id', '=', 'accounts.id')
            ->where('transaction_details.transaction_id', $transaction_id);

            if (!is_null($account_id)) $query->where('transaction_details.account_id', $account_id);
            $data = $query->orderBy('accounts.account_type', 'asc')->get();

            return $data;
        }

        return null;
    }

    public function getAccountById($id)
    {
        $data = Account::find($id);
        return !is_null($data) ? $data : null;
    }

    public function getAccountBySpecifiation(
        $company_id,
        $is_get_all = true,
        $name = null,
        $type = null
    ) {
        $data = Account::select(DB::raw('
            accounts.id,
            accounts.company_id,
            accounts.parent_account_id,
            accounts.account_name,
            accounts.account_code,
            accounts.level,
            accounts.balance,
            accounts.account_type
        '))->where('accounts.company_id', $company_id);

        if (!is_null($name)) $data->whereRaw('(accounts.account_name like "%'.$name.'%")');
        if (!is_null($type)) $data->where('accounts.account_type', $type);

        $data->orderBy('accounts.account_name', 'asc');
        $result = $is_get_all == true ? $data->get() : $data->first();

        return $result;

    }

    public function getFinanceConfigurationActiveBySpec($company_id, $config_code = null)
    {
        $data = FinanceConfiguration::with(['finance_configuration_details'])
            ->where('company_id', $company_id)
            ->where('configuration_status', FinanceConfiguration::STATUS_ACTIVE);

        if (!is_null($config_code)) $data->where('configuration_code', $config_code);
        $result = $data->first();

        return $result;
    }

    public function getFinanceConfigurationDetailAccount($finance_configuration, $is_get_one = true)
    {
        $result = $is_get_one ? null : [];
        if (!is_null($finance_configuration) && !empty($finance_configuration->finance_configuration_details)) {
            $config_detail = $finance_configuration->finance_configuration_details;
            if ($is_get_one == false) {
                foreach ($config_detail as $value) array_push($result, $value->account);
            }
            $result = $config_detail[0]->account;
        }

        return $result;
    }
}
