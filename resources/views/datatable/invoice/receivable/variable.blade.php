<div class="row">
    <div class="col-md-12">
        <div class="float-right">
            {{ app('string.helper')->defFormatCurrency($invoice->total_amount, "Rp ") }}
        </div>
    </div>
</div>