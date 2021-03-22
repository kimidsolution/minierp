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
                        <li class="breadcrumb-item active">Create Company</li>
                    </ol>
                </div>
                <h4 class="page-title">Create Company</h4>
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
                    {!! Form::open(['route' => 'admin.companies.store', 'class' => 'form-parsley', 'role' => 'form', 'id' => 'jq-validation-form-create', 'enctype' => 'multipart/form-data']) !!}
                    @csrf
                    <div class="form-row d-flex justify-content-center">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="label-required">Company Name</label>
                                {!! Form::text('company_name', old('company_name'), ['class' => 'form-control', 'autocomplete' => 'off']) !!}
                            </div>
                            <div class="form-group">
                                {!! Form::label('Brand Name') !!}
                                {!! Form::text('brand_name', old('brand_name'), ['class' => 'form-control', 'autocomplete' => 'off']) !!}
                            </div>
                            <div class="form-group">
                                <label class="label-required">Address</label>
                                {!! Form::textarea('address', old('address'), ['rows' => 5, 'class' => 'form-control', 'required']) !!}
                            </div>
                            <div class="form-group">
                                <label class="label-required">City</label>
                                {!! Form::text('city', old('city'), ['class' => 'form-control', 'autocomplete' => 'off']) !!}
                            </div>
                            <div class="form-group">
                                <label class="label-required">Country</label>
                                {!! Form::text('country', old('country'), ['class' => 'form-control', 'autocomplete' => 'off']) !!}
                            </div>
                            <div class="form-group">
                                <label class="label-required">Email</label>
                                {!! Form::text('email', old('email'), ['class' => 'form-control', 'autocomplete' => 'off']) !!}
                            </div>
                            <div class="form-group">
                                <label class="label-required">Phone</label>
                                {!! Form::text('phone', old('phone'), ['class' => 'form-control only-number', 'autocomplete' => 'off']) !!}
                            </div>
                            <div class="form-group">
                                {!! Form::label('Fax') !!}
                                {!! Form::text('fax', old('fax'), ['class' => 'form-control only-number', 'autocomplete' => 'off']) !!}
                            </div>
                            <div class="form-group">
                                {!! Form::label('Tax ID Number') !!}
                                {!! Form::text('tax_id_number', old('tax_id_number'), ['class' => 'form-control', 'autocomplete' => 'off']) !!}
                            </div>
                            <div class="form-group">
                                {!! Form::label('Website') !!}
                                {!! Form::text('website', old('website'), ['class' => 'form-control', 'autocomplete' => 'off']) !!}
                            </div>
                            <div class="form-group">
                                <label class="label-required">Currency</label>
                                <select id="currency_id" name="currency_id" class="select2 form-control" required>
                                    <option value=""> Choose Currency ... </option>
                                    @foreach ($currencies as $currency)
                                        <option value="{{ $currency->id }}" {{ old('currency_id') == $currency->id ? 'selected' : ''}}>
                                            {{ $currency->currency_name . ' ('.$currency->symbol.')' }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group mb-2 row">
                                <label class="label-required col-md-5 my-1 control-label">Enable Value Add Tax?</label>
                                <div class="col-md-6">
                                    <div class="form-check-inline my-1">
                                        <div class="custom-control custom-radio">
                                            <input type="radio" id="vat_enabled_yes" value="true" name="vat_enabled" class="custom-control-input" checked>
                                            <label class="custom-control-label" for="vat_enabled_yes">Yes</label>
                                        </div>
                                    </div>
                                    <div class="form-check-inline my-1">
                                        <div class="custom-control custom-radio">
                                            <input type="radio" id="vat_enabled_no" value="false" name="vat_enabled" class="custom-control-input">
                                            <label class="custom-control-label" for="vat_enabled_no">No</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                {!! Form::label('Logo') !!}
                                <input type="file" id="input-file-now" name="logo" class="dropify" accept="image/*" />
                            </div>
                            <div class="form-group">
                                <label class="label-required">Type</label>
                                <div class="radio-buttons">
                                    <label class="custom-radio">
                                        <input type="radio" class="form-control" name="type" value="1" required checked>
                                        <span class="radio-btn">
                                        <i class="fa fa-check"></i>
                                            <div class="option-check">
                                                <h3>UMKM</h3>
                                            </div>
                                        </span>
                                    </label>
                                    <label class="custom-radio">
                                        <input type="radio" class="form-control" name="type" value="2" required>
                                        <span class="radio-btn">
                                        <i class="fa fa-check"></i>
                                        <div class="option-check">
                                            <h3>ENTERPRISE</h3>
                                        </div>
                                        </span>
                                    </label>
                                </div>
                            </div>
                            <div class="form-group">
                                <hr style="border: 2px solid green;"/>
                            </div>
                            <div class="form-group">
                                <label class="label-required">Person in Charge (PIC)</label>
                                {!! Form::text('pic_name', old('website'), ['class' => 'form-control', 'autocomplete' => 'off']) !!}
                            </div>
                            <div class="form-group">
                                <label class="label-required">PIC Email</label>
                                {!! Form::text('pic_email', old('website'), ['class' => 'form-control', 'autocomplete' => 'off']) !!}
                            </div>
                            <div class="form-group">
                                <label class="label-required">PIC Phone</label>
                                {!! Form::text('pic_phone', old('website'), ['class' => 'form-control only-number', 'autocomplete' => 'off']) !!}
                            </div>
                            <div class="form-group">
                                <label class="label-required">Password</label>
                                {!! Form::password('password', ['class' => 'form-control', 'required']) !!}
                            </div>
                            <div class="form-group mt-5">
                                <button type="submit" class="btn btn-gradient-primary waves-effect waves-light">
                                    Save
                                </button>
                                <a href="{{ route('admin.companies.index') }}" class="btn btn-gradient-danger waves-effect m-l-5">Cancel</a>
                            </div><!--end form-group-->
                        </div>
                    </div>
                    {!! Form::close() !!}<!--end form-->
                </div><!--end card-body-->
            </div><!--end card-->
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
    <script src="{{ URL::asset('pages/js/company/formInput.js') }}"></script>
@endsection
