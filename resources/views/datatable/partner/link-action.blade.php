@if (is_null($partner->deleted_at))
<button style="margin: 5px;" title="Delete"
    data-id="{{ $partner->id }}"
    data-name="{{ $partner->partner_name }}"
    class="btn btn-sm btn-gradient-danger waves-effect waves-light sa-information actions-table btn-delete"
>
    <i class="far fa-trash-alt ml-1"></i>
</button>
@endif
<button style="margin: 5px;" type="button" class="btn btn-sm btn-gradient-primary waves-effect waves-light sa-information" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
    <i class="fas fa-file-alt ml-1"></i>
</button>
<div class="dropdown-menu">
    <a class="dropdown-item" href="{{route('finance.invoices.invoices-receivable-partner', ['id' => $partner->id])}}">Invoice Receivable</a>
    <a class="dropdown-item" href="{{route('finance.invoices.invoices-payable-partner', ['id' => $partner->id])}}">Invoice Payable</a>
</div>
