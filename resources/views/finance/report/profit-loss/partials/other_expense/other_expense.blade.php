<!-- Other Expense -->
@if (!empty($request->resource_other_expense))
    @foreach ($request->resource_other_expense as $expense)
        <tr class="header-title-row" style="cursor: pointer;" data-toggle="collapse" data-target="#otherExpenseCollapse">
            <td style="padding: 10px 10px 10px 0px; color: rgb(80,179,128); font-size: 0.8rem; border-top: none;">
                <i class="fa dripicons-chevron-right"></i>
                <b>{{ $expense['account_type_name'] }}</b>
            </td>
            <td style="color: rgb(80,179,128); border-top: none;"></td>
        </tr>
        <tr id="otherExpenseCollapse" class="collapse">
            <td style="color: rgb(80,179,128);" colspan="2">
                <table style="width: 100%;">
                    @if (!empty($expense['accounts']))
                        @foreach ($expense['accounts'] as $account_expense)
                            <tr>
                                <td style="padding: 10px 10px 10px 20px; color: rgb(80,179,128); font-size: 0.8rem; border-top: 0;">
                                    <b> {{ $account_expense['account_name'] }}</b>
                                </td>
                                @php
                                    $expense_transaction = app('data.helper')->getTransactionByOtherSpecification($request, $account_expense['account_id'], null, null);
                                    $nominal_expense_transaction = app('data.helper')->isUnemptyObject($expense_transaction) ?
                                        app('string.helper')->defFloat($expense_transaction->nominal_debit_amount) : 0;
                                @endphp
                                @if ($expense_transaction && !is_null($expense_transaction->nominal_debit_amount))
                                    <td style="color: rgb(80,179,128); font-size: 0.8rem; border-top: 0;" data-column="currency" class="text-right">
                                        {{ app('string.helper')->defFormatCurrency($nominal_expense_transaction, "Rp ") }}
                                    </td>
                                @else
                                    <td style="border-top: 0;"></td>
                                @endif
                                @if (!empty($account_expense['account_children']))
                                    @foreach ($account_expense['account_children'] as $child_expense)
                                        @php
                                            $transaction_expense = app('data.helper')->getTransactionByOtherSpecification($request, $child_expense['id'], null, null);
                                            $childrens = app('data.helper')->getAccountChildByParent($child_expense['id']);
                                            $account_nominal_debit = app('data.helper')->isUnemptyObject($transaction_expense) ?
                                                app('string.helper')->defFloat($transaction_expense->nominal_debit_amount) : 0;
                                        @endphp

                                        @if ($account_nominal_debit > 0)
                                            <tr data-id="{{ $child_expense['id'] }}" data-parent="0" data-level="1" style="font-size: 0.8rem;">
                                                <td data-column="name" class="text-left" style="padding: 10px 10px 10px 40px; color: #425176;">
                                                    <b>{{ $child_expense['account_code'] . ' ' . $child_expense['naming'] }}</b>
                                                </td>
                                                <td data-column="currency" class="text-right">
                                                    {{ app('string.helper')->defFormatCurrency($account_nominal_debit, "Rp ") }}
                                                </td>
                                            </tr>
                                        @endif

                                        @if (!empty($childrens->toArray()))
                                            @include('finance.report.profit-loss.partials.other_expense.other_expense_childrens', [
                                                'child_of_expense' => $childrens->toArray(),
                                                'dataParent' => $child_expense['id'] ,
                                                'dataLevel' => 1,
                                                'paddingLevel' => 40
                                            ])
                                        @endif
                                    @endforeach
                                @endif
                            </tr>
                            <tr>
                                <td class="text-left" style="padding: 10px 10px 10px 20px; color: rgb(44,101,60); font-size: 0.8rem;">
                                    <b>Total {{ $account_expense['account_name'] }}</b>
                                </td>
                                <td class="text-right" style="color: rgb(44,101,60); font-size: 0.8rem; border-top: 0.5px solid;">
                                    {{ app('string.helper')->defFormatCurrency($account_expense['total_nominal_account'], "Rp ") }}
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
            <b style="margin-left: 16px;">Total Other Expenses</b>
        </td>
        <td class="text-right" style="color: rgb(80,179,128); font-size: 0.8rem; border-top: 0.5px solid;">
            {{ app('string.helper')->defFormatCurrency($expense['account_type_nominal_expense'], "Rp ") }}
        </td>
    </tr>
@endif
<tr>
    <td colspan="2" style="border: none;"></td>
</tr>
