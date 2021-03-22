@extends('layouts.backend.metrica.master')

@section('css')
    <link href="{{ URL::asset('metrica/assets/plugins/daterangepicker/daterangepicker.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ URL::asset('metrica/assets/plugins/timepicker/bootstrap-material-datetimepicker.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ URL::asset('metrica/assets/plugins/select2/select2.min.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('content')

<div class="section-loader">
    <div class="spinner-border thumb-loader text-primary part-loader"></div>
</div>
<div class="container-fluid">
    <!-- Page-Title -->
    <div class="row">
        <div class="col-sm-12">
            <div class="page-title-box">
                <div class="float-right">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="javascript:void(0);">Finance</a></li>
                        <li class="breadcrumb-item"><a href="javascript:void(0);">Report</a></li>
                        <li class="breadcrumb-item active">General Ledger</li>
                    </ol>
                </div>
                <h4 class="page-title">General Ledger</h4>
            </div>
            <!--end page-title-box-->
        </div>
        <!--end col-->
    </div>
    <!-- end page title end breadcrumb -->

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
        <input type="hidden" id="routeFetch" value="{{ route('api.finance.report.general.ledger.route') }}">
        <input type="hidden" id="routeFetchAccount" value="{{ route('api.account.list.account.route') }}">
        <input type="hidden" id="companyId" value="{{ $company->company_id }}">
        <input type="hidden" id="isAdmin" value="{{ $isAdmin }}">
        <div class="col-12">
            <div class="card">
                @if ($isAdmin)
                    <div class="card-header header-report-company">
                        <div class="scope-action-table-dropdown">
                            <div class="row">
                                <label for="filter-company" class="col-sm-6 col-form-label text-right">General Ledger Of Company</label>
                                <div class="col-sm-6">
                                    <select class="select2 form-control" id="filter-company">
                                        <option value=""> Choose Company ... </option>
                                        @foreach ($list_company as $company)
                                            <option data-id="{{ $company->company_name.'-'.$company->address }}"
                                                value="{{ $company->id }}">
                                                {{ $company->company_name }}
                                            </option>
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
                    <div class="header-title-report" style="margin-bottom: 1%;">
                        <div>
                            <h3 id="title-company" style="padding-left: 50px;">
                                {{ $company && $company->company ? $company->company->company_name : '' }}
                            </h3>
                            <p class="text-muted" style="width: 500px; margin: auto;">
                                <i class="fas fa-map-marker-alt text-info"></i>
                                <span id="loc-company">
                                    {{$company && $company->company ? $company->company->address : ''}}
                                </span>
                            </p>
                            <h5>General Ledger</h5>
                        </div>
                        <p id="period-report"></p>
                    </div>
                    <div class="col-md-12">
                        <div class="row" style="margin-bottom: 10px;">
                            <div class="col-md-6">
                                <div style="text-align: left;">
                                    <table>
                                        <tr>
                                            <td width="150px"><b>Account Name</b></td>
                                            <td width="10px" style="text-align: left">:</td>
                                            <td style="text-align: left" id="txt_account_name"></td>
                                        </tr>
                                        <tr>
                                            <td width="150px"><b>Account Number</b></td>
                                            <td width="10px" style="text-align: left">:</td>
                                            <td style="text-align: left" id="txt_account_number"></td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div style="float:right;">
                                    <div class="btn-group btn-group-toggle" data-toggle="buttons">
                                        <label class="btn btn-outline-primary btn-sm">
                                            <input type="radio" value="true" name="options_zero"> Enable Zero
                                        </label>
                                        <label class="btn btn-outline-primary btn-sm">
                                            <input type="radio" value="false" name="options_zero" checked> Disable Zero
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive-lg row-table-sticky mb-0" id="row-table-expand">
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
                        <table class="table table-bordered table-hover table-sticky mb-0" id="general-ledger-table">
                            <thead>
                                <tr>
                                    <th>REFRENCE NUMBER</th>
                                    <th>DATE</th>
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
                                    <th>
                                        BALANCE <br>
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
                    <form id="form-filter-general-ledger">
                        <div class="form-row mb-3">
                            <div class="col-md-6 mb-3">
                                <div class="form-group">
                                    <label class="label-required" for="from-period">Report Period</label>
                                    <div class="input-group">
                                        <input type="text" name="reportrange" id="reportrange" class="form-control" readonly>
                                        <div class="input-group-append">
                                            <span class="input-group-text"><i class="dripicons-calendar"></i></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="form-group">
                                    <label class="label-required" for="to-period">Account</label>
                                    <select id="accountId" name="account_id" class="select2 form-control">
                                        <option value="">Choose Account ... </option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <button type="button" class="btn btn-sm btn-danger" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-sm btn-primary" id="btn-submit">Filter</button>
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
<script src="{{ URL::asset('metrica/plugins/jquery-validation/jquery.validate.min.js') }}"></script>
<script src="{{ URL::asset('metrica/assets/plugins/accounting/accounting.js') }}"></script>
<script src="{{ URL::asset('metrica/assets/pages/jquery.forms-advanced.js') }}"></script>
<script src="{{ URL::asset('pages/js/global/expand.js') }}"></script>
<script src="{{ URL::asset('pages/js/global/stringHelper.js') }}"></script>
<script src="{{ URL::asset('pages/js/global/dateRangeHelper.js') }}"></script>
<script src="{{ URL::asset('pages/js/global/serializeObject.min.js') }}"></script>
<script src="{{ URL::asset('pages/js/report/general_ledger/index.js') }}"></script>
@endsection
