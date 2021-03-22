@extends('layouts.backend.metrica.master')

@section('css')
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
                        <li class="breadcrumb-item"><a href="/users">User</a></li>
                        <li class="breadcrumb-item active">Create User</li>
                    </ol>
                </div>
                <h4 class="page-title">Create User</h4>
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
                    {!! Form::open(['route' => 'admin.users.store', 'class' => 'form-parsley', 'role' => 'form', 'id' => 'jq-validation-form-create']) !!}
                        @csrf
                        <div class="form-row d-flex justify-content-center">
                            <div class="col-md-12">
                                @if ($isSuperOpsAdmin)
                                <div class="form-group">
                                    <label class="label-required">Company Name</label>
                                    {!! Form::select(
                                        'company_id',
                                        $dataCompany,
                                        old('company_id'),
                                        ['class' => 'select2 form-control mb-3 custom-select', 'id' => 'company_id', 'name' => 'company_id', 'style' => 'width: 100%; height:36px;']
                                    ) !!}
                                </div>
                                @else
                                    {!! Form::hidden('company_id', $user->company_id,  ['class' => 'form-control', 'required']) !!}
                                @endif
                                <div class="form-group">
                                    <label class="label-required">Name</label>
                                    {!! Form::text('name', old('name'),  ['class' => 'form-control', 'required']) !!}
                                </div>
                                <div class="form-group">
                                    <label class="label-required">Email</label>
                                    {!! Form::text('email', old('email'),  ['class' => 'form-control', 'required']) !!}
                                </div>
                                <div class="form-group">
                                    <label class="label-required">Phone Number</label>
                                    <input type="text"
                                        required
                                        maxlength="50"
                                        name="phone_number"
                                        class="form-control"
                                        value="{{ old('phone_number') }}"
                                        onkeypress='return event.charCode >= 48 && event.charCode <= 57'
                                    >
                                </div>
                                <div class="form-group">
                                    {!! Form::label('Title ') !!}
                                    <select name="title" class="select2 form-control">
                                        <option value=""> Choose Title ... </option>
                                        <option value="Mr." {{ old('title') == 'Mr.' ? 'selected' : ''}}>Mr.</option>
                                        <option value="Ms." {{ old('title') == 'Ms.' ? 'selected' : ''}}>Ms.</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    {!! Form::label('Job ') !!}
                                    {!! Form::text('job', old('job'),  ['class' => 'form-control']) !!}
                                </div>
                                <div class="form-group">
                                    {!! Form::label('Address') !!}
                                    {!! Form::textarea('address', old('address'),  ['class' => 'form-control', 'rows' => 2]) !!}
                                </div>
                                <div class="form-group">
                                    <label class="label-required">Role</label>
                                    <select id="role" name="role" class="select2 form-control mb-3 custom-select" required>
                                        <option value="">Choose Role User ... </option>
                                    </select>
                                </div>
                                <div class="form-group mt-4 float-right">
                                    <a href="{{ route('admin.users.index') }}" class="btn btn-gradient-danger waves-effect m-l-5">Cancel</a>
                                    <button type="submit" class="btn btn-gradient-primary waves-effect waves-light">
                                        Save
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

<input type="hidden" id="routeFetchRoleByCompanyId" value="{{ route('api.select2.get.role.by.company.route') }}">
<input type="hidden" id="companyIdFieldInput" value="{{ $user->company_id }}">
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

    <!-- Validation jquery -->
    <script src="{{ URL::asset('metrica/plugins/jquery-validation/jquery.validate.min.js') }}"></script>
    <script src="{{ URL::asset('metrica/assets/js/jq-validation/form_user.js') }}"></script>
@endsection


@section('script-bottom')
    <script>
        $(document).ready(function() {

            $("#company_id").select2();
            $("#role").select2();

            const token = $('meta[name="csrf-token"]').attr('content');
            const routeFetchRoleByCompanyId = $("#routeFetchRoleByCompanyId").val();

            let companyIdValue = null;
            let companyIdSelect = $("#company_id").val();

            if (typeof companyIdSelect === 'undefined') {
                companyIdValue = $("#companyIdFieldInput").val();
            } else {
                $("#companyIdFieldInput").val(companyIdSelect);
                companyIdValue = $("#companyIdFieldInput").val();
            }

            // company event change
            $('#company_id').change(function() {
                $("#companyIdFieldInput").val($(this).val());
                companyIdValue = $("#companyIdFieldInput").val();
                fetchRoleByCompanyId(companyIdValue);
            });

            // company not change
            fetchRoleByCompanyId(companyIdValue);


            function fetchRoleByCompanyId(companyId) {
                let request = {
                    "company_id": companyId
                }

                $.ajaxSetup({
                    headers: { "X-CSRF-TOKEN": token },
                });

                $.ajax({
                    url: routeFetchRoleByCompanyId,
                    type: "POST",
                    dataType: "json",
                    data: request,
                    success: function(response) {
                        if (response.length > 0) {
                            response.forEach((data) => {
                                let option = new Option(data.text, data.id, false, false)
                                $('#role').append(option)
                            })
                        }
                    },
                    error: function(xhr, status, error) {
                        var err = eval("(" + xhr.responseText + ")");
                        Swal.fire({
                            html: '<strong>Oops!</strong> ' +err.Message
                        });
                    }
                });
            }
        });
    </script>
@endsection
