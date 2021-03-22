@extends('layouts.backend.metrica.master')

@section('css')
    <link href="{{ URL::asset('metrica/assets/plugins/dropify/css/dropify.min.css') }}" rel="stylesheet">

    <!-- Plugins css -->
    <link href="{{ URL::asset('metrica/assets/plugins/daterangepicker/daterangepicker.css') }}" rel="stylesheet" />
    <link href="{{ URL::asset('metrica/assets/plugins/select2/select2.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ URL::asset('metrica/assets/plugins/bootstrap-colorpicker/css/bootstrap-colorpicker.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ URL::asset('metrica/assets/plugins/timepicker/bootstrap-material-datetimepicker.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('metrica/assets/plugins/bootstrap-touchspin/css/jquery.bootstrap-touchspin.min.css') }}" rel="stylesheet" />
@endsection

@section('content')
<div class="container-fluid">
    <!-- Page-Title -->
    <div class="row">
        <div class="col-sm-12">
            <div class="page-title-box">
                <div class="float-right">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/">Home</a></li>
                        <li class="breadcrumb-item"><a href="/currencies">Company</a></li>
                        <li class="breadcrumb-item active">Edit Company</li>
                    </ol>
                </div>
                <h4 class="page-title">Edit Company</h4>
            </div><!--end page-title-box-->
        </div><!--end col-->
    </div>
    <!--end row-->

    @if ($errors->any())
        @foreach ($errors->all() as $error)
        <div class="row">
            <div class="col-lg-12">
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true"><i class="mdi mdi-close"></i></span>
                    </button>
                    <strong>Info!</strong> {{ $error }}
                </div>
            </div>
        </div>
        @endforeach
    @endif

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
        <div class="col-lg-6">
            <div class="card">
                <div class="card-body">
                    <form class="form-parsley" method="POST" action="{{ route('admin.companies.update', ['company' => $company->id]) }}" role="form" enctype="multipart/form-data" id="jq-validation-form-create">
                    @csrf
                    {{ Form::hidden('_method', 'PUT') }}
                    {{ Form::hidden('id', $company->id) }}

                    <div class="form-row d-flex justify-content-center">
                        <div class="col-md-12">
                            <div class="form-group">
                                <p class="text-muted mb-4">Please fill in the following form correctly.</p>
                            </div>
                            <div class="form-group">
                                <label class="label-required">Company Name</label>
                                {!! Form::text('company_name', old('company_name', $company->company_name), ['class' => 'form-control', 'autocomplete' => 'off']) !!}
                            </div>
                            <div class="form-group">
                                {!! Form::label('Brand Name') !!}
                                {!! Form::text('brand_name', old('brand_name', $company->brand_name), ['class' => 'form-control', 'autocomplete' => 'off']) !!}
                            </div>
                            <div class="form-group">
                                <label class="label-required">Address</label>
                                {!! Form::textarea('address', old('brand_name', $company->address), ['rows' => 5, 'class' => 'form-control', 'required']) !!}
                            </div>
                            <div class="form-group">
                                <label class="label-required">City</label>
                                {!! Form::text('city', old('city', $company->city), ['class' => 'form-control', 'autocomplete' => 'off']) !!}
                            </div>
                            <div class="form-group">
                                <label class="label-required">Country</label>
                                {!! Form::text('country', old('country', $company->country), ['class' => 'form-control', 'autocomplete' => 'off']) !!}
                            </div>
                            <div class="form-group">
                                <label class="label-required">Email</label>
                                {!! Form::text('email', old('email', $company->email), ['class' => 'form-control', 'autocomplete' => 'off']) !!}
                            </div>
                            <div class="form-group">
                                <label class="label-required">Phone</label>
                                {!! Form::text('phone', old('phone', $company->phone_number), ['class' => 'form-control only-number', 'autocomplete' => 'off']) !!}
                            </div>
                            <div class="form-group">
                                {!! Form::label('Fax') !!}
                                {!! Form::text('fax', old('fax', $company->fax), ['class' => 'form-control', 'autocomplete' => 'off']) !!}
                            </div>
                            <div class="form-group">
                                {!! Form::label('NPWP') !!}
                                {!! Form::text('tax_id_number', old('fax', $company->tax_id_number), ['class' => 'form-control', 'autocomplete' => 'off']) !!}
                            </div>
                            <div class="form-group">
                                {!! Form::label('Website') !!}
                                {!! Form::text('website', old('fax', $company->website), ['class' => 'form-control', 'autocomplete' => 'off']) !!}
                            </div>
                            <div class="form-group">
                                <label class="label-required">Currency</label>
                                <select id="currency_id" class="select2 form-control" required disabled>
                                    <option value=""> Choose Currency ... </option>
                                    @foreach ($currencies as $currency)
                                        <option value="{{ $currency->id }}" {{ old('currency_id') == $currency->id ? 'selected' : ''}} {{ $company->currency_id == $currency->id ? 'selected' : '' }}>
                                            {{ $currency->name . ' ('.$currency->symbol.')' }}
                                        </option>
                                    @endforeach
                                </select>
                                <input type="hidden" name="currency_id" value="{{ $company->currency_id }}">
                            </div>
                            <div class="form-group mb-2 row">
                                <label class="label-required col-md-5 my-1 control-label">Enable Value Add Tax?</label>
                                <div class="col-md-6">
                                    <div class="form-check-inline my-1">
                                        <div class="custom-control custom-radio">
                                            <input type="radio" id="vat_enabled_yes" value="true" name="vat_enabled" class="custom-control-input" {{ ($company->vat_enabled == true) ? 'checked' : '' }}>
                                            <label class="custom-control-label" for="vat_enabled_yes">Yes</label>
                                        </div>
                                    </div>
                                    <div class="form-check-inline my-1">
                                        <div class="custom-control custom-radio">
                                            <input type="radio" id="vat_enabled_no" value="false" name="vat_enabled" class="custom-control-input" {{ ($company->vat_enabled == false) ? 'checked' : '' }}>
                                            <label class="custom-control-label" for="vat_enabled_no">No</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                {!! Form::label('Logo') !!}
                                <input type="file" id="input-file-now" name="logo" class="dropify" accept="image/*" data-default-file="{{ Storage::disk('logo_company')->url($company->logo) }}" />
                            </div>
                            <div class="form-group">
                                <label class="label-required">Type</label>
                                <div class="radio-buttons">
                                    @if ($company->type == 1)
                                        <label class="custom-radio">
                                            <input type="radio" class="form-control" name="type" value="1" required {{ ($company->type == 1) ? 'checked' : '' }}>
                                            <span class="radio-btn">
                                            <i class="fa fa-check"></i>
                                                <div class="option-check">
                                                    <h3>UMKM</h3>
                                                </div>
                                            </span>
                                        </label>
                                    @endif
                                    @if ($company->type == 2)
                                        <label class="custom-radio">
                                            <input type="radio" class="form-control" name="type" value="2" required {{ ($company->type == 2) ? 'checked' : '' }}>
                                            <span class="radio-btn">
                                            <i class="fa fa-check"></i>
                                            <div class="option-check">
                                                <h3>ENTERPRISE</h3>
                                            </div>
                                            </span>
                                        </label>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group">
                                <hr style="border: 2px solid green;"/>
                            </div>
                            <div class="form-group">
                                <label class="label-required">Person in Charge (PIC)</label>
                                {!! Form::select('pic_id', $list_user, $list_user_pic->id, ['placeholder' => 'Select PIC...', 'class' => 'select2 form-control mb-5 custom-select', 'id' => 'pic_id', 'name' => 'pic_id', 'style' => 'width: 100%;']) !!}
                            </div>
                            <div class="form-group mb-4">
                                <button type="button" class="btn btn-outline-light btn-gradient-info btn-sm btn-modal">[+] Add New PIC</button>
                            </div>
                            <div class="form-group mt-5">
                                <button type="submit" class="btn btn-gradient-primary waves-effect waves-light">Save</button>
                                <a href="{{ route('admin.companies.index') }}" class="btn btn-gradient-danger waves-effect m-l-5">Cancel</a>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="float-right">
                                @if (1 !== $company->status)
                                    <button type="button" class="btn btn-gradient-secondary waves-effect waves-light btn-status" data-id="{{ $company->id }}" data-value="{{ $company->company_name }}" data-status="active">Activate</button>
                                @endif
                                @if (2 !== $company->status)
                                    <button type="button" class="btn btn-gradient-warning waves-effect waves-light btn-status" data-id="{{ $company->id }}" data-value="{{ $company->company_name }}" data-status="inactive">Inactive</button>
                                @endif
                                @if (3 !== $company->status)
                                    <button type="button" class="btn btn-gradient-info waves-effect waves-light btn-status" data-id="{{ $company->id }}" data-value="{{ $company->company_name }}" data-status="onhold">Onhold</button>
                                @endif
                                @if (4 !== $company->status)
                                    <button type="button" class="btn btn-gradient-danger waves-effect waves-light btn-status" data-id="{{ $company->id }}" data-value="{{ $company->company_name }}" data-status="delete">Delete</button>
                                @endif
                                <input type="hidden" id="routeUpdateStatus" value="{{ route('api.company.update.status.route') }}">
                                <input type="hidden" id="routeRedirectToIndex" value="{{ route('admin.companies.index') }}">
                                <input type="hidden" id="routeStoreUserModal" value="{{ route('api.admin.user.store.route') }}">
                                <input type="hidden" id="routeListUser" value="{{ route('api.admin.user.list.by.company.route') }}">
                                <input type="hidden" id="userId" value="{{ Auth::user()->id }}">
                                <input type="hidden" id="companyId" value="{{ $company->id }}">
                                <input type="hidden" id="picSelected" value="{{ $list_user_pic->id }}">
                            </div>
                        </div>
                    </div><!--end form-group-->
                    </form><!--end form-->
                </div><!--end card-body-->
            </div><!--end card-->
        </div> <!-- end col -->
    </div>
