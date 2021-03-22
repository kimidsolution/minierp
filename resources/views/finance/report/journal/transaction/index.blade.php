@extends('layouts.backend.metrica.master')

@section('css')
    <link href="{{ URL::asset('metrica/assets/plugins/daterangepicker/daterangepicker.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ URL::asset('metrica/assets/plugins/timepicker/bootstrap-material-datetimepicker.css') }}" rel="stylesheet" type="text/css" />
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
                        <li class="breadcrumb-item"><a href="javascript:void(0);">Finance</a></li>
                        <li class="breadcrumb-item"><a href="javascript:void(0);">Report</a></li>
                        <li class="breadcrumb-item active">Journal Entry</li>
                    </ol>
                </div>
                <h4 class="page-title">Journal Entry</h4>
            </div>
            <!--end page-title-box-->
        </div>
        <!--end col-->
    </div>
    <!-- end page title end breadcrumb -->

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
        <div class="col-lg-12">
            <div class="accordion" id="Details_collapse">
                <div class="card">
                    <div class="card-body">
                        <a class="d-lg-flex justify-content-between" data-toggle="collapse" href="#detail_collapse" role="button" aria-expanded="true" aria-controls="detail_collapse">
                            <div class="media mb-3 mb-lg-0">
                                @if ($company && $company->company && !is_null($company->company->logo))
                                    <img src="{{URL::asset($company->company->logo)}}" class="mr-3 thumb-md align-self-center rounded-circle" alt="logo-company">
                                @else
                                    <img src="{{ URL::asset('metrica/assets/images/users/user-4.jpg') }}" class="mr-3 thumb-md align-self-center rounded-circle" alt="logo-company">
                                @endif
                                <div class="media-body align-self-center">
                                    <h5 class="mt-0 mb-1">
                                        {{$company && $company->company ? $company->company->company_name : ''}}
                                    </h5>
                                    <p class="text-muted mb-0">
                                        <i class="fas fa-map-marker-alt mr-2 text-info"></i>
                                        {{$company && $company->company ? $company->company->address : ''}}
                                    </p>
                                    <p class="text-muted mb-0">
                                        <i class="mdi mdi-comment-text mr-2 text-info"></i>
                                        {{ empty($data->description) ? 'No Description' : $data->description }}
                                    </p>
                                </div><!--end media body-->
                            </div> <!--end media-->
                            <p class="text-muted mb-2 mb-lg-0 align-self-center">
                                <i class="mdi mdi-folder-outline mr-2 text-info font-14"></i>
                                Account
                                <span class="badge badge-primary">
                                    {{$account_name}}
                                </span>
                            </p>
                            <p class="text-muted mb-2 mb-lg-0 align-self-center">
                                <i class="mdi mdi-folder-outline mr-2 text-info font-14"></i>
                                Reference Number
                                <span class="badge badge-primary">
                                    {{$data->reference_number ? $data->reference_number : '-'}}
                                </span>
                            </p>
                            <p class="text-muted mb-2 mb-lg-0 align-self-center">
                                <i class="fas fa-calendar mr-2 text-info font-14"></i>
                                Date
                                <span class="badge badge-primary">
                                    {{ date('d-m-Y', strtotime($data->transaction_date))}}
                                </span>
                            </p>
                        </a>
                        <div class="collapse show" id="detail_collapse" data-parent="#Details_collapse">
                            <div class="card card-body mb-0">
                                <div class="row">
                                    <div class="col-lg-9">
                                        <div class="table-responsive">
                                            <table class="table table-bordered mb-0 table-centered table-sm">
                                                <thead style="background-color: #50b380;">
                                                    <tr>
                                                        <th style="color: #fff; width: 30%;">Account Name</th>
                                                        <th class="text-center" style="color: #fff; width: 10%;">Balance</th>
                                                        <th style="color: #fff; text-align: right; width: 30%;">Debit Amount</th>
                                                        <th style="color: #fff; text-align: right; width: 30%;">Credit Amount</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @if (!empty($data->transaction_details))
                                                        @php
                                                            $total_debit = (float) 0;
                                                            $total_credit = (float) 0;
                                                        @endphp
                                                        @foreach ($data->transaction_details as $key => $item)
                                                            @php
                                                                $total_debit += $item->debit_amount;
                                                                $total_credit += $item->credit_amount;
                                                            @endphp
                                                            <tr>
                                                                <td>{{$item->account->account_name}}</td>
                                                                <td class="text-center">
                                                                    @if ($item->account->balance == 'debit')
                                                                        <span class="badge badge-soft-success">{{$item->account->balance}}</span>
                                                                    @else
                                                                        <span class="badge badge-soft-info">{{$item->account->balance}}</span>
                                                                    @endif
                                                                </td>
                                                                <td style="text-align: right;">
                                                                    {{app('string.helper')->defFormatCurrency($item->debit_amount)}}
                                                                </td>
                                                                <td style="text-align: right;">
                                                                    {{app('string.helper')->defFormatCurrency($item->credit_amount)}}
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                        <tr style="background-color: #50b380">
                                                            <td colspan="2" style="color: #fff;">Total</td>
                                                            <td style="text-align: right; color: #fff;">
                                                                {{app('string.helper')->defFormatCurrency($total_debit)}}
                                                            </td>
                                                            <td style="text-align: right; color: #fff;">
                                                                {{app('string.helper')->defFormatCurrency($total_credit)}}
                                                            </td>
                                                        </tr>
                                                    @endif
                                                </tbody>
                                            </table><!--end /table-->
                                        </div><!--end /tableresponsive-->
                                    </div><!-- end col-->
                                    <div class="col-lg-3">
                                        <h4 class="mt-0 header-title">Transaction Details</h4>
                                        <div class="carousel-bg-img">
                                            <div class="dash-info-carousel">
                                                <div id="carouse_detail" class="carousel slide" data-ride="carousel">
                                                    <div class="carousel-inner">
                                                        @if (!empty($data->transaction_details))
                                                            @foreach ($data->transaction_details as $key => $item)
                                                                @php
                                                                    $class_icon = $item->account->balance == 'debit' ? 'text-primary' : 'text-info';
                                                                    $class_badge = $item->account->balance == 'debit' ? 'badge-primary' : 'badge-info';
                                                                @endphp
                                                                <div class="carousel-item {{$key == 0 ? 'active' : ''}}">
                                                                    <div class="media">
                                                                        <i style="font-size: 3rem;" class="mdi mdi mdi-scale-balance {{$class_icon}} mr-4"></i>
                                                                        <div class="media-body align-self-center">
                                                                            <span class="badge {{$class_badge}} mb-2">
                                                                                {{$item->account->balance}}
                                                                            </span>
                                                                            <h4 class="mt-0">{{$item->account->naming}}</h4>
                                                                            <p class="text-muted mb-0">
                                                                                Debit amount
                                                                                <span class="badge badge-primary" style="float: right;">
                                                                                    {{app('string.helper')->defFormatCurrency($item->debit_amount)}}
                                                                                </span>
                                                                            </p>
                                                                            <p class="text-muted mb-0">
                                                                                Credit amount
                                                                                <span class="badge badge-info" style="float: right;">
                                                                                    {{app('string.helper')->defFormatCurrency($item->credit_amount)}}
                                                                                </span>
                                                                            </p>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            @endforeach
                                                        @endif
                                                    </div>
                                                    <a class="carousel-control-prev" href="#carouse_detail" role="button" data-slide="prev">
                                                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                                    <span class="sr-only">Previous</span>
                                                    </a>
                                                    <a class="carousel-control-next" href="#carouse_detail" role="button" data-slide="next">
                                                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                                    <span class="sr-only">Next</span>
                                                    </a>
                                                </div>
                                            </div><!--end card-body-->
                                        </div><!--end card-->
                                    </div><!-- end col-->
                                </div><!--end row-->
                            </div><!--end card-->
                        </div><!--end collapse-->
                    </div><!--end card-body-->
                </div><!--end card-->
            </div><!--end Details_collapse-->
        </div><!--end col-->
    </div><!--end row-->
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
<script src="{{ URL::asset('metrica/plugins/jquery-validation/jquery.validate.min.js') }}"></script>
<script src="{{ URL::asset('metrica/assets/plugins/accounting/accounting.js') }}"></script>
<script src="{{ URL::asset('metrica/assets/pages/jquery.forms-advanced.js') }}"></script>
<script src="{{ URL::asset('pages/js/global/expand.js') }}"></script>
<script src="{{ URL::asset('pages/js/global/stringHelper.js') }}"></script>
@endsection
