@extends('layouts.backend.metrica.master')

@section('css')

<!-- DataTables -->
<link href="{{ URL::asset('metrica/plugins/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet"
    type="text/css" />
<link href="{{ URL::asset('metrica/plugins/datatables/buttons.bootstrap4.min.css') }}" rel="stylesheet"
    type="text/css" />
<!-- Responsive datatable examples -->
<link href="{{ URL::asset('metrica/plugins/datatables/responsive.bootstrap4.min.css') }}" rel="stylesheet"
    type="text/css" />

@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="page-title-box">
                <h4 class="page-title">Company</h4>
            </div>
        </div>
        <!-- end col-->
    </div>

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

    <div style="position: absolute; top: 0; right: 0;">
        <div class="toast fade" data-autohide="false" role="alert" aria-live="assertive" aria-atomic="true"
            data-toggle="toast">
            <div class="toast-header">
                <strong class="mr-auto">Notification</strong>
                <small class="text-muted current-time"></small>
                <button type="button" class="ml-2 mb-1 close" data-dismiss="toast" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="toast-body fill-message">
            </div>
        </div>
        <!--end toast-->
    </div>

    <!--end row-->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <input type="hidden" id="routeFetch" value="{{ $urlDatatable }}">
                <input type="hidden" id="routeUpdateStatus" value="{{ route('api.company.update.status.route') }}">
                <input type="hidden" id="userId" value="{{ Auth::user()->id }}">
                <div class="card-body">
                    <div class="button-items mb-4 ml-2">
                        <a href="{{ route('admin.companies.create') }}"
                            class="btn btn-primary waves-effect waves-light">
                            Create Company <i class="far fa-plus-square ml-1"></i>
                        </a>
                        <div class="scope-action-table">
                            <button id="show-all" class="btn btn-primary" data-toggle="tooltip" data-placement="left"
                                title="Show All Company" data-id="false">
                                Show All
                            </button>
                            <button id="show-active" class="btn btn-primary" data-toggle="tooltip" data-placement="left"
                                title="Show Company Active" data-id="true" style="display: none">
                                Show Active
                            </button>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table id="company-datatable" class="table table-striped table-bordered nowrap"
                            style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                            <thead>
                                <tr>
                                    <th>Company Name</th>
                                    <th>Brand Name</th>
                                    <th>Type</th>
                                    <th>City</th>
                                    <th>Country</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Pic</th>
                                    <th>Status</th>
                                    <th>Created at</th>
                                    <th> </th>
                                </tr>
                            </thead>
                        </table>
                    </div>
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
<script src="{{ URL::asset('pages/js/company/index.js') }}"></script>

@endsection