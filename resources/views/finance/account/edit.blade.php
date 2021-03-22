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
                        <li class="breadcrumb-item"><a href="/">Home</a></li>
                        <li class="breadcrumb-item"><a href="/account-types">Account</a></li>
                        <li class="breadcrumb-item active">Edit Account</li>
                    </ol>
                </div>
                <h4 class="page-title">Edit Account</h4>
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
                    <input type="hidden" id="routeFetchParent" value="{{ route('api.account.list.parent.route') }}">
                    <input type="hidden" id="routeCheckCode" value="{{ route('api.account.check.code.route') }}">
                    <input type="hidden" id="idAccount" value="{{ $account->id }}">
                    <p class="text-muted mb-4">Please fill in the following form correctly.</p>
                    <form class="form-parsley" method="POST" action="{{ route('finance.accounts.update', ['account' => $account->id]) }}" role="form" id="jq-validation-form-create">
                    @csrf
                    {{ Form::hidden('_method', 'PUT') }}
                    {{ Form::hidden('id', $account->id) }}
                        <div class="form-row">
                            <div class="col-md-12">
                                <input type="hidden" id="companyId" name="company_id" value="{{ old('company_id', $account->company_id) }}">
                                <div class="form-group">
                                    <label class="label-required">Account Type</label>
                                    <select id="accountType" name="account_type" class="select2 form-control" required disabled>
                                        <option value=""> Choose Account Type ... </option>
                                        @foreach (config('sempoa.coa_type') as $type)
                                            <option data-id="{{ $type['balance'] }}" value="{{ $type['id'] }}" {{ $account->account_type == $type['id'] ? 'selected' : '' }}>
                                                {{ $type['name'] }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <input type="hidden" name="account_type" value="{{ $account->account_type }}">
                                    <input type="hidden" value="{{ old('balance', $account->balance) }}" id="balance" name="balance">
                                </div>
                                <div class="form-group">
                                    <label class="label-required">Parent Account</label>
                                    <select id="accountParent" name="parent_account_id" class="select2 form-control" required disabled>
                                        <option value="">Choose Parent Account ... </option>
                                        @foreach ($list_parent_existing as $parent)
                                            <option data-id="{{ $parent->level }}" value="{{ $parent->id }}" {{ $parent->id == $account->parent_account_id ? 'selected' : '' }}>{{ $parent->account_name }}</option>
                                        @endforeach
                                    </select>
                                    <input type="hidden" name="parent_account_id" value="{{ $account->parent_account_id }}">
                                </div>
                                <div class="form-group">
                                    <label class="label-required">Level</label>
                                    <input class="form-control" type="text" value="{{ old('level', $account->level) }}" id="level" name="level" required readonly>
                                </div>
                                <div class="form-group">
                                    <label class="label-required">Account Name</label>
                                    {!! Form::text('name', old('name', $account->account_name),  ['class' => 'form-control', 'required', 'readonly']) !!}
                                </div>
                                <div class="form-group">
                                    <label class="label-required">Account Number</label>
                                    <div class="prefix-group prefix">
                                        <span class="input-group-prefix" id="prefixType">{{ $account->account_type }}</span>
                                        <span class="input-group-prefix">-</span>
                                        <input class="form-control"
                                            required
                                            type="text"
                                            name="account_code"
                                            maxlength="8"
                                            id="accountCode"
                                            value="{{ old('account_code', substr(strstr($account->account_code, "-"), 1)) }}"
                                            {{ $account->account_type ? '' : 'readonly' }}
                                            onkeypress='return event.charCode >= 48 && event.charCode <= 57'
                                        >
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Account Text</label>
                                    {!! Form::text('account_text', old('account_text', $account->account_text),  ['class' => 'form-control']) !!}
                                </div>
                                <input type="hidden" name="account_balance_id" value="{{ $last_account_balance->id }}">
                                <div class="form-row">
                                    <div class="form-group col-md-5">
                                        <label class="label-required">Balance Date</label>
                                        <input type="text"
                                            readonly
                                            name="balance_date"
                                            class="form-control"
                                            value="{{ old('date', date("d-m-Y", strtotime($last_account_balance->balance_date))) }}"
                                            id="balanceDate"
                                        >
                                    </div>
                                    <div class="form-group col-md-7">
                                        <label class="label-required">Balance</label>
                                        <input class="form-control"
                                            required
                                            type="text"
                                            id="balance_nominal"
                                            name="balance_nominal"
                                            value="{{ old('balance_nominal', $account->balance == "debit" ? $last_account_balance->debit_amount : $last_account_balance->credit_amount) }}"
                                        >
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Description</label>
                                    {!! Form::textarea('description', old('description', $last_account_balance->description), ['rows' => 2, 'class' => 'form-control']) !!}
                                </div>
                                <div class="form-group mt-4 float-right">
                                    <a href="{{ route('finance.accounts.index') }}" class="btn btn-gradient-danger waves-effect m-l-5">Cancel</a>
                                    <button type="submit" class="btn btn-gradient-primary waves-effect waves-light btn-submit">
                                        Edit
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
    <!-- Plugins js -->
    <script src="{{ URL::asset('metrica/assets/plugins/moment/moment.js') }}"></script>
    <script src="{{ URL::asset('metrica/assets/plugins/daterangepicker/daterangepicker.js') }}"></script>
    <script src="{{ URL::asset('metrica/assets/plugins/select2/select2.min.js') }}"></script>
    <script src="{{ URL::asset('metrica/assets/plugins/bootstrap-colorpicker/js/bootstrap-colorpicker.min.js') }}"></script>
    <script src="{{ URL::asset('metrica/assets/plugins/timepicker/bootstrap-material-datetimepicker.js') }}"></script>
    <script src="{{ URL::asset('metrica/assets/plugins/bootstrap-maxlength/bootstrap-maxlength.min.js') }}"></script>
    <script src="{{ URL::asset('metrica/assets/plugins/bootstrap-touchspin/js/jquery.bootstrap-touchspin.min.js') }}"></script>
    <script src="{{ URL::asset('metrica/assets/pages-material/jquery.forms-advanced.js') }}"></script>
    <script src="{{ URL::asset('metrica/assets/plugins/datepicker/js/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ URL::asset('metrica/assets/plugins/datepicker/js/bootstrap-datepicker.id.min.js') }}"></script>
    <script src="{{ URL::asset('metrica/assets/plugins/accounting/autoNumeric.min.js') }}"></script>

    <!-- Validation jquery -->
    <script src="{{ URL::asset('metrica/plugins/jquery-validation/jquery.validate.min.js') }}"></script>
    <script src="{{ URL::asset('pages/js/global/stringHelper.js') }}"></script>
    <script src="{{ URL::asset('pages/js/account/form.js') }}"></script>
@endsection
