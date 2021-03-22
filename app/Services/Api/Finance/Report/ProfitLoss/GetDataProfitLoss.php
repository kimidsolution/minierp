<?php

namespace App\Services\Api\Finance\Report\ProfitLoss;

use App\Models\Account;
use App\Models\FinanceConfiguration;
use Illuminate\Http\Request;
use League\Fractal\Manager;
use League\Fractal\Resource\Collection;

class GetDataProfitLoss
{

    public static function handle(Request $request)
    {
        $resource_income = Self::parseFromType($request, $request->data_account_type_income);
        $resource_cogs = Self::parseFromType($request, $request->data_account_type_cogs);
        $resource_expense = Self::parseFromType($request, $request->data_account_type_expense);
        $resource_other_income = Self::parseFromType($request, $request->data_account_type_other_income);
        $resource_other_expense = Self::parseFromType($request, $request->data_account_type_other_expense);

        $total_income = app('string.helper')->defFloat($resource_income[0]['account_type_nominal_income']);
        $total_other_income = app('string.helper')->defFloat($resource_other_income[0]['account_type_nominal_income']);
        $total_cogs = app('string.helper')->defFloat($resource_cogs[0]['account_type_nominal_expense']);
        $total_expense = app('string.helper')->defFloat($resource_expense[0]['account_type_nominal_expense']);
        $total_other_expense = app('string.helper')->defFloat($resource_other_expense[0]['account_type_nominal_expense']);
        $tax_peryear = app('string.helper')->defFloat(Self::getTaxPerYear($request));

        $gross_profit = Self::countSubstraction($total_income, $total_cogs, true);
        $net_income = Self::countSubstraction($gross_profit, $total_expense, true);
        $net_other = Self::countSubstraction($total_other_income, $total_other_expense, true);

        $total_profit_loss_beforetax = app('string.helper')->defFloat($net_income + $net_other);
        $total_profit_loss = Self::countSubstraction($total_profit_loss_beforetax, $tax_peryear, true);

        $request->request->add([
            'resource_income' => !empty($resource_income) ? $resource_income : null,
            'resource_cogs' => !empty($resource_cogs) ? $resource_cogs : null,
            'resource_expense' => !empty($resource_expense) ? $resource_expense : null,
            'resource_other_income' => !empty($resource_other_income) ? $resource_other_income : null,
            'resource_other_expense' => !empty($resource_other_expense) ? $resource_other_expense : null,
            'gross_profit' => $gross_profit,
            'net_income' => $net_income,
            'net_other' => $net_other,
            'total_profit_loss_beforetax' => $total_profit_loss_beforetax,
            'tax_peryear' => $tax_peryear,
            'total_profit_loss' => $total_profit_loss
        ]);
    }

