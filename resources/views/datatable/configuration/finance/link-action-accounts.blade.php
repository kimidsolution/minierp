<button title="Detail"
    data-id="{{ $data->id }}"
    class="btn btn-sm btn-gradient-info waves-effect waves-light sa-information actions-table details-control"
>
    <i class="fas fa-list-ul"></i>
</button>
<a href="{{ route('configuration.finance.accounts.edit', ['id' => $data->id]) }}"
    class="btn btn-sm btn-gradient-primary waves-effect waves-light actions-table btn-edit"
    title="Edit"
>
    <i class="fas fa-edit"></i>
</a>
<button title="Delete"
    data-id="{{ $data->id }}"
    data-name="{{ $data->configuration_description }}"
    class="btn btn-sm btn-gradient-danger waves-effect waves-light sa-information actions-table btn-delete items-hide"
>
    <i class="far fa-trash-alt"></i>
</button>
