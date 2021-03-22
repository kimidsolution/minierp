@include('finance.report.profit-loss.partials.income.income')

@include('finance.report.profit-loss.partials.cogs.cogs')

<tr style="background-color: #f1f5fa;">
    <td style="padding: 10px 10px 10px 0px; color: #303e67; font-size: 0.8rem; border-top: none;">
        <b style="padding-left: 15px;">Gross Profit</b>
    </td>
    <td class="text-right" style="color: #303e67; font-size: 0.8rem; border-top: none;">
        {{ app('string.helper')->defFormatCurrency($request->gross_profit, "Rp ") }}
    </td>
</tr>

<tr><td style="border-top: none;" colspan="2"></td></tr>

@include('finance.report.profit-loss.partials.expense.expense')

<tr style="background-color: #f1f5fa;">
    <td style="padding: 10px 10px 10px 0px; color: #303e67; font-size: 0.8rem; border-top: none;">
        <b style="padding-left: 15px;">Profit</b>
    </td>
    <td class="text-right" style="color: #303e67; font-size: 0.8rem; border-top: none;">
        {{ app('string.helper')->defFormatCurrency($request->net_income, "Rp ") }}
    </td>
</tr>

<tr><td style="border-top: none;" colspan="2"></td></tr>

@include('finance.report.profit-loss.partials.other_income.other_income')

@include('finance.report.profit-loss.partials.other_expense.other_expense')

<tr style="background-color: #f1f5fa;">
    <td style="padding: 10px 10px 10px 0px; color: #303e67; font-size: 0.8rem; border-top: none;">
        <b style="padding-left: 15px;">Other Profit</b>
    </td>
    <td class="text-right" style="color: #303e67; font-size: 0.8rem; border-top: none;">
        {{ app('string.helper')->defFormatCurrency($request->net_other, "Rp ") }}
    </td>
</tr>

<tr><td style="border-top: none;" colspan="2"></td></tr>

<tr style="background-color: #f1f5fa;">
    <td style="padding: 10px 10px 10px 0px; color: #303e67; font-size: 0.8rem; border-top: none;">
        <b style="padding-left: 15px;">Profit Loss Before Tax</b>
    </td>
    <td class="text-right" style="color: #303e67; font-size: 0.8rem; border-top: none;">
        {{ app('string.helper')->defFormatCurrency($request->total_profit_loss_beforetax, "Rp ") }}
    </td>
</tr>

<tr><td style="border-top: none;" colspan="2"></td></tr>

<tr style="background-color: #f1f5fa;">
    <td style="padding: 10px 10px 10px 0px; color: #303e67; font-size: 0.8rem; border-top: none;">
        <b style="padding-left: 15px;">Tax</b>
    </td>
    <td class="text-right" style="color: #303e67; font-size: 0.8rem; border-top: none;">
        {{ app('string.helper')->defFormatCurrency($request->tax_peryear, "Rp ") }}
    </td>
</tr>

<tr><td style="border-top: none;" colspan="2"></td></tr>

<tr><td style="border-top: none;" colspan="2"></td></tr>

<tr>
    <td style="padding: 10px 10px 10px 0px; color: #303e67; font-size: 1.2rem;">
        <b>Profit Loss</b>
    </td>
    <td class="text-right" style="color: #303e67; font-size: 1rem; border-top: 0.5px solid;">
        {{ app('string.helper')->defFormatCurrency($request->total_profit_loss, "Rp ") }}
    </td>
</tr>
