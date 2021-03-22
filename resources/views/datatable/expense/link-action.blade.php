<button title="Detail"
    data-id="{{ $expense->id }}"
    class="btn btn-sm btn-gradient-info waves-effect waves-light sa-information actions-table details-control"
>
    <i class="fas fa-list-ul"></i>
</button>
@if (!$expense->is_posted)
    <a href="{{ route('finance.expenses.edit', ['expense' => $expense->id]) }}"
        class="btn btn-sm btn-gradient-primary waves-effect waves-light actions-table btn-edit"
        title="Edit"
    >
        <i class="fas fa-edit"></i>
    </a>
    <button title="Delete"
        data-id="{{ $expense->id }}"
        data-name="{{ $expense->reference_number }}"
        class="btn btn-sm btn-gradient-danger waves-effect waves-light sa-information actions-table btn-delete"
    >
        <i class="far fa-trash-alt"></i>
    </button>
    <button title="Click For Posted"
        data-id="{{ $expense->id }}"
        data-name="{{ $expense->reference_number }}"
        style="width: 115px;"
        class="btn btn-sm btn-info btn-square btn-outline-dashed waves-effect waves-light actions-table btn-posted-transaction-action btn-posted"
    >
        Post It
    </button>
@endif
