<a href="{{ route('finance.invoices.receivable.show', ['receivable' => $invoice->id]) }}" class="btn btn-sm btn-gradient-primary waves-effect waves-light" title="klik untuk detail"><i class="far fa-eye ml-1"></i></a>
@if (2 == $invoice->is_posted)
{{-- <a href="{{ route('finance.invoices.receivable.edit', ['receivable' => $invoice->id]) }}" class="btn btn-sm btn-gradient-warning waves-effect waves-light" title="klik untuk edit"><i class="fas fa-pencil-alt ml-1"></i></a> --}}
{{-- <a href="{{ route('finance.invoices.receivable.delete', ['id' => $invoice->id]) }}" class="btn btn-sm btn-gradient-danger waves-effect waves-light" title="klik untuk hapus"><i class="fas fa-trash-alt ml-1"></i></a> --}}
@endif