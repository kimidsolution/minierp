<!-- cogs -->
@if (!empty($request->resource_cogs))
    @foreach ($request->resource_cogs as $cogs)
        <tr class="header-title-row" style="cursor: pointer;" data-toggle="collapse" data-target="#cogsCollapse">
            <td style="padding: 10px 10px 10px 0px; color: rgb(80,179,128); font-size: 0.8rem; border-top: none;">
                <i class="fa dripicons-chevron-right"></i>
                <b>{{ $cogs['account_type_name'] }}</b>
            </td>
            <td style="color: rgb(80,179,128); border-top: none;"></td>
        </tr>
        <tr id="cogsCollapse" class="collapse">
            <td style="color: rgb(80,179,128);" colspan="2">
                <table style="width: 100%;">
                    @if (!empty($cogs['accounts']))
                        @foreach ($cogs['accounts'] as $account_cogs)
                            <tr>
                                <td style="padding: 10px 10px 10px 20px; color: rgb(80,179,128); font-size: 0.8rem; border-top: 0;">
                                    <b> {{ $account_cogs['account_name'] }}</b>
                                </td>
                                @php
                                    $cogs_transaction = app('data.helper')->getTransactionByOtherSpecification($request, $account_cogs['account_id'], null, null);
                                    $nominal_cogs_transaction = app('data.helper')->isUnemptyObject($cogs_transaction) ?
                                        app('string.helper')->defFloat($cogs_transaction->nominal_debit_amount) : 0;
                                @endphp
                                @if ($cogs_transaction && !is_null($cogs_transaction->nominal_debit_amount))
                                    <td style="color: rgb(80,179,128); font-size: 0.8rem; border-top: 0;" data-column="currency" class="text-right">
                                        {{ app('string.helper')->defFormatCurrency($nominal_cogs_transaction, "Rp ") }}
                                    </td>
                                @else
                                    <td style="border-top: 0;"></td>
                                @endif
                                @if (!empty($account_cogs['account_children']))
                                    @foreach ($account_cogs['account_children'] as $child_cogs)
                                        @php
                                            $transaction_cogs = app('data.helper')->getTransactionByOtherSpecification($request, $child_cogs['id'], null, null);
                                            $childrens = app('data.helper')->getAccountChildByParent($child_cogs['id']);
                                            $account_nominal_debit = app('data.helper')->isUnemptyObject($transaction_cogs) ?
                                                app('string.helper')->defFloat($transaction_cogs->nominal_debit_amount) : 0;
                                        @endphp

                                        @if ($account_nominal_debit > 0)
                                            <tr data-id="{{ $child_cogs['id'] }}" data-parent="0" data-level="1" style="font-size: 0.8rem;">
                                                <td data-column="name" class="text-left" style="padding: 10px 10px 10px 40px; color: #425176;">
                                                    <b>{{ $child_cogs['account_code'] . ' ' . $child_cogs['naming'] }}</b>
                                                </td>
                                                <td data-column="currency" class="text-right">
                                                    {{ app('string.helper')->defFormatCurrency($account_nominal_debit, "Rp ") }}
                                                </td>
                                            </tr>
                                        @endif

                                        @if (!empty($childrens->toArray()))
                                            @include('finance.report.profit-loss.partials.cogs.cogs_childrens', [
                                                'child_of_cogs' => $childrens->toArray(),
                                                'dataParent' => $child_cogs['id'] ,
                                                'dataLevel' => 1,
                                                'paddingLevel' => 40
                                            ])
                                        @endif
                                    @endforeach
                                @endif
                            </tr>
                            <tr>
                                <td class="text-left" style="padding: 10px 10px 10px 20px; color: rgb(44,101,60); font-size: 0.8rem;">
                                    <b>Total {{ $account_cogs['account_name'] }}</b>
                                </td>
                                <td class="text-right" style="color: rgb(44,101,60); font-size: 0.8rem; border-top: 0.5px solid;">
                                    {{ app('string.helper')->defFormatCurrency($account_cogs['total_nominal_account'], "Rp ") }}
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
            <b style="margin-left: 16px;">Total COGS (Cost of Goods Sold)</b>
        </td>
        <td class="text-right" style="color: rgb(80,179,128); font-size: 0.8rem; border-top: 0.5px solid;">
            {{ app('string.helper')->defFormatCurrency($cogs['account_type_nominal_expense'], "Rp ") }}
        </td>
    </tr>
@endif
<tr>
    <td colspan="2" style="border: none;"></td>
</tr>
