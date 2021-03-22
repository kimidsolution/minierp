@if (!empty($response['data']))
    @foreach ($response['data'] as $row)
        @if (!empty($row['transaction_details']))
            @foreach ($row['transaction_details'] as $detail)
                <tr>
                    @if ($loop->first)
                        <td rowspan="{{ strval(count($row['transaction_details'])) }}" style="text-align: center; background-color: #fff; width: 10%;">
                            {{ date('d-m-Y', strtotime($row['transaction_date'])) }}
                        </td>
                        <td rowspan="{{ strval(count($row['transaction_details'])) }}" style="text-align: center; background-color: #fff; width: 10%;">
                            {{ $row['reference_number'] }}
                        </td>
                    @endif
                    <td style="width: 25%;">
                        {{ $detail['account']['naming'] }}
                        <span style="font-weight: 500; letter-spacing: 1px; float: right; width: 20%;" class="badge badge-primary">
                            {{ $detail['account']['account_code'] }}
                        </span>
                    </td>
                    @if ($loop->first)
                        <td rowspan="{{ strval(count($row['transaction_details'])) }}" style="text-align: center; background-color: #fff; width: 10%;">
                            <div class="iffyTip hideText2">
                                {{ $row['description'] }}
                            </div>
                        </td>
                    @endif
                    <td style="width: 15%; text-align: right; {{ app('string.helper')->checkIsNegative($detail['debit_amount']) ? 'color: rgb(241,100,108);' : '' }}">
                        {{ $detail['debit_amount'] ? app('string.helper')->defFormatCurrency($detail['debit_amount']) : '' }}
                    </td>
                    <td style="width: 15%; text-align: right; {{ app('string.helper')->checkIsNegative($detail['credit_amount']) ? 'color: rgb(241,100,108);' : '' }}">
                        {{ $detail['credit_amount'] ? app('string.helper')->defFormatCurrency($detail['credit_amount']) : '' }}
                    </td>
                </tr>
                @if ($loop->last && count($row['transaction_details']) > 1)
                    @php
                        $checking_balance_credit = $row['checking_balance_credit'];
                        $checking_balance_debit = $row['checking_balance_debit'];
                        $desc_checking_balance = app('string.helper')->getStringCheckingBalance($checking_balance_credit, $checking_balance_debit);
                    @endphp
                    <tr class="title-checking-balance">
                        <td style="font-weight: bold;" colspan="4">
                            Checking Balance
                            <span style="letter-spacing: 2px; float: right; text-align:center; font-weight: bold;" class="badge {{ $desc_checking_balance == 'Balance' ? 'badge-primary' : 'badge-danger' }}">
                                {{ ' '.$desc_checking_balance }}
                            </span>
                        </td>
                        <td style="font-weight: bold; width: 15%; text-align: right; {{ app('string.helper')->checkIsNegative($checking_balance_debit) ? 'color: rgb(241,100,108);' : '' }}">
                            {{ $checking_balance_debit ? app('string.helper')->defFormatCurrency($checking_balance_debit) : '' }}
                        </td>
                        <td style="font-weight: bold; width: 15%; text-align: right; {{ app('string.helper')->checkIsNegative($checking_balance_credit) ? 'color: rgb(241,100,108);' : '' }}">
                            {{ $checking_balance_credit ? app('string.helper')->defFormatCurrency($checking_balance_credit) : '' }}
                        </td>
                    </tr>
                    <tr>
                        <td colspan="6" style="background-color: #fff; border: none;"></td>
                    </tr>
                @endif
            @endforeach
        @endif
    @endforeach
@endif
