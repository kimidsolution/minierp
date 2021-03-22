<a href="{{ route('finance.invoices.payable.show', ['payable' => $invoice->id]) }}" class="btn btn-sm btn-gradient-primary waves-effect waves-light" title="klik untuk detail"><i class="far fa-eye ml-1"></i></a>
@if (2 == $invoice->is_posted)
{{-- <a href="{{ route('finance.invoices.payable.edit', ['payable' => $invoice->id]) }}" class="btn btn-sm btn-gradient-warning waves-effect waves-light" title="klik untuk edit"><i class="fas fa-pencil-alt ml-1"></i></a> --}}
{{-- <a href="{{ route('finance.invoices.payable.delete', ['id' => $invoice->id]) }}" class="btn btn-sm btn-gradient-danger waves-effect waves-light" title="klik untuk hapus"><i class="fas fa-trash-alt ml-1"></i></a> --}}
@endif