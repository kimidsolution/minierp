@extends('layouts.backend.metrica.master')

@section('css')

<!-- DataTables -->
<link href="{{ URL::asset('metrica/plugins/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ URL::asset('metrica/plugins/datatables/buttons.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ URL::asset('metrica/assets/plugins/select2/select2.min.css') }}" rel="stylesheet" type="text/css" />

@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="page-title-box">
                <h4 class="page-title">Transactions {{ $transaction_type == false ? 'Receivable' : 'Payable' }}</h4>
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
                <input type="hidden" id="transactionType" value="{{ $transaction_type }}">
                <input type="hidden" id="routeFetch" value="{{ route('api.datatable.transactions.main.route') }}">
                <input type="hidden" id="routeDelete" value="{{ route('api.finance.transaction.destroy.route') }}">
                <input type="hidden" id="routePosted" value="{{ route('api.finance.transaction.posted.route') }}">
                <input type="hidden" id="userId" value="{{ Auth::user()->id }}">
                <input type="hidden" id="isAdmin" value="{{ $isAdmin }}">
                <input type="hidden" id="companyId" value="{{ Auth::user()->company_id }}">
                <div class="card-body">
                    <div class="button-items mb-4 ml-6">
                        <a href="{{ $transaction_type == false ? route('finance.transactions.receivable.create') : route('finance.transactions.payable.create')  }}"
                            class="btn btn-primary waves-effect waves-light"
                        >
                            Create Transaction <i class="far fa-plus-square ml-1"></i>
                        </a>
                        @if ($isAdmin)
                            <button id="show-all" class="btn btn-primary items-hide" data-toggle="tooltip" data-placement="left"
                                title="Show All Partners" data-id="false">
                                Show All
                            </button>
                            <button id="show-active" class="btn btn-primary items-hide" data-toggle="tooltip" data-placement="left"
                                title="Show Partners Active" data-id="true">
                                Show Active
                            </button>
                            <div class="scope-action-table-dropdown">
                                <div class="form-group row">
                                    <label for="filter-company" class="col-sm-6 col-form-label text-right"></label>
                                    <div class="col-sm-6">
                                        <select class="select2 form-control" id="filter-company">
                                            <option value=""> Choose Company ... </option>
                                            @foreach ($list_company as $company)
                                                <option value="{{ $company->id }}">{{ $company->company_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                    <div class="table-responsive">
                        <table id="transaction-datatable" class="table table-striped table-bordered nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                            <thead>
                                <tr>
                                    <th>Transaction Date</th>
                                    <th>Reference Number</th>
                                    <th>Transaction</th>
                                    <th>Nominal Debit</th>
                                    <th>Nominal Credit</th>
                                    <th>Status</th>
                                    <th class="text-center">Action</th>
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
<script src="{{ URL::asset('metrica/plugins/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ URL::asset('metrica/plugins/datatables/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ URL::asset('metrica/assets/plugins/accounting/accounting.js') }}"></script>
<script src="{{ URL::asset('pages/js/global/time.js') }}"></script>
<script src="{{ URL::asset('pages/js/global/notifications.js') }}"></script>
<script src="{{ URL::asset('pages/js/global/stringHelper.js') }}"></script>
<script src="{{ URL::asset('pages/js/transaction/index.js') }}"></script>
<script src="{{ URL::asset('metrica/assets/plugins/moment/moment.js') }}"></script>
<script src="{{ URL::asset('metrica/assets/plugins/daterangepicker/daterangepicker.js') }}"></script>
<script src="{{ URL::asset('metrica/assets/plugins/select2/select2.min.js') }}"></script>
<script src="{{ URL::asset('metrica/assets/plugins/timepicker/bootstrap-material-datetimepicker.js') }}"></script>
<script src="{{ URL::asset('metrica/assets/plugins/bootstrap-colorpicker/js/bootstrap-colorpicker.min.js') }}"></script>
<script src="{{ URL::asset('metrica/assets/plugins/bootstrap-maxlength/bootstrap-maxlength.min.js') }}"></script>
<script src="{{ URL::asset('metrica/assets/plugins/bootstrap-touchspin/js/jquery.bootstrap-touchspin.min.js') }}"></script>
<script src="{{ URL::asset('metrica/assets/pages-material/jquery.forms-advanced.js') }}"></script>

@endsection
