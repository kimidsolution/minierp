@extends('layouts.backend.metrica.master')

@section('css')

<!-- DataTables -->
<link href="{{ URL::asset('metrica/plugins/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ URL::asset('metrica/plugins/datatables/buttons.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="page-title-box">
                <h4 class="page-title">Currency</h4>
            </div>
        </div>
        <!-- end col-->
    </div>
    <!--end row-->

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
            <div class="toast-body fill-message"></div>
        </div>
        <!--end toast-->
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <input type="hidden" id="routeFetch" value="{{ $urlDatatable }}">
                <input type="hidden" id="routeDelete" value="{{ route('api.admin.currencies.destroy.route') }}">
                <input type="hidden" id="userId" value="{{ Auth::user()->id }}">
                <div class="card-body">
                    <div class="button-items mb-4 ml-6">
                        <a href="{{ route('admin.currencies.create') }}" class="btn btn-primary waves-effect waves-light">Create Currency <i class="far fa-plus-square ml-1"></i></a>
                        <div class="scope-action-table">
                            <button id="show-all" class="btn btn-primary items-hide" data-toggle="tooltip" data-placement="left"
                                title="Show All Partners" data-id="false">
                                Show All
                            </button>
                            <button id="show-active" class="btn btn-primary items-hide" data-toggle="tooltip" data-placement="left"
                                title="Show Partners Active" data-id="true">
                                Show Active
                            </button>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table id="currencies-datatable" class="table table-striped table-bordered nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                            <thead>
                                <tr>
                                    <th>ISO Code</th>
                                    <th>Name</th>
                                    <th>Code</th>
                                    <th>Symbol</th>
                                    <th>Crdt</th>
                                    <th></th>
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
<script src="{{ URL::asset('pages/js/global/time.js') }}"></script>
<script src="{{ URL::asset('pages/js/global/notifications.js') }}"></script>
<script src="{{ URL::asset('pages/js/currency/index.js') }}"></script>

@endsection
