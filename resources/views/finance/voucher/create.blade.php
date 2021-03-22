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
                        <li class="breadcrumb-item"><a href="javascript:void(0);">Pages</a></li>
                        <li class="breadcrumb-item active">Input Voucher</li>
                    </ol>
                </div>
                <h4 class="page-title">Input Voucher</h4>
            </div><!--end page-title-box-->
        </div><!--end col-->
    </div>
    <!--end row-->

    <div class="row">
        <div class="col-lg-12">
            <input type="hidden" value="{{json_encode($config_code)}}" id="configCodeAccount">
            <input type="hidden" id="isAdmin" value="{{ $isAdmin }}">

            <form class="form-parsley" id="myForm" action="#">
                @csrf
                <div class="card">
                    <div class="card-body">
                        <p class="text-muted mb-4">Please fill in the following form correctly.</p>
                        <div class="form-row">
                            @if ($isAdmin)
                                <div class="form-group col-md-3">
                                    <label class="label-required">Company</label>
                                    <select name="company_id" class="select2 form-control select-company" required>
                                        <option value=""> Choose Company ... </option>
                                        @foreach ($list_company as $company)
                                            <option value="{{ $company->id }}" {{ old('company_id') == $company->id ? 'selected' : ''}}>
                                                {{ $company->company_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            @endif
                            <div class="form-group col-md-3">
                                <label class="label-required">Type</label>
                                {!! Form::select('type_voucher', [], null, ['placeholder' => 'Select Type...','class' => 'select2 form-control mb-3 custom-select', 'id' => 'type_voucher', 'name' => 'type_voucher', 'style' => 'width: 100%; height:36px;']) !!}
                            </div>
                            <div class="form-group col-md-3">
                                <label class="label-required">Partner</label>
                                {!! Form::select('partner_id', [], null, ['placeholder' => 'Select Partner...', 'class' => 'select2 form-control mb-3 custom-select partner', 'id' => 'pelanggan', 'name' => 'pelanggan', 'style' => 'width: 100%; height:36px;']) !!}
                            </div>
                            <div class="form-group col-md-3">
                                <label class="label-required">Voucher Number</label>
                                {!! Form::text('voucher_number', $voucher_number, ['class' => 'form-control', 'id' => 'voucher_number', 'name' => 'voucher_number']) !!}
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-3">
                                <label class="label-required">Choose Account (Assets)</label>
                                {!! Form::select('account_id', [], null, ['class' => 'select2 form-control mb-3 custom-select', 'id' => 'akun', 'name' => 'akun', 'style' => 'width: 100%; height:36px;']) !!}
                            </div>
                            <div class="form-group col-md-3">
                                <label class="label-required">Date</label>
                                <input class="form-control" type="text" value="" id="tanggal" name="tanggal" autocomplete="off">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-5">
                                <label class="label-required">Additional Notes</label>
                                <input class="form-control" type="text" id="deskripsi" name="deskripsi">
                            </div>
                        </div>
                    </div><!--end card-body-->
                </div><!--end card-->
                <div class="card">
                    <div class="card-body">

                        <div class="form-row mb-2">
                            <table class="table table-bordered mb-0 table-centered">
                                <thead style="background-color: #50b380;">
                                    <tr>
                                        <th style="color:#fff; width: 25%;">No Invoice</th>
                                        <th style="color:#fff; width: 25%;">Description</th>
                                        <th style="color:#fff; width: 20%;">Amount Owed</th>
                                        <th style="color:#fff; width: 20%;">Amount Paid</th>
                                        <th style="color:#fff; width: 10%;">#</th>
                                    </tr>
                                </thead>
                            </table>
                            <table class="table table-bordered mb-0 table-centered" id="transactions_table">
                                <tbody>
                                    <tr>
                                        <td style="width: 25%;">
                                            <select class="select2 form-control invoice" id="noinvoice-0" ids="0" name="noinvoice[]" style="width: 100%;"></select>
                                            <input class="form-control" type="hidden" id="invoicenum-0" name="invoicenum[]" value="" style="text-align: right;" readonly>
                                        </td>
                                        <td style="width: 25%;">
                                            <input class="form-control" type="text" id="descinvoice-0" name="descinvoice[]" value="" style="text-align: left;" readonly>
                                        </td>
                                        <td style="width: 20%;">
                                            <input class="form-control" type="text" id="amountpaid-0" name="amountpaid[]" value="0" style="text-align: right;" readonly>
                                        </td>
                                        <td style="width: 20%;">
                                            <input class="form-control totalamount totalamount-0" type="text" id="amount-0" name="amount[]" value="0" style="text-align: right;" readonly>
                                        </td>
                                        <td style="width: 10%;">
                                            <div class="dropdown d-inline-block float-right">
                                                <button type="button" id="btn-add-0" class="btn btn-sm btn-gradient-primary waves-effect waves-light my-button-add" title="klik untuk tambah" onclick="addRow()"><i class="fas fa-plus"></i></button> &nbsp;
                                                <button type="button" id="btn-del-0" class="btn btn-sm btn-gradient-danger waves-effect waves-light my-button-delete" onclick="delRow(this)" title="klik untuk hapus"><i class="far fa-trash-alt ml-1"></i></button>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr style="background-color: #f0fff9; display: none;" class="tr-0">
                                        <td colspan="5" style="padding: 0px; width: 100%;">
                                            <table class="table table-bordered mb-0 table-centered">
                                                <tbody id="additional-expense-0" style="background-color: #f0fff9;">
                                                    <tr id="row-0-0" class="additional">
                                                        <td style="width: 25%">
                                                            &nbsp;
                                                        </td>
                                                        <td style="width: 25%">
                                                            <div class="dropdown d-inline-block float-right">
                                                                <button type="button" onclick="delRowAdditional(this)" id="btn-del-0-0" class="btn btn-sm btn-outline-purple my-button-delete-0"><i class="fas fa-minus"></i></button>
                                                                <button type="button" id="btn-add-0-0" class="btn btn-sm btn-outline-pink my-button-add-0" onclick="addRowAdditional(this)"><i class="fas fa-plus"></i></button>
                                                            </div>
                                                        </td>
                                                        <td style="width: 20%">
                                                            <select class="select2 form-control akunchoose myakun-0" id="akunpilih-0-0" name="akunpilih0[]" style="width: 100% !important;" ids="0">
                                                                <option selected="selected" value="">Choose Account...</option>
                                                            </select>
                                                        </td>
                                                        <td style="width: 20%"><input class="form-control amount-expense amountexpense-0" type="text" id="amountakun-0-0" name="amountakun0[]" value="0" style="text-align: right;" readonly></td>
                                                        <td style="width: 10%"><div id="info-purchase"></div></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </td>
                                    </tr>
                                    <tr style="background-color: #ffe0e0; display: none;" class="tr-0">
                                        <td style="width: 25%;"></td>
                                        <td style="width: 25%;"></td>
                                        <td style="width: 20%; text-align: right;">
                                            <b>Total</b>
                                        </td>
                                        <td style="width: 20%;">
                                            <input class="form-control subtotalamount" type="text" id="subamount0" name="subamount[]" value="0" style="text-align: right;" readonly>
                                        </td>
                                        <td style="width: 10%;"></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="form-row">
                            <div class="col-md-7">&nbsp;</div>
                            <div class="col-md-5">
                                <hr style="border: 0.2px solid #50649c"/>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="col-md-7">&nbsp;</div>
                            <div class="col-md-5">
                                <div class="form-group row">
                                    <label for="total" class="col-sm-4 col-form-label text-left"> Sub Total</label>
                                    <div class="col-sm-8">
                                        <input class="form-control" type="text" value="0" name="totalpayment" id="totalpayment" style="text-align: right;" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group mt-5">
                            <button type="button" id="submitForm" class="btn btn-gradient-primary dropdown-toggle btn-save" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Save <i class="mdi mdi-chevron-down"></i></button>
                            <div class="dropdown-menu">
                                <button type="button" class="dropdown-item btn-submit btn" id="saveDraft">
                                    Save as Draft
                                </button>
                                <button type="button" class="dropdown-item btn-submit btn" id="savePost">
                                    Save and Post
                                </button>
                            </div>
                            <a href="{{ route('finance.vouchers.index') }}"><button type="button" class="btn btn-gradient-danger waves-effect m-l-5">
                                Cancel
                            </button></a>

                            <input type="hidden" id="routeFetchPartner" value="{{ route('api.company.list.partner.have.invoice.not.yet.paid.route') }}">
                            <input type="hidden" id="routeFetchInvoice" value="{{ route('api.finance.list.by.partner.route') }}">
                            <input type="hidden" id="routeFetchAccount" value="{{ route('api.select2.get.list.expense.route') }}">
                            <input type="hidden" id="routeStoreVoucher" value="{{ route('api.finance.voucher.store.route') }}">

                            <input type="hidden" id="companyID" value="{{ ($isAdmin) ? '' : Auth::user()->company_id }}">
                            <input type="hidden" id="userId" value="{{ Auth::user()->id }}">
                            <input type="hidden" id="adminStatus" value="{{ $isAdmin }}">

                            <input type="hidden" id="routeFinanceConfigAccountAsset" value="{{ route('api.configuration.finance.load.route') }}">
                            <input type="hidden"
                                id="financeConfigStatus"
                                value="{{ App\Models\FinanceConfiguration::STATUS_ACTIVE }}"
                            >
                            <input type="hidden"
                                id="financeConfigCode"
                                value="{{ App\Models\FinanceConfiguration::CODE_ACCOUNT_ASSETS_VOUCHER }}"
                            >
                            <input type="hidden"
                                id="financeConfigCodeOtherExpense"
                                value="{{ App\Models\FinanceConfiguration::CODE_ACCOUNT_OTHER_EXPENSE_VOUCHER }}"
                            >
                        </div><!--end form-group-->
                    </div><!--end card-body-->
                </div><!--end card-->
            </form><!--end form-->
        </div> <!-- end col -->
    </div>
</div>
<!-- container -->

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
    <script src="{{ URL::asset('pages/js/global/stringHelper.js') }}"></script>
    <script src="{{ URL::asset('pages/js/global/checkConfiguration.js') }}"></script>
    <script src="{{ URL::asset('pages/js/voucher/formInput.js') }}"></script>
@endsection
