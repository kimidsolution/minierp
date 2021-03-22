@if (is_null($currencies->deleted_at))
<button style="margin: 5px;"
    title="Delete"
    data-id="{{ $currencies->id }}"
    data-name="{{ $currencies->currency_name }}"
    class="btn btn-sm btn-gradient-danger waves-effect waves-light sa-information actions-table btn-delete"
>
    <i class="far fa-trash-alt ml-1"></i>
</button>
@endif