</div>
<!-- container -->

<!-- modal -->
<div class="modal fade" id="userModalInput" tabindex="-1" role="dialog" aria-labelledby="userModalInputLabel" aria-hidden="true">
    <form id="userModal" method="post" class="form-horizontal">
    @csrf
        <div class="modal-dialog" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title mt-0" id="userModalInputLabel">Create Person in Charge (PIC)</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="col-md-12 form-group">
                    <label class="label-required">Person in Charge (PIC)</label>
                    {!! Form::text('pic_name', '', ['class' => 'form-control clear-input', 'autocomplete' => 'off', 'id' => 'pic_name']) !!}
                </div>
                <div class="col-md-12 form-group">
                    <label class="label-required">PIC Email</label>
                    {!! Form::text('pic_email', '', ['class' => 'form-control clear-input', 'autocomplete' => 'off', 'id' => 'pic_email']) !!}
                </div>
                <div class="col-md-12 form-group">
                    <label class="label-required">PIC Phone</label>
                    {!! Form::text('pic_phone', '', ['class' => 'form-control clear-input only-number', 'autocomplete' => 'off', 'id' => 'pic_phone']) !!}
                </div>
                <div class="col-md-12 form-group">
                    <label class="label-required">Password</label>
                    {!! Form::password('password', ['class' => 'form-control clear-input', 'autocomplete' => 'off', 'id' => 'password']) !!}
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary" id="submitModal">Save</button>
            </div>
            </div>
        </div>
    </form>
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

    <script src="{{ URL::asset('metrica/assets/pages-material/jquery.forms-advanced.js') }}"></script>

    <!-- Parsley js -->
    <script src="{{ URL::asset('metrica/assets/plugins/parsleyjs/parsley.min.js') }}"></script>

    <script src="{{ URL::asset('metrica/assets/plugins/dropify/js/dropify.min.js') }}"></script>
    <script src="{{ URL::asset('metrica/assets/pages/jquery.form-upload.init.js') }}"></script>

    <!-- Validation jquery -->
    <script src="{{ URL::asset('metrica/plugins/jquery-validation/jquery.validate.min.js') }}"></script>
    <script src="{{ URL::asset('metrica/plugins/jquery-validation/additional-methods.min.js') }}"></script>
    <script src="{{ URL::asset('metrica/assets/js/jq-validation/form_company.js') }}"></script>

    <!-- Form jquery -->
    <script src="{{ URL::asset('pages/js/company/formEdit.js') }}"></script>
@endsection
