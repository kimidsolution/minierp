@extends('layouts.backend.metrica.master')

@section('css')

<!-- DataTables -->
<link href="{{ URL::asset('metrica/plugins/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ URL::asset('metrica/plugins/datatables/buttons.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
<!-- Responsive datatable examples -->
<link href="{{ URL::asset('metrica/plugins/datatables/responsive.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />

@endsection

@section('content')
<div class="container-fluid">
    <br/>
    @if (Session::has('info'))
        <div class="row">
            <div class="col-lg-12">
                <div class="alert alert-secondary alert-dismissible fade show" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true"><i class="mdi mdi-close"></i></span>
                    </button>
                    <strong>Info!</strong> {{ Session::get('info') }}
                </div>
            </div>
        </div>
    @endif
    <div class="row">
        <div class="col-sm-12">
            <div class="page-title-box">
                <h4 class="page-title">Invoices</h4>
            </div>
        </div>
        <!-- end col-->
    </div>
    <!--end row-->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="button-items mb-4 ml-2">
                        <a href="{{ route('finance.invoices.create') }}" class="btn btn-primary waves-effect waves-light">Create Invoice <i class="far fa-plus-square ml-1"></i></a>
                    </div>
                    <table id="invoice-datatable" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                        <thead>
                            <tr>
                                <th>Type</th>
                                <th>Reference Number</th>
                                <th>Date</th>
                                <th>Due Date</th>
                                <th>Paid Status</th>
                                <th>Is Posted</th>
                                <th>Final Amount</th>
                                <th>Partner</th>
                                <th></th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
        <!-- end col -->
    </div>
</div>
<!-- container -->


@endsection

@section('script')

<!-- DataTables -->
<script src="{{ URL::asset('metrica/plugins/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ URL::asset('metrica/plugins/datatables/dataTables.bootstrap4.min.js') }}"></script>
<!-- Responsive examples -->
<script src="{{ URL::asset('metrica/plugins/datatables/dataTables.responsive.min.js') }}"></script>
<script src="{{ URL::asset('metrica/plugins/datatables/responsive.bootstrap4.min.js') }}"></script>

<script>
    $(document).ready(function (e) {

        // ajax set-up
        $.ajaxSetup({
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                "user_id": {{ Auth::user()->id }}
            },
        });

        // datatable
        var userDatatable = $("#invoice-datatable").DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                "url": "{{ $urlDatatable }}",
                "data": {
                    "partner_id": "{{ $partner_id }}",
                    "is_admin": "{{ $isAdmin }}"
                }
            },
            columns: [
                {
                    data: "type",
                },
                {
                    data: "number"
                },
                {
                    data: "datehuman"
                },
                {
                    data: "duedatehuman"
                },
                {
                    data: "status_paid"
                },
                {
                    data: "is_posted"
                },
                {
                    data: "final_amount"
                },
                {
                    data: "partner.name"
                },
                {
                    data: 'action',
                    orderable: false,
                    searchable: false
                }
            ],
        });
    });

</script>

@endsection
