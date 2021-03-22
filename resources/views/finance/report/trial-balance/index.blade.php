@extends('layouts.backend.metrica.master')

@section('css')
    <link href="{{ URL::asset('metrica/assets/plugins/datepicker/css/bootstrap-datepicker.min.css') }}" rel="stylesheet" />
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
                        <li class="breadcrumb-item active">Trial Balance</li>
                    </ol>
                </div>
                <h4 class="page-title">Trial Balance</h4>
            </div>
            <!--end page-title-box-->
        </div>
        <!--end col-->
    </div>
    <!-- end page title end breadcrumb -->
    <div class="row">
        <input type="hidden" id="routeFetch" value="{{ route('api.finance.report.trial.balance.route') }}">
        <input type="hidden" id="companyId" value="{{ $company->company_id }}">
        <input type="hidden" id="isAdmin" value="{{ $isAdmin }}">
        <div class="col-12">
            <div class="card">
                @if ($isAdmin)
                    <div class="card-header header-report-company">
                        <div class="scope-action-table-dropdown">
                            <div class="row">
                                <label for="filter-company" class="col-sm-6 col-form-label text-right">Trial Balance Of Company</label>
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
                                class="btn btn-sm btn-primary"
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
                            <h3 id="title-company">
                                {{ $company && $company->company ? $company->company->company_name : '' }}
                            </h3>
                            <p class="text-muted" style="width: 500px; margin: auto;">
                                <i class="fas fa-map-marker-alt text-info"></i>
                                <span id="loc-company">
                                    {{$company && $company->company ? $company->company->address : ''}}
                                </span>
                            </p>
                            <h5>Trial Balance</h5>
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
                        <table id="trial-balance-table" class="table table-bordered table-hover table-sticky mb-0">
                            <thead>
                                <tr class="sticky-header-first" id="column-header">
                                    <th id="col-code" class="hard_left-head" style="width: 10%;" rowspan="3">ACCOUNT CODE</th>
                                    <th id="col-desc" class="next_left-head" style="width: 20%;" rowspan="3">DESCRIPTION</th>
                                    <th id="col-open" style="width: 10%;" rowspan="3">OPENING BALANCE</th>
                                    <th id="col-mutation" style="width: 10%;" rowspan="3">MUTATION</th>
                                    <th id="col-netmutation" style="width: 10%;" rowspan="3">NET MUTATION</th>
                                    <th id="col-end" style="width: 10%;" rowspan="3">ENDING BALANCE</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div> <!-- end col -->
    </div> <!-- end row -->
    <!--end row-->

    <div class="modal fade" id="modal-form-filter" role="dialog" aria-labelledby="modal-form-filter" aria-hidden="true">
        <div class="modal-dialog modal-md" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="myLargeModalLabel">Filter Report</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <div class="modal-body">
                    <form id="form-filter-month-period">
                        <div class="form-row mb-3">
                            <div class="col-md-6 mb-3">
                                <div class="form-group">
                                    <label for="from-period">From Period</label>
                                    <input type="text"
                                        readonly
                                        class="form-control date-month"
                                        name="fromPeriod"
                                        id="from-period"
                                    >
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="form-group">
                                    <label for="to-period">To Period</label>
                                    <input type="text"
                                        readonly
                                        class="form-control date-month"
                                        name="toPeriod"
                                        id="to-period"
                                    >
                                </div>
                            </div>
                        </div>
                        <div style="margin-top: 5%;">
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
<script src="{{ URL::asset('metrica/assets/plugins/datepicker/js/bootstrap-datepicker.min.js') }}"></script>
<script src="{{ URL::asset('metrica/assets/plugins/datepicker/js/bootstrap-datepicker.id.min.js') }}"></script>
<script src="{{ URL::asset('metrica/plugins/jquery-validation/jquery.validate.min.js') }}"></script>
<script src="{{ URL::asset('metrica/plugins/jquery-validation/additional-methods.min.js') }}"></script>
<script src="{{ URL::asset('metrica/assets/plugins/accounting/accounting.js') }}"></script>
<script src="{{ URL::asset('pages/js/global/expand.js') }}"></script>
<script src="{{ URL::asset('pages/js/global/serializeObject.min.js') }}"></script>
<script src="{{ URL::asset('pages/js/global/stringHelper.js') }}"></script>
<script src="{{ URL::asset('metrica/assets/js/jq-validation/form_filter_month.js') }}"></script>
<script src="{{ URL::asset('pages/js/report/trial-balance/index.js') }}"></script>
@endsection
