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
                        <li class="breadcrumb-item"><a href="/">Home</a></li>
                        <li class="breadcrumb-item"><a href="/currencies">Currency</a></li>
                        <li class="breadcrumb-item active">Edit Currency</li>
                    </ol>
                </div>
                <h4 class="page-title">Edit Currency</h4>
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
                    <input type="hidden" id="routeCheckIsoCode" value="{{ route('api.admin.currencies.check.isocode.route') }}">
                    <input type="hidden" id="idCurrency" value="{{ $currency->id }}">
                    <form class="form-parsley" method="POST" id="jq-validation-form-create" action="{{ route('admin.currencies.update', ['currency' => $currency->id]) }}" role="form">
                    @csrf
                    {{ Form::hidden('_method', 'PUT') }}
                    {{ Form::hidden('id', $currency->id) }}
                        <div class="form-row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="label-required">ISO Code</label>
                                    <input class="form-control"
                                        required
                                        type="text"
                                        name="iso_code"
                                        id="isoCode"
                                        maxlength="3"
                                        value="{{ old('iso_code', $currency->iso_code) }}"
                                        onkeypress='return event.charCode >= 48 && event.charCode <= 57'
                                    >
                                </div>
                                <div class="form-group">
                                    <label class="label-required">Name</label>
                                    {!! Form::text('currency_name', old('currency_name', $currency->currency_name),  ['class' => 'form-control', 'required']) !!}
                                </div>
                                <div class="form-group">
                                    <label class="label-required">Code</label>
                                    <input class="form-control"
                                        required
                                        type="text"
                                        name="currency_code"
                                        value="{{ old('currency_code', $currency->currency_code) }}"
                                        onkeyup='this.value = this.value.toUpperCase()'
                                    >
                                </div>
                                <div class="form-group">
                                    <label class="label-required">Symbol</label>
                                    {!! Form::text('symbol', old('symbol', $currency->symbol),  ['class' => 'form-control', 'required']) !!}
                                </div>
                                <div class="form-group mt-4">
                                    <a href="{{ route('admin.currencies.index') }}" class="btn btn-gradient-danger waves-effect m-l-5">Cancel</a>
                                    <button type="submit" class="btn btn-gradient-primary waves-effect waves-light">
                                        Edit Currency
                                    </button>
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
    <script>
        $(document).ready(function(e){

            // only number in form
            $(".only-number").keypress(function (e) {
                if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
                    return false;
                }
            });
        });

    </script>

    <!-- Validation jquery -->
    <script src="{{ URL::asset('metrica/plugins/jquery-validation/jquery.validate.min.js') }}"></script>
    <script src="{{ URL::asset('metrica/assets/js/jq-validation/form_currency.js') }}"></script>
@endsection
