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
                        <li class="breadcrumb-item active">Create Vendor Invoice</li>
                    </ol>
                </div>
                <h4 class="page-title">Create Vendor Invoice</h4>
            </div><!--end page-title-box-->
        </div><!--end col-->
    </div>
    <!--end row-->

    <div class="row">
        <div class="col-lg-12">
            <input type="hidden" value="{{json_encode($config_code)}}" id="configCodeAccount">
            <input type="hidden" id="isAdmin" value="{{ $isAdmin }}">

            <div class="card">
                <div class="card-body">
                    <p class="text-muted mb-4">Please fill in the following form correctly.</p>

                    <form class="form-parsley" id="myForm" action="#">
                        @csrf
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
                                <label class="label-required">Partner</label>
                                {!! Form::select('partner_id', $data_partner, null, ['placeholder' => 'Select Partner', 'class' => 'select2 form-control mb-3 custom-select', 'id' => 'pelanggan', 'name' => 'pelanggan', 'style' => 'width: 100%; height:36px;']) !!}
                            </div>
                            <div class="form-group col-md-3">
                                <label class="label-required">Account Downpayment</label>
                                {!! Form::select('account_id_asset', [], null, ['placeholder' => 'Choose Account ...', 'class' => 'select2 form-control mb-3 custom-select', 'id' => 'account_id_asset', 'name' => 'account_id_asset', 'style' => 'width: 100%; height:36px;']) !!}
                            </div>
                            <div class="form-group col-md-3">
                                <label class="label-required">Invoice Number</label>
                                {!! Form::text('invoice_number', $invoice_number, ['class' => 'form-control', 'id' => 'noref', 'name' => 'noref']) !!}
                            </div>
                            <div class="form-group col-md-3">
                                <label class="label">Purchase Order</label>
                                <input class="form-control" type="text" value="" id="purchase_order" name="purchase_order">
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-2">
                                <label class="label-required">Date</label>
                                <input class="form-control" type="text" value="" id="tanggal" name="tanggal" autocomplete="off">
                            </div>
                            <div class="form-group col-md-2">
                                <label class="label-required">Due Date</label>
                                <input class="form-control" type="text" value="" id="duedate" name="duedate" autocomplete="off">
                            </div>
                            <div class="form-group col-md-2">
                                <label class="label">Payment Term</label>
                                <div class="input-group">
                                    {!! Form::select('payment_term', [14 => '14 days', 30 => '30 days'], null, ['placeholder' => 'Select', 'class' => 'form-control mb-3 custom-select change-term', 'id' => 'payment_term', 'name' => 'payment_term', 'style' => 'width: 100%; height:36px;']) !!}
                                </div>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label class="label-required">Additional Notes</label>
                                <input class="form-control" type="text" id="deskripsi" name="deskripsi">
                            </div>
                        </div>

                        <div class="form-row mb-2">
                            <table class="table table-bordered mb-0 table-centered">
                                <thead>
                                    <tr>
                                        <th style="width: 30%">
                                            Product / Service
                                            <button type="button" class=" ml-3 btn btn-outline-primary waves-effect waves-light btn-sm btn-modal">
                                            Add New</button>
                                        </th>
                                        <th style="width: 10%">Type</th>
                                        <th style="width: 10%">Qty</th>
                                        <th style="width: 20%">Price</th>
                                        <th style="width: 20%">Total Price</th>
                                        <th style="width: 10%">#</th>
                                    </tr>
                                </thead>
                                <tbody id="transactions_table">
                                    <tr>
                                        <td>
                                            <select class="select2 form-control getproduct" style="width: 100%;" id="product-0" ids="'+counter+'" name="product[]"></select>
                                        </td>
                                        <td>
                                            <div id="typeproduct-0"><span class="badge badge-soft-secondary">-</span></div>
                                        </td>
                                        <td>
                                            <input class="form-control changeqty only-number" type="text" id="qty-0" name="qty[]" value="0" style="text-align: right;">
                                        </td>
                                        <td>
                                            <input class="form-control change-price" type="text" id="harga-0" name="harga[]" value="0" style="text-align: right;">
                                            <input class="form-control gettype" type="hidden" id="type-0" name="type[]" value="" style="text-align: right;" readonly>
                                        </td>
                                        <td>
                                            <input class="form-control totalproduct" type="text" id="total-0" name="total[]" value="0" style="text-align: right;" readonly>
                                        </td>
                                        <td>
                                            <div class="dropdown d-inline-block float-right">
                                                <button type="button" id="btn-add-0" class="btn btn-sm btn-gradient-primary waves-effect waves-light my-button-add" title="klik untuk tambah" onclick="addRow()"><i class="fas fa-plus"></i></button> &nbsp;
                                                <button type="button" id="btn-del-0" class="btn btn-sm btn-gradient-danger waves-effect waves-light my-button-delete" onclick="delRow(this)" title="klik untuk hapus"><i class="far fa-trash-alt ml-1"></i></button>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="form-row">
                            <div class="col-md-6">&nbsp;</div>
                            <div class="col-md-6">
                                <hr style="border: 0.2px solid #50649c"/>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="col-md-6">&nbsp;</div>
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label for="total-pajak" class="col-sm-4 col-form-label text-left">Subtotal</label>
                                    <div class="col-sm-8">
                                        <input class="form-control" type="text" value="0" id="total-totalbarangjasa" name="totalbarangjasa" style="text-align: right;" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="col-md-6">&nbsp;</div>
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label for="diskon" class="col-sm-4 col-form-label text-left">VAT</label>
                                    <div class="col-sm-8">
                                        <input class="form-control" type="text" value="0" id="harga-pajak" name="hargapajak" style="text-align: right;">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="col-md-6">&nbsp;</div>
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label for="total-pajak" class="col-sm-4 col-form-label text-left">WithHolding Tax</label>
                                    <div class="col-sm-8">
                                        <input class="form-control" type="text" value="0" id="pph" name="pph" style="text-align: right;" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="col-md-6">&nbsp;</div>
                            <div class="col-md-6">
                                <hr style="border: 0.2px solid #50649c"/>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="col-md-6">&nbsp;</div>
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label for="total" class="col-sm-4 col-form-label text-left">Total</label>
                                    <div class="col-sm-8">
                                        <input class="form-control" type="text" value="0" id="subtotal" name="subtotal" style="text-align: right;" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="col-md-6">&nbsp;</div>
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label for="total" class="col-sm-4 col-form-label text-left">Downpayment ( - )</label>
                                    <div class="col-sm-8">
                                        <input class="form-control auto-currency" type="text" value="0" id="uangmuka" style="text-align: right;" name="uangmuka" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="col-md-6">&nbsp;</div>
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label for="total" class="col-sm-4 col-form-label text-left">Total Amount</label>
                                    <div class="col-sm-8">
                                        <input class="form-control" type="text" value="0" name="sisanominal" id="sisauang" style="text-align: right;" readonly>
                                        <div id="validate-sisa-minus" style="color: red; display: none;">
                                            The remaining nominal cannot be minus.
                                        </div>
                                        <div id="validate-sisa-nol" style="color: red; display: none;">
                                            The remaining nominal cannot be zero (0).
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group mt-5">
                            <button type="button" id="submitForm" class="btn btn-gradient-primary dropdown-toggle btn-save" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" disabled>Save <i class="mdi mdi-chevron-down"></i></button>
                            <div class="dropdown-menu">
                                <button type="button" class="dropdown-item btn-submit btn" id="saveDraft">
                                    Save as Draft
                                </button>
                                <button type="button" class="dropdown-item btn-submit btn" id="savePost">
                                    Save and Post
                                </button>
                            </div>
                            <a href="{{ route('finance.invoices.payable.index') }}"><button type="button" class="btn btn-gradient-danger waves-effect m-l-5">
                                Cancel
                            </button></a>

                            <input type="hidden" id="routeListProductCategory" value="{{ route('api.product.category.list.by.company.route') }}">
                            <input type="hidden" id="routeListProduct" value="{{ route('api.product.list.by.company.route') }}">
                            <input type="hidden" id="routeListPartnerVendorBothCompany" value="{{ route('api.select2.get.list.partner.vendor.both.route') }}">
                            <input type="hidden" id="routeListAccountAssetCompany" value="{{ route('api.select2.get.list.account.asset.company.route') }}">

                            <input type="hidden" id="routeStoreProductCategory" value="{{ route('api.product.category.store.route') }}">
                            <input type="hidden" id="routeStoreInvoicePayable" value="{{ route('api.finance.invoice.payable.store.route') }}">
                            <input type="hidden" id="routeStoreProduct" value="{{ route('api.product.store.route') }}">
                            <input type="hidden" id="routeShowInvoice" value="{{ route('finance.invoices.payable.show', ['payable' => ':id']) }}">
                            <input type="hidden" id="routeCompanyDetail" value="{{ route('api.company.detail.route') }}">

                            <input type="hidden" id="companyID" value="{{ ($isAdmin) ? '' : Auth::user()->company_id }}">
                            <input type="hidden" id="userId" value="{{ Auth::user()->id }}">
                            <input type="hidden" id="adminStatus" value="{{ $isAdmin }}">
                            <input type="hidden" id="vatEnabledCompany" value="{{ $data_company->vat_enabled }}">

                            <input type="hidden" id="routeFinanceConfigAccountDp" value="{{ route('api.configuration.finance.load.route') }}">
                            <input type="hidden"
                                id="financeConfigStatus"
                                value="{{ App\Models\FinanceConfiguration::STATUS_ACTIVE }}"
                            >
                            <input type="hidden"
                                id="financeConfigCode"
                                value="{{ App\Models\FinanceConfiguration::CODE_ACCOUNT_DP_INVOICE_PAYABLE }}"
                            >
                        </div><!--end form-group-->
                    </form><!--end form-->
                </div><!--end card-body-->
            </div><!--end card-->
        </div> <!-- end col -->
    </div>
</div>
<!-- container -->

<!-- modal -->
@include('finance.invoice.payable.partials.modal_add_product')

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
    <script src="{{ URL::asset('pages/js/invoice/payable/formInput.js') }}"></script>
@endsection
