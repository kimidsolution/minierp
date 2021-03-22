@extends('layouts.backend.metrica.master')

@section('css')
    <!-- Plugins css -->
    <link href="{{ URL::asset('metrica/assets/plugins/daterangepicker/daterangepicker.css') }}" rel="stylesheet" />
    <link href="{{ URL::asset('metrica/assets/plugins/select2/select2.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ URL::asset('metrica/assets/plugins/bootstrap-colorpicker/css/bootstrap-colorpicker.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ URL::asset('metrica/assets/plugins/timepicker/bootstrap-material-datetimepicker.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('metrica/assets/plugins/bootstrap-touchspin/css/jquery.bootstrap-touchspin.min.css') }}" rel="stylesheet" />
    <link href="{{ URL::asset('metrica/assets/plugins/datepicker/css/bootstrap-datepicker.min.css') }}" rel="stylesheet" />
@endsection

@section('content')

<div class="container-fluid">
    <!-- Page-Title -->
    <div class="row">
        <div class="col-sm-12">
            <div class="page-title-box">
                <div class="float-right">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="javascript:void(0);">Finance</a></li>
                        <li class="breadcrumb-item"><a href="javascript:void(0);">Transactions</a></li>
                        <li class="breadcrumb-item active">Create Transaction {{ $title_transaction }}</li>
                    </ol>
                </div>
                <h4 class="page-title">Create Transaction {{ $title_transaction }}</h4>
            </div><!--end page-title-box-->
        </div><!--end col-->
    </div>
    <!--end row-->
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <p class="text-muted mb-4">
                        Please fill in the following form correctly.
                    </p>
                    <input type="hidden" id="isAdmin" value="{{ $isAdmin }}">
                    <input type="hidden" id="routeFetchAccount" value="{{ route('api.account.list.account.route') }}">
                    <input type="hidden" id="routeFetchStore" value="{{ route('api.finance.transaction.store.route') }}">
                    <input type="hidden" id="routeCheckRefNumber" value="{{ route('api.finance.transaction.checkrefnumber.route') }}">
                    <input type="hidden" id="routeFetchInvoice" value="{{ route('api.select2.get.list.invoice.company.route') }}">
                    <input type="hidden" id="routeFetchVoucher" value="{{ route('api.select2.get.list.voucher.company.route') }}">
                    <form class="form-parsley" id="jq-validation-form-create">
                        <input type="hidden" id="transactionType" name="transaction_type" value="{{ $value_transaction_type }}">
                        <input type="hidden" name="user_id" value="{{ Auth::user()->id }}">
                        <div class="form-row">
                            @if ($isAdmin)
                            <div class="form-group col-md-3">
                                <label class="label-required">Company</label>
                                <select id="companyId" name="company_id" class="select2 form-control" required>
                                    <option value=""> Choose Company ... </option>
                                    @foreach ($list_company as $company)
                                    <option value="{{ $company->id }}"
                                        {{ old('company_id') == $company->id ? 'selected' : ''}}>
                                        {{ $company->company_name }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            @else
                                <input type="hidden" id="companyId" name="company_id"
                                    value="{{ Auth::user()->company_id }}">
                            @endif
                            <div class="form-group col-md-3">
                                <label class="label-required">Type</label>
                                <select id="modelType" name="model_type" class="select2 form-control" required>
                                    <option value=""> Choose Type ... </option>
                                    @foreach ($list_model_type as $type)
                                        <option data-id="{{ $type['id'] }}" value="{{ $type['id'] }}"
                                            {{ old('model_type') == $type['id'] ? 'selected' : ''}}>
                                            {{ $type['name'] }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-3">
                                <label class="label-required">Transaction Date</label>
                                <input class="form-control" type="text" id="transactionDate" name="transaction_date" autocomplete="off">
                            </div>
                            <div class="form-group col-md-3" id="divReferenceNumber">
                                <label>Reference Number</label>
                                <input class="form-control" type="text" value="{{ old('reference_number') }}" id="refrenceNumber" name="reference_number">
                            </div>
                            <div class="form-group col-md-3 items-hide" id="divProcessing">
                                <label>Reference Number</label>
                                <span class="text-center" style="display: block; letter-spacing: 2px; margin: 10px;">Loading ...</span>
                            </div>
                            <div class="form-group col-md-3 items-hide" id="divModelId">
                                <label class="label-required">Reference Number</label>
                                <select class="select2 form-control" style="width: 100%;" id="modelId" name="model_id" required>
                                </select>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label class="label-required">Description</label>
                                <textarea name="description" id="description" class="form-control" rows="2"></textarea>
                            </div>
                        </div>

                        <div class="form-row mb-2">
                            <div class="col-lg-12">
                                <div class="card">
                                    <h5 class="card-header bg-primary text-white mt-0">Transaction Line</h5>
                                    <div class="card-body">
                                        <table class="table table-bordered mb-0 table-centered" style="border: none;">
                                            <thead>
                                                <tr>
                                                    <th style="width: 25%">Account</th>
                                                    <th style="width: 20%">Debit</th>
                                                    <th style="width: 20%">Credit</th>
                                                    <th style="width: 10%"></th>
                                                </tr>
                                            </thead>
                                            <tbody id="transactions_table">
                                                <tr>
                                                    <td>
                                                        <select class="select2 form-control selectorAccount" style="width: 100%;" id="account-0" ids="0" name="account[]" required>
                                                            <option value=""> Choose Account ... </option>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <input class="form-control debit_amount currencyInput currencyInputDebit" type="text" id="debit_amount-0" name="debit_amount[]" value="0" style="text-align: right;">
                                                    </td>
                                                    <td>
                                                        <input class="form-control credit_amount currencyInput currencyInputCredit" type="text" id="credit_amount-0" name="credit_amount[]" value="0" style="text-align: right;">
                                                    </td>
                                                    <td class="text-center">
                                                        <div class="dropdown d-inline-block">
                                                            <button type="button"
                                                                id="btn-add-0"
                                                                style="margin: 10px;"
                                                                title="Click for add row"
                                                                onclick="addRow()"
                                                                class="btn btn-sm btn-gradient-primary waves-effect waves-light my-button-add"
                                                            >
                                                                <i class="fas fa-plus"></i>
                                                            </button>
                                                            <button type="button"
                                                                id="btn-del-0"
                                                                style="margin: 10px;"
                                                                onclick="delRow(this)"
                                                                title="Click for remove row"
                                                                class="btn btn-sm btn-gradient-danger waves-effect waves-light my-button-delete"
                                                            >
                                                                <i class="far fa-trash-alt"></i>
                                                            </button>
                                                        </div>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                        <table id="footerTable"
                                            class="table table-sm table-borderless mb-0 table-centered"
                                            style="border: none; margin-top: 20px;">
                                            <tr>
                                                <th style="width: 25%"></th>
                                                <th style="width: 20%"></th>
                                                <th style="width: 20%"></th>
                                                <th style="width: 10%"></th>
                                            </tr>
                                            <tr>
                                                <td style="font-weight: bolder; font-size: 1rem; background-color: #f1f5fa;" class="text-center">
                                                    Total
                                                </td>
                                                <td>
                                                    <input class="form-control" type="text" id="totalDebit" name="totalDebitAmount" value="0" style="text-align: right;" readonly>
                                                </td>
                                                <td>
                                                    <input class="form-control" type="text" id="totalCredit" name="totalCreditAmount" value="0" style="text-align: right;" readonly>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td></td>
                                                <td colspan="2">
                                                    <h5 style="margin-top: 10px;"
                                                        id="checkingBalance"
                                                        class="animated fadeIn text-center"
                                                    >
                                                    </h5>
                                                </td>
                                            </tr>
                                        </table>
                                    </div><!--end card-body-->
                                </div><!--end card-->
                            </div>
                        </div>

                        <div class="form-group mt-4 float-right">
                            <a href="{{ $route_transaction_type  }}"class="btn btn-gradient-danger waves-effect m-l-5">Cancel</a>
                            <button type="button"
                                disabled
                                id="submitForm"
                                aria-haspopup="true"
                                aria-expanded="false"
                                data-toggle="dropdown"
                                class="btn btn-gradient-primary dropdown-toggle"
                            >
                                Save <i class="mdi mdi-chevron-down"></i>
                            </button>
                            <div class="dropdown-menu">
                                <button type="submit" class="dropdown-item btn-submit" id="saveDraft">
                                    Save as Draft
                                </button>
                                <button type="submit" class="dropdown-item btn-submit" id="savePost">
                                    Save and Post
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('script')
    <!-- Plugins js -->
    <script src="{{ URL::asset('metrica/assets/plugins/moment/moment.js') }}"></script>
    <script src="{{ URL::asset('metrica/assets/plugins/daterangepicker/daterangepicker.js') }}"></script>
    <script src="{{ URL::asset('metrica/assets/plugins/select2/select2.min.js') }}"></script>
    <script src="{{ URL::asset('metrica/assets/plugins/bootstrap-colorpicker/js/bootstrap-colorpicker.min.js') }}"></script>
    <script src="{{ URL::asset('metrica/assets/plugins/timepicker/bootstrap-material-datetimepicker.js') }}"></script>
    <script src="{{ URL::asset('metrica/assets/plugins/bootstrap-maxlength/bootstrap-maxlength.min.js') }}"></script>
    <script src="{{ URL::asset('metrica/assets/plugins/bootstrap-touchspin/js/jquery.bootstrap-touchspin.min.js') }}"></script>
    <script src="{{ URL::asset('metrica/assets/plugins/accounting/autoNumeric.min.js') }}"></script>
    <script src="{{ URL::asset('metrica/assets/plugins/datepicker/js/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ URL::asset('metrica/assets/plugins/datepicker/js/bootstrap-datepicker.id.min.js') }}"></script>
    <script src="{{ URL::asset('metrica/assets/pages-material/jquery.forms-advanced.js') }}"></script>

    <!-- Validation jquery -->
    <script src="{{ URL::asset('metrica/plugins/jquery-validation/jquery.validate.min.js') }}"></script>
    <script src="{{ URL::asset('metrica/plugins/jquery-validation/additional-methods.min.js') }}"></script>

    <script src="{{ URL::asset('metrica/assets/plugins/accounting/accounting.js') }}"></script>
    <script src="{{ URL::asset('metrica/assets/plugins/accounting/autoNumeric.min.js') }}"></script>
    <script src="{{ URL::asset('metrica/assets/js/jq-validation/form_transactions.js') }}"></script>
    <script src="{{ URL::asset('pages/js/global/time.js') }}"></script>
    <script src="{{ URL::asset('pages/js/global/notifications.js') }}"></script>
    <script src="{{ URL::asset('pages/js/global/stringHelper.js') }}"></script>
    <script src="{{ URL::asset('pages/js/global/serializeObject.min.js') }}"></script>
    <script src="{{ URL::asset('pages/js/transaction/form.js') }}"></script>
@endsection