    protected static function parseFromType($request, $type_list)
    {
        $result = [];
        $fractal = new Manager();
        $array_name_tax = Self::getNameTax($request->company_id);

        if (!empty($type_list)) {
            foreach ($type_list as $key => $account_type) {
                $list_account_of_type = Account::parentless()
                    ->where('company_id', $request->company_id)
                    ->where('account_type', $account_type['id'])
                    ->get();

                $transaction = app('data.helper')->getTransactionByOtherSpecification(
                    $request, null, null, $account_type['balance'], $account_type['id']
                );

                $account_type_nominal_income = app('data.helper')->isUnemptyObject($transaction) ?
                    app('string.helper')->defFloat($transaction->nominal_income) : 0;
                $account_type_nominal_expense = app('data.helper')->isUnemptyObject($transaction) ?
                    app('string.helper')->defFloat($transaction->nominal_debit_amount) : 0;


                if (!empty($list_account_of_type)) {
                    $resource = new Collection($list_account_of_type->toArray(), function(array $account) use ($request) {
                        $childrens = app('data.helper')->getAccountChildByParent($account['id']);
                        $account_child = app('array.helper')->buildTree($childrens->toArray(), $account['id'], 'parent_account_id', 'id');
                        return Self::mappingSourceTransactionAccount($request, $account, $account_child);
                    });
                    $array = $fractal->createData($resource)->toArray();
                    $arrayData = $array['data'];

                    // remove account_name tax from account_type Expenses
                    if (Account::EXPENSES == $account_type['id']) {
                        $account_type_nominal_expense = Self::countSubstraction(
                            $account_type_nominal_expense,
                            Self::getTaxPerYear($request), true
                        );
                        if (count($arrayData) > 0) {
                            foreach ($arrayData as $keyP => $value) {
                                if ('Expenses' == $value['account_name']) {
                                    $listChildren = $value['account_children'];
                                    if (count($listChildren) > 0) {
                                        // search account name tax from expense
                                        foreach ($listChildren as $keyC => $valueC) {
                                            if (in_array($valueC['account_name'], $array_name_tax)) {
                                                unset($arrayData[$keyP]['account_children'][$keyC]);
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }

                    $result[$key] = [
                        'account_type_id' => $account_type['id'],
                        'account_type_name' => $account_type['name'],
                        'account_type_balance' => $account_type['balance'],
                        'account_type_nominal_income' => $account_type_nominal_income,
                        'account_type_nominal_expense' => $account_type_nominal_expense,
                        'accounts' => $arrayData
                    ];
                }
            }
        }
        return $result;
    }

    protected static function mappingSourceTransactionAccount($request, $data, $children)
    {
        $transaction = app('data.helper')->getTransactionByOtherSpecification($request, $data['id'], null, null);
        if (!empty($children)) {
            $transaction = app('data.helper')->getTransactionByOtherSpecification($request, null, $data['id'], null);
            return Self::mappingAccounts($request, $transaction, $data, $children);
        }
        return Self::mappingAccounts($request, $transaction, $data);
    }

    protected static function mappingAccounts($request, $transaction, $data, $children = [])
    {
        $count_transaction_parent = app('data.helper')->getTransactionByOtherSpecification($request, $data['id'], null, null);
        $total_nominal_account = Self::sumPerAccountNested($request, $children);
        if ($data['balance'] == 'debit') $total_nominal_account += $count_transaction_parent->nominal_debit_amount;
        if ($data['balance'] == 'credit') $total_nominal_account += $count_transaction_parent->nominal_credit_amount;

        return [
            'account_id' => $data['id'],
            'account_name' => $data['naming'],
            'account_code' => $data['account_code'],
            'account_level' => $data['level'],
            'account_nominal_credit' => app('data.helper')->isUnemptyObject($transaction) ?
                app('string.helper')->defFloat($transaction->nominal_credit_amount) : 0,
            'account_nominal_debit' => app('data.helper')->isUnemptyObject($transaction) ?
                app('string.helper')->defFloat($transaction->nominal_debit_amount) : 0,
            'account_is_discounts' => $data['account_name'] == Account::SALES_DISCOUNTS,
            'account_nominal_discounts' => app('data.helper')->getSalesDiscounts($transaction),
            'account_nominal_parent_discounts' => !empty($children) ?
                app('string.helper')->defFloat(Self::countSubstraction(
                    $transaction->nominal_credit_amount, $transaction->nominal_debit_amount, false
                )
            ) : 0,
            'account_children' => $children,
            'total_nominal_account' => $total_nominal_account
        ];
    }

    protected static function countSubstraction($nominal_income, $nominal_expense, $istotal) {
        $result = 0;
        if ($istotal) {
            if (!empty($nominal_income)) $result = $nominal_income - $nominal_expense;
        } else {
            if (!empty($nominal_income) && !empty($nominal_expense)) $result = $nominal_income - $nominal_expense;
        }

        return app('string.helper')->defFloat($result);
    }

    protected static function sumPerAccountNested($request, $childrensParam = []) {
        $result = 0;
        $array_name_tax = Self::getNameTax($request->company_id);

        if (!empty($childrensParam)) {
            foreach ($childrensParam as $value) {
                if (in_array($value['account_name'], $array_name_tax)) continue;

                $transaction = app('data.helper')->getTransactionByOtherSpecification($request, $value['id'], null, null);
                if ($value['balance'] == 'debit') $result += $transaction->nominal_debit_amount;
                if ($value['balance'] == 'credit') $result += $transaction->nominal_credit_amount;

                $childrens = app('data.helper')->getAccountChildByParent($value['id']);
                if (!empty($childrens)) $result += Self::sumPerAccountNested($request, $childrens);
            }
        }

        return app('string.helper')->defFloat($result);
    }

    protected static function getTaxPerYear($request) {
        $result = 0;
        $account_id = [];
        $finance_config = Self::getFinanceConfigurationTax($request->company_id);

        if (!is_null($finance_config) && !empty($finance_config->finance_configuration_details)) {
            foreach ($finance_config->finance_configuration_details as $value) array_push($account_id, $value->account_id);
            $transaction = app('data.helper')->getTransactionByOtherSpecification($request, $account_id, null, null);
            $result = app('data.helper')->isUnemptyObject($transaction) ? $transaction->nominal_debit_amount : 0;
        }

        return app('string.helper')->defFloat($result);
    }

    protected static function getFinanceConfigurationTax($company_id) {
        $finance_config = FinanceConfiguration::with(['finance_configuration_details'])
            ->where('company_id', $company_id)
            ->where('configuration_status', FinanceConfiguration::STATUS_ACTIVE)
            ->where('configuration_code', FinanceConfiguration::CODE_ACCOUNT_ANNUAL_TAX)
            ->first();

        return $finance_config;
    }

    protected static function getNameTax($company_id) {
        $result = [];
        $finance_config = Self::getFinanceConfigurationTax($company_id);
        if (!is_null($finance_config) && !empty($finance_config->finance_configuration_details)) {
            foreach ($finance_config->finance_configuration_details as $value) {
                array_push($result, $value->account->account_name);
            }
        }
        return $result;
    }
}
