@if ('no' == $revenue->is_posted)
<a href="{{ route('finance.revenues.edit', ['revenue' => $revenue->id]) }}" class="btn btn-sm btn-gradient-warning waves-effect waves-light" title="klik untuk edit"><i class="fas fa-pencil-alt ml-1"></i></a>
@endif