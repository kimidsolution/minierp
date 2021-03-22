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
    <div class="row">
        <div class="col-sm-12">
            <div class="page-title-box">
                <h4 class="page-title">Users</h4>
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
                        <a href="{{ route('admin.users.create') }}" class="btn btn-primary waves-effect waves-light">Create User <i class="far fa-plus-square ml-1"></i></a>
                    </div>
                    <table id="user-datatable" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                        <thead>
                            <tr>
                                <th>Company</th>
                                <th>Name</th>
                                <th>Title</th>
                                <th>Email</th>
                                <th>Job</th>
                                <th>Phone Number</th>
                                <th>Status</th>
                                <th> </th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
        <!-- end col -->
    </div>
    <!-- end row -->
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
            },
        });

        // datatable
        var userDatatable = $("#user-datatable").DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                "url": "{{ $urlDatatable }}",
                "data": {
                    "company_id": "{{ $companyId }}",
                }
            },
            columns: [
                {
                    data: "company.company_name",
                },
                {
                    data: "name",
                },
                {
                    data: "title",
                },
                {
                    data: "email",
                },
                {
                    data: "job",
                },
                {
                    data: "phone_number",
                },
                {
                    data: "user_status",
                },
                {
                    data: 'action',
                    orderable: false,
                    searchable: false
                },
            ],
        });
    });

</script>

@endsection
