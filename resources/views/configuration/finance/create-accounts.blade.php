@extends('layouts.backend.metrica.master')

@section('css')
    <!-- Plugins css -->
    <link href="{{ URL::asset('metrica/assets/plugins/select2/select2.min.css') }}" rel="stylesheet" type="text/css" />
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
                        <li class="breadcrumb-item"><a href="javascript:void(0);">Configuration</a></li>
                        <li class="breadcrumb-item"><a href="javascript:void(0);">Finance</a></li>
                        <li class="breadcrumb-item"><a href="javascript:void(0);">Accounts</a></li>
                        <li class="breadcrumb-item active">Create Accounts</li>
                    </ol>
                </div>
                <h4 class="page-title">Create Finance Configuration Accounts</h4>
            </div><!--end page-title-box-->
        </div><!--end col-->
    </div>
    <!--end row-->
    <div class="row">
        <div class="col-lg-6">
            <div class="card">
                <div class="card-body">
                    <p class="text-muted mb-4">
                        Please fill in the following form correctly.
                    </p>
                    <input type="hidden" id="isAdmin" value="{{ $isAdmin }}">
                    <input type="hidden" id="routeFetchAccount" value="{{ route('api.account.list.account.route') }}">
                    <input type="hidden" id="routeFetchStore" value="{{ route('api.configuration.finance.store.route') }}">
                    <input type="hidden" id="routeFetchConfig" value="{{ route('api.configuration.finance.getlistavailable.route') }}">
                    <form class="form-parsley" id="jq-validation-form-create">
                        <input type="hidden" name="user_id" value="{{ Auth::user()->id }}">
                        <div class="form-row">
                            <div class="col-md-12">
                                @if ($isAdmin)
                                    <div class="form-group">
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
                                <div class="form-group">
                                    <label class="label-required">Configuration</label>
                                    <select id="configCode" name="config_code" class="select2 form-control" required>
                                        <option value=""> Choose Configuration ... </option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label class="label-required">Status</label>
                                    <div class="btn-group btn-group-toggle" data-toggle="buttons" style="display: block;">
                                        <label class="btn btn-outline-primary btn-sm">
                                            <input type="radio" value="true" name="config_status"> Active
                                        </label>
                                        <label class="btn btn-outline-primary btn-sm">
                                            <input type="radio" value="false" name="config_status"> Not Active
                                        </label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <table class="table table-bordered mb-0 table-centered" style="border: none;">
                                        <thead>
                                            <tr style="background-color: #50b380;">
                                                <th style="width: 25%; color: #fff;">Account</th>
                                                <th style="width: 10%; color: #fff;"></th>
                                            </tr>
                                        </thead>
                                        <tbody id="accounts_table">
                                            <tr>
                                                <td>
                                                    <select class="select2 form-control selectorAccount" style="width: 100%;" id="account-0" ids="0" name="accounts[]" required>
                                                        <option value=""> Choose Account ... </option>
                                                    </select>
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
                                </div>
                                <div class="form-group mt-4 float-right">
                                    <a href="{{ route('configuration.finance.accounts.index')  }}"class="btn btn-gradient-danger waves-effect m-l-5">Cancel</a>
                                    <button type="submit"
                                        disabled
                                        id="submitForm"
                                        class="btn btn-gradient-primary"
                                    >
                                        Save
                                    </button>
                                </div>
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
    <script src="{{ URL::asset('metrica/assets/pages-material/jquery.forms-advanced.js') }}"></script>

    <!-- Validation jquery -->
    <script src="{{ URL::asset('metrica/plugins/jquery-validation/jquery.validate.min.js') }}"></script>
    <script src="{{ URL::asset('metrica/plugins/jquery-validation/additional-methods.min.js') }}"></script>

    <script src="{{ URL::asset('metrica/assets/plugins/accounting/accounting.js') }}"></script>
    <script src="{{ URL::asset('metrica/assets/plugins/accounting/autoNumeric.min.js') }}"></script>
    <script src="{{ URL::asset('metrica/assets/js/jq-validation/form_configuration_finance.js') }}"></script>
    <script src="{{ URL::asset('pages/js/global/time.js') }}"></script>
    <script src="{{ URL::asset('pages/js/global/notifications.js') }}"></script>
    <script src="{{ URL::asset('pages/js/global/stringHelper.js') }}"></script>
    <script src="{{ URL::asset('pages/js/global/serializeObject.min.js') }}"></script>
    <script src="{{ URL::asset('pages/js/configuration/finance/formAccount.js') }}"></script>
@endsection
