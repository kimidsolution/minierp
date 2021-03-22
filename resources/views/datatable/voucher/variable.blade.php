<div class="row">
    <div class="col-md-12">
        <div class="float-right">
            {{ app('string.helper')->defFormatCurrency(app('invoice.helper')->getNominalVoucherInvoice($vouchers->id), "Rp ") }}
        </div>
    </div>
</div>