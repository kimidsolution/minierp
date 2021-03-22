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
                        <li class="breadcrumb-item active">Edit Expense</li>
                    </ol>
                </div>
                <h4 class="page-title">Edit Expense</h4>
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
                    <input type="hidden" id="routeFetchStore" value="{{ route('api.finance.expense.store.route') }}">

                    <form class="form-parsley" id="jq-validation-form-create">
                        <input type="hidden" name="user_id" value="{{ Auth::user()->id }}">
                        <input type="hidden" id="companyId" name="company_id" value="{{ $expense->company_id }}">
                        <input type="hidden" id="idExpense" name="expense_id" value="{{ $expense->id }}">
                        <input type="hidden" id="idTransaction" name="transaction_id" value="{{ $transaction->id }}">
                        <input type="hidden" id="paymentAccountIdValue" value="{{ $expense->payment_account_id }}">
                        <input type="hidden" id="expenseAccountIdValue" value="{{ $expense_detail->account_id }}">

                        <div class="form-row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="label-required">Expense Date</label>
                                    <input class="form-control" type="text" id="expenseDate" name="expense_date" autocomplete="off" value="{{ date('d-m-Y', strtotime($expense->expense_date)) }}">
                                </div>
                                <div class="form-group">
                                    <label class="label-required">Reference Number</label>
                                    {!! Form::text('expense_number', $expense->reference_number, ['class' => 'form-control', 'id' => 'noref', 'readonly']) !!}
                                </div>
                                <div class="form-group">
                                    <label class="label-required">Description</label>
                                    <textarea name="description" id="description" class="form-control" rows="2">{{ $expense->description }}</textarea>
                                </div>
                                <div class="form-group">
                                    <label class="label-required">Expense Account</label>
                                    <select id="expenseAccountId" name="expense_account_id" class="select2 form-control" required>
                                        <option value="">Choose Account ... </option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label class="label-required">Payment Account</label>
                                    <select id="paymentAccountId" name="payment_account_id" class="select2 form-control" required>
                                        <option value="">Choose Account ... </option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label class="label-required">Amount</label>
                                    <input class="form-control" type="text" value="{{ $expense->amount }}" id="amount" name="amount" required>
                                </div>
                            </div>
                        </div>

                        <div class="form-group mt-4 float-right">
                            <a href="{{ route('finance.expenses.index') }}" class="btn btn-gradient-danger waves-effect m-l-5">Cancel</a>
                            <button type="submit"
                                id="submitForm"
                                class="btn btn-gradient-primary btn-submit"
                            >
                                Edit Expense
                            </button>
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
    <script src="{{ URL::asset('metrica/assets/plugins/accounting/accounting.js') }}"></script>
    <script src="{{ URL::asset('metrica/assets/plugins/accounting/autoNumeric.min.js') }}"></script>
    <script src="{{ URL::asset('metrica/assets/plugins/datepicker/js/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ URL::asset('metrica/assets/plugins/datepicker/js/bootstrap-datepicker.id.min.js') }}"></script>
    <script src="{{ URL::asset('metrica/assets/pages-material/jquery.forms-advanced.js') }}"></script>
    <!-- Validation jquery -->
    <script src="{{ URL::asset('metrica/plugins/jquery-validation/jquery.validate.min.js') }}"></script>
    <script src="{{ URL::asset('metrica/plugins/jquery-validation/additional-methods.min.js') }}"></script>
    <script src="{{ URL::asset('pages/js/global/time.js') }}"></script>
    <script src="{{ URL::asset('pages/js/global/notifications.js') }}"></script>
    <script src="{{ URL::asset('pages/js/global/stringHelper.js') }}"></script>
    <script src="{{ URL::asset('pages/js/global/serializeObject.min.js') }}"></script>
    <script src="{{ URL::asset('pages/js/expense/form.js') }}"></script>

@endsection
