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
                        <li class="breadcrumb-item"><a href="/finance/transactions/import">Transactions</a></li>
                        <li class="breadcrumb-item active">Import</li>
                    </ol>
                </div>
                <h4 class="page-title">Import Transactions</h4>
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
                    <form method="POST" class="form-parsley" id="jq-validation-form-create" role="form" enctype="multipart/form-data" action="{{ route('finance.transactions.import.upload.post') }}">
                    @csrf
                    <div class="form-row d-flex justify-content-center">
                        <div class="col-md-12">
                            @if ($isAdmin)
                                <div class="form-group">
                                    <label class="label-required">Company</label>
                                    <select name="company_id" class="select2 form-control select-company" required>
                                        <option value=""> Choose Company ... </option>
                                        @foreach ($list_company as $company)
                                            <option value="{{ $company->id }}">
                                                {{ $company->company_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            @endif
                            <div class="form-group">
                                {!! Form::label('File Import') !!}
                                <input type="file" id="input-file-now" name="document" class="dropify" accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel" />
                            </div>
                            <div class="form-group mt-5">
                                <button type="submit" class="btn btn-gradient-primary waves-effect waves-light">
                                    Save
                                </button>
                                <a href="{{ route('finance.transactions.import.index') }}" class="btn btn-gradient-danger waves-effect m-l-5">Cancel</a>
                            </div><!--end form-group-->
                        </div>
                    </div>
                    </form><!--end form-->
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
@endsection
