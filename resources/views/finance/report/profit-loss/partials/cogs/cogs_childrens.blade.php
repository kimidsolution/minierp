<!-- cogs Childrens -->
@foreach($child_of_cogs as $child)
    @php
        $padding_left = $child['level'] * 20;
        $transaction = app('data.helper')->getTransactionByOtherSpecification($request, $child['id'], null, null);
        $childrens = app('data.helper')->getAccountChildByParent($child['id']);
        $account_nominal_debit = app('data.helper')->isUnemptyObject($transaction) ?
            app('string.helper')->defFloat($transaction->nominal_debit_amount) : 0;
    @endphp

    @if ($account_nominal_debit > 0)
        <tr data-id="{{ $child['id'] }}" data-parent="{{$dataParent}}" data-level = "{{$dataLevel + 1}}" style="font-size: 0.8rem;">
            <td data-column="name" class="text-left" style="padding: 10px 10px 10px {{ $padding_left }}px; color: #425176;">
                <b>{{ $child['account_code'] . ' ' . $child['naming'] }}</b>
            </td>
            <td data-column="currency" class="text-right">
                {{ app('string.helper')->defFormatCurrency($account_nominal_debit, "Rp ") }}
            </td>
        </tr>
    @endif

    @if (!empty($childrens->toArray()))
        @include('finance.report.profit-loss.partials.cogs.cogs_childrens', [
            'child_of_cogs' => $childrens->toArray(),
            'dataParent' => $child['id'] ,
            'dataLevel' => $dataLevel
        ])
    @endif
@endforeach
