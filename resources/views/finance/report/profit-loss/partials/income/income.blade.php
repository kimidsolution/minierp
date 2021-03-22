<!-- Income -->
@if (!empty($request->resource_income))
    @foreach ($request->resource_income as $income)
        <tr class="header-title-row" style="cursor: pointer;" data-toggle="collapse" data-target="#incomeCollapse">
            <td style="padding: 10px 10px 10px 0px; color: rgb(80,179,128); font-size: 0.8rem; border-top: none;">
                <i class="fa dripicons-chevron-right"></i>
                <b>{{ $income['account_type_name'] }}</b>
            </td>
            <td style="color: rgb(80,179,128); border-top: none;"></td>
        </tr>
        <tr id="incomeCollapse" class="collapse">
            <td style="color: rgb(80,179,128);" colspan="2">
                <table style="width: 100%;">
                    @if (!empty($income['accounts']))
                        @foreach ($income['accounts'] as $account_income)
                            <tr>
                                <td style="padding: 10px 10px 10px 20px; color: rgb(80,179,128); font-size: 0.8rem; border-top: 0;">
                                    <b> {{ $account_income['account_name'] }}</b>
                                </td>
                                @php
                                    $income_transaction = app('data.helper')->getTransactionByOtherSpecification($request, $account_income['account_id'], null, null);
                                    $nominal_income_transaction = app('data.helper')->isUnemptyObject($income_transaction) ?
                                        app('string.helper')->defFloat($income_transaction->nominal_credit_amount) : 0;
                                @endphp
                                @if ($income_transaction && !is_null($income_transaction->nominal_credit_amount))
                                    <td style="color: rgb(80,179,128); font-size: 0.8rem; border-top: 0;" data-column="currency" class="text-right">
                                        {{ app('string.helper')->defFormatCurrency($nominal_income_transaction, "Rp ") }}
                                    </td>
                                @else
                                    <td style="border-top: 0;"></td>
                                @endif
                                @if (!empty($account_income['account_children']))
                                    @foreach ($account_income['account_children'] as $child_revenue)
                                        @php
                                            $transaction_income = app('data.helper')->getTransactionByOtherSpecification($request, $child_revenue['id'], null, null);
                                            $childrens = app('data.helper')->getAccountChildByParent($child_revenue['id']);
                                            $account_is_discounts = $child_revenue['account_name'] == App\Models\Account::SALES_DISCOUNTS;
                                            $account_nominal_discounts = app('data.helper')->getSalesDiscounts($transaction_income);
                                            $account_nominal_credit = app('data.helper')->isUnemptyObject($transaction_income) ?
                                                app('string.helper')->defFloat($transaction_income->nominal_credit_amount) : 0;
                                            $account_nominal_revenue = $account_is_discounts ? $account_nominal_discounts : $account_nominal_credit;
                                        @endphp

                                        @if ($account_nominal_revenue > 0)
                                            <tr data-id="{{ $child_revenue['id'] }}" data-parent="0" data-level="1" style="font-size: 0.8rem;">
                                                <td data-column="name" class="text-left" style="padding: 10px 10px 10px 40px; color: {{ $account_is_discounts ? 'rgb(241,100,108);' : '#425176;' }}">
                                                    <b>{{ $child_revenue['account_code'] . ' ' . $child_revenue['naming'] }}</b>
                                                </td>
                                                <td data-column="currency" class="text-right" style="color: {{ $account_is_discounts ? 'rgb(241,100,108);' : '#425176;' }} ">
                                                    {{ app('string.helper')->defFormatCurrency($account_nominal_revenue, "Rp ") }}
                                                </td>
                                            </tr>
                                        @endif

                                        @if (!empty($childrens->toArray()))
                                            @include('finance.report.profit-loss.partials.income.income_childrens', [
                                                'child_of_revenue' => $childrens->toArray(),
                                                'dataParent' => $child_revenue['id'] ,
                                                'dataLevel' => 1,
                                                'paddingLevel' => 40
                                            ])
                                        @endif
                                    @endforeach
                                @endif
                            </tr>
                            <tr>
                                <td class="text-left" style="padding: 10px 10px 10px 20px; color: rgb(44,101,60); font-size: 0.8rem;">
                                    <b>Total {{ $account_income['account_name'] }}</b>
                                </td>
                                <td class="text-right" style="color: rgb(44,101,60); font-size: 0.8rem; border-top: 0.5px solid;">
                                    {{ app('string.helper')->defFormatCurrency($account_income['total_nominal_account'], "Rp ") }}
                                </td>
                            </tr>
                        @endforeach
                    @endif
                </table>
            </td>
        </tr>
    @endforeach
    <tr>
        <td class="text-left" style="padding: 10px 10px 10px 0px; color: rgb(80,179,128); font-size: 0.8rem;">
            <b style="margin-left: 16px;">Total Income</b>
        </td>
        <td class="text-right" style="color: rgb(80,179,128); font-size: 0.8rem; border-top: 0.5px solid;">
            {{ app('string.helper')->defFormatCurrency($income['account_type_nominal_income'], "Rp ") }}
        </td>
    </tr>
@endif
<tr>
    <td colspan="2" style="border: none;"></td>
</tr>
