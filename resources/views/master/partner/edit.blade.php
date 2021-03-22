@extends('layouts.backend.metrica.master')

@section('css')
<link href="{{ URL::asset('metrica/assets/plugins/select2/select2.min.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('content')
<div class="container-fluid">
    <!-- Page-Title -->
    <div class="row">
        <div class="col-sm-12">
            <div class="page-title-box">
                <div class="float-right">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('master.partner.index') }}">Partner</a></li>
                        <li class="breadcrumb-item active">Edit Partner</li>
                    </ol>
                </div>
                <h4 class="page-title">Edit Partner</h4>
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
                    <form class="form-parsley" method="POST" action="{{ route('master.partner.update', ['partner' => $partner->id]) }}" role="form" id="jq-validation-form-create">
                    @csrf
                    {{ Form::hidden('_method', 'PUT') }}
                    {{ Form::hidden('id', $partner->id) }}
                    <div class="form-row d-flex justify-content-center">
                        <div class="col-md-12">
                            @if ($isAdmin)
                                <input type="hidden" value="{{ $partner->company_id }}" name="company_id">
                            @endif
                            <div class="form-group">
                                <label class="label-required">Partner Name</label>
                                {!! Form::text('name', old('name', $partner->partner_name),  ['class' => 'form-control', 'required']) !!}
                            </div>
                            <div class="form-group">
                                <label class="label-required">Email</label>
                                {!! Form::text('email', old('email', $partner->email),  ['class' => 'form-control', 'required']) !!}
                            </div>
                            <div class="form-group">
                                {!! Form::label('Phone') !!}
                                <input type="text"
                                    maxlength="50"
                                    name="phone_number"
                                    class="form-control"
                                    value="{{ old('phone_number', $partner->phone_number) }}"
                                    onkeypress='return event.charCode >= 48 && event.charCode <= 57'
                                >
                            </div>
                            <div class="form-group">
                                {!! Form::label('Fax') !!}
                                <input type="text"
                                    maxlength="50"
                                    name="fax"
                                    class="form-control"
                                    value="{{ old('fax', $partner->fax) }}"
                                    onkeypress='return event.charCode >= 48 && event.charCode <= 57'
                                >
                            </div>
                            <div class="form-group">
                                {!! Form::label('Tax Id Number (NPWP)') !!}
                                {!! Form::text('tax_id_number', old('tax_id_number', $partner->tax_id_number),  ['class' => 'form-control']) !!}
                            </div>
                            <div class="form-group">
                                <label class="label-required">Address</label>
                                {!! Form::textarea('address', old('address', $partner->address), ['rows' => 3, 'class' => 'form-control', 'required']) !!}
                            </div>
                            <div class="form-group">
                                <label class="label-required">City</label>
                                {!! Form::text('city', old('city', $partner->city),  ['class' => 'form-control', 'required']) !!}
                            </div>
                            <div class="form-group">
                                <label class="label-required">Country</label>
                                {!! Form::text('country', old('country', $partner->country),  ['class' => 'form-control', 'required']) !!}
                            </div>
                            <div class="form-group">
                                {!! Form::label('PIC') !!}
                                {!! Form::text('pic_name', old('pic_name', $partner->pic_name),  ['class' => 'form-control']) !!}
                            </div>
                            <div class="form-group">
                                {!! Form::label('PIC Email') !!}
                                {!! Form::text('pic_email', old('pic_email', $partner->pic_email),  ['class' => 'form-control']) !!}
                            </div>
                            <div class="form-group">
                                {!! Form::label('PIC Phone') !!}
                                <input type="text"
                                    maxlength="50"
                                    name="pic_phone_number"
                                    class="form-control"
                                    value="{{ old('pic_phone_number', $partner->pic_phone_number) }}"
                                    onkeypress='return event.charCode >= 48 && event.charCode <= 57'
                                >
                            </div>
                            <div class="form-group">
                                <label class="label-required">Type</label>
                                <div class="radio-buttons">
                                    <label class="custom-radio">
                                        <input type="radio"
                                            class="form-control"
                                            name="partner_status"
                                            value="vendor"
                                            required
                                            {{ $partner->is_client == false && $partner->is_vendor == true ? 'checked' : '' }}
                                        >
                                        <span class="radio-btn">
                                        <i class="fa fa-check"></i>
                                            <div class="option-check">
                                                <h3>Vendor</h3>
                                            </div>
                                        </span>
                                    </label>
                                    <label class="custom-radio">
                                        <input type="radio"
                                            class="form-control"
                                            name="partner_status"
                                            value="client"
                                            required
                                            {{ $partner->is_client == true && $partner->is_vendor == false ? 'checked' : '' }}
                                        >
                                        <span class="radio-btn">
                                        <i class="fa fa-check"></i>
                                        <div class="option-check">
                                            <h3>Customer</h3>
                                        </div>
                                        </span>
                                    </label>
                                    <label class="custom-radio">
                                        <input type="radio"
                                            class="form-control"
                                            name="partner_status"
                                            value="both"
                                            required
                                            {{ $partner->is_client == true && $partner->is_vendor == true ? 'checked' : '' }}
                                        >
                                        <span class="radio-btn">
                                        <i class="fa fa-check"></i>
                                        <div class="option-check">
                                            <h3>Both</h3>
                                        </div>
                                        </span>
                                    </label>
                                </div>
                            </div>
                            <div class="form-group mt-4 float-right">
                                <a href="{{ route('master.partner.index') }}" class="btn btn-gradient-danger waves-effect m-l-5">Cancel</a>
                                <button type="submit" class="btn btn-gradient-primary waves-effect waves-light">
                                    Edit
                                </button>
                            </div>
                        </div>
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div><!--end col-->
    </div>
    <!--end row-->
</div>
<!-- container -->

@endsection

@section('script')
<script src="{{ URL::asset('metrica/plugins/jquery-validation/jquery.validate.min.js') }}"></script>
<script src="{{ URL::asset('metrica/assets/js/jq-validation/form_partner.js') }}"></script>
<script src="{{ URL::asset('metrica/assets/plugins/moment/moment.js') }}"></script>
<script src="{{ URL::asset('metrica/assets/plugins/daterangepicker/daterangepicker.js') }}"></script>
<script src="{{ URL::asset('metrica/assets/plugins/select2/select2.min.js') }}"></script>
<script src="{{ URL::asset('metrica/assets/plugins/timepicker/bootstrap-material-datetimepicker.js') }}"></script>
<script src="{{ URL::asset('metrica/assets/plugins/bootstrap-colorpicker/js/bootstrap-colorpicker.min.js') }}"></script>
<script src="{{ URL::asset('metrica/assets/plugins/bootstrap-maxlength/bootstrap-maxlength.min.js') }}"></script>
<script src="{{ URL::asset('metrica/assets/plugins/bootstrap-touchspin/js/jquery.bootstrap-touchspin.min.js') }}"></script>
<script src="{{ URL::asset('metrica/assets/pages-material/jquery.forms-advanced.js') }}"></script>
@endsection
