@php
    $link_edit = $transaction->transaction_type == \App\Models\Transaction::TYPE_RECEIVABLE ?
        'finance.transactions.receivable.edit'  : 'finance.transactions.payable.edit'
@endphp
<button title="Detail"
    data-id="{{ $transaction->id }}"
    class="btn btn-sm btn-gradient-info waves-effect waves-light sa-information actions-table details-control"
>
    <i class="fas fa-list-ul"></i>
</button>
@if ($transaction->transaction_status == \App\Models\Transaction::STATUS_DRAFT)
    <a href="{{ route($link_edit, ['id' => $transaction->id]) }}"
        class="btn btn-sm btn-gradient-primary waves-effect waves-light actions-table btn-edit"
        title="Edit"
    >
        <i class="fas fa-edit"></i>
    </a>
    <button title="Click For Posted"
        data-id="{{ $transaction->id }}"
        data-name="{{ $transaction->reference_number }}"
        class="btn btn-sm btn-info btn-square btn-outline-dashed waves-effect waves-light actions-table btn-posted-transaction-action btn-posted"
    >
        Post It
    </button>
@endif
<button title="Delete"
    data-id="{{ $transaction->id }}"
    data-name="{{ $transaction->reference_number }}"
    class="btn btn-sm btn-gradient-danger waves-effect waves-light sa-information actions-table btn-delete items-hide"
>
    <i class="far fa-trash-alt"></i>
</button>
