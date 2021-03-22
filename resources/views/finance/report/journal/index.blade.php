@extends('layouts.backend.metrica.master')

@section('css')
    <link href="{{ URL::asset('metrica/assets/plugins/daterangepicker/daterangepicker.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ URL::asset('metrica/assets/plugins/timepicker/bootstrap-material-datetimepicker.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ URL::asset('metrica/assets/plugins/select2/select2.min.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('content')

<div class="spinner-border thumb-md text-primary part-loader"></div>
<div class="container-fluid">
    <!-- Page-Title -->
    <div class="row">
        <div class="col-sm-12">
            <div class="page-title-box">
                <div class="float-right">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="javascript:void(0);">Finance</a></li>
                        <li class="breadcrumb-item"><a href="javascript:void(0);">Report</a></li>
                        <li class="breadcrumb-item active">Journal</li>
                    </ol>
                </div>
                <h4 class="page-title">Journal</h4>
            </div>
            <!--end page-title-box-->
        </div>
        <!--end col-->
    </div>
    <!-- end page title end breadcrumb -->
    <div class="row">
        <input type="hidden" id="routeFetch" value="{{ route('api.finance.report.journal.route') }}">
        <input type="hidden" id="routeFetchAccount" value="{{ route('api.account.list.account.route') }}">
        <input type="hidden" id="companyId" value="{{ $company->company_id }}">
        <input type="hidden" id="isAdmin" value="{{ $isAdmin }}">
        <div class="col-12">
            <div class="card">
                @if ($isAdmin)
                    <div class="card-header header-report-company">
                        <div class="scope-action-table-dropdown">
                            <div class="row">
                                <label for="filter-company" class="col-sm-6 col-form-label text-right">Journal Of Company</label>
                                <div class="col-sm-6">
                                    <select class="select2 form-control" id="filter-company">
                                        <option value=""> Choose Company ... </option>
                                        @foreach ($list_company as $company)
                                            <option data-id="{{ $company->company_name }}" value="{{ $company->id }}">{{ $company->company_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
                <div class="card-body">
                    <div class="button-items">
                        <button type="button"
                        id="buttonModalFilter"
                        class="btn btn-sm btn-primary btn-modal-filter"
                        data-toggle="modal"
                        data-target="#modal-form-filter"
                        {{ $isAdmin ? 'disabled' : '' }}
                    >
                            <i class="fa fa-fw fa-calendar mr-1"></i> Filter
                        </button>
                        <div class="scope-action-table">
                            <button id="expand-table"
                                style="width: 40px;"
                                class="btn btn-md btn-primary"
                                data-toggle="tooltip"
                                data-placement="left"
                                title="Fullscreen"
                            >
                                <i class="fa fa-expand"></i>
                            </button>
                        </div>
                    </div>
                    <div class="header-title-report">
                        <div>
                            <h3 id="title-company">{{ $company && $company->company ? $company->company->company_name : '' }}</h3>
                            <h5>Journal</h5>
                        </div>
                        <p id="period-report"></p>
                    </div>
                    <div class="table-responsive row-table-sticky mb-0" id="row-table-expand">
                        <div class="scope-action-table-min">
                            <button id="back-expand-table"
                                style="width: 40px;"
                                class="btn btn-md btn-primary"
                                data-toggle="tooltip"
                                data-placement="left"
                                title="Exit Fullscreen"
                            >
                                <i class="fa fa-times"></i>
                            </button>
                        </div>
                        <table class="table table-bordered table-hover table-sticky mb-0" id="journal-table">
                            <thead>
                                <tr>
                                    <th>DATE</th>
                                    <th>REFRENCE NUMBER</th>
                                    <th>ACCOUNT</th>
                                    <th>DESCRIPTION</th>
                                    <th>
                                        DEBIT BALANCE <br>
                                        (Rp)
                                    </th>
                                    <th>
                                        CREDIT BALANCE <br>
                                        (Rp)
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                        <div id="load_more" style="margin-top: 1%; text-align:center;">
                            <input type="hidden" id="idUrl">
                            <button type="button"
                                class="btn btn-primary btn-sm items-hide"
                                id="load_more_button"
                                style="width: 15%; margin-bottom: 2%;"
                            >
                                Load More
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div> <!-- end col -->
    </div> <!-- end row -->
    <!--end row-->

    <div class="modal fade" id="modal-form-filter" role="dialog" aria-labelledby="modal-form-filter" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="myLargeModalLabel">Filter Report</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <div class="modal-body">
                    <form id="form-filter-journal">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label class="label-required">Report Period</label>
                                        <div class="input-group">
                                            <input type="text" name="reportrange" id="reportrange" class="form-control" readonly>
                                            <div class="input-group-append">
                                                <span class="input-group-text"><i class="dripicons-calendar"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Account</label>
                                        <select id="accountId" name="account_id" class="select2 form-control">
                                            <option value="">Choose Account ... </option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div style="margin-top: 2%;">
                            <button type="button" class="btn btn-sm btn-danger" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-sm btn-primary" id="btn-submit">Filter</button>
                        </div>
                    </form>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

</div>
<!-- container -->
@endsection

@section('script')
<script src="{{ URL::asset('metrica/assets/plugins/moment/moment.js') }}"></script>
<script src="{{ URL::asset('metrica/assets/plugins/daterangepicker/daterangepicker.js') }}"></script>
<script src="{{ URL::asset('metrica/assets/plugins/select2/select2.min.js') }}"></script>
<script src="{{ URL::asset('metrica/assets/plugins/bootstrap-colorpicker/js/bootstrap-colorpicker.min.js') }}"></script>
<script src="{{ URL::asset('metrica/assets/plugins/timepicker/bootstrap-material-datetimepicker.js') }}"></script>
<script src="{{ URL::asset('metrica/assets/plugins/bootstrap-maxlength/bootstrap-maxlength.min.js') }}"></script>
<script src="{{ URL::asset('metrica/assets/plugins/bootstrap-touchspin/js/jquery.bootstrap-touchspin.min.js') }}"></script>
<script src="{{ URL::asset('metrica/assets/pages/jquery.forms-advanced.js') }}"></script>
<script src="{{ URL::asset('pages/js/global/expand.js') }}"></script>
<script src="{{ URL::asset('pages/js/global/stringHelper.js') }}"></script>
<script src="{{ URL::asset('pages/js/global/dateRangeHelper.js') }}"></script>
<script src="{{ URL::asset('pages/js/global/serializeObject.min.js') }}"></script>
<script src="{{ URL::asset('pages/js/report/journal/index.js') }}"></script>
@endsection
