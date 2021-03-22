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
                        <li class="breadcrumb-item active">Edit User</li>
                    </ol>
                </div>
                <h4 class="page-title">Edit User</h4>
            </div><!--end page-title-box-->
        </div><!--end col-->
    </div>
    <!--end row-->

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

    @if ($errors->any())
        @foreach ($errors->all() as $error)
        <div class="row">
            <div class="col-lg-12">
            <div class="alert icon-custom-alert alert-outline-pink b-round fade show" role="alert">
                <i class="mdi mdi-alert-outline alert-icon"></i>
                <div class="alert-text">
                    <strong>{{ $error }}</strong>
                </div>

                <div class="alert-close">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true"><i class="mdi mdi-close text-danger"></i></span>
                    </button>
                </div>
            </div>
            </div>
        </div>
        @endforeach
    @endif

    <div class="row">
        <div class="col-lg-6">
            <div class="card">
                <div class="card-body">
                    <p class="text-muted mb-4">Please fill in the following form correctly.</p>

                    <form class="form-parsley" method="POST" action="{{ route('admin.users.update', ['user' => $user->id]) }}" role="form" role="form" id="jq-validation-form-create">
                    @csrf
                    {{ Form::hidden('_method', 'PUT') }}
                    {{ Form::hidden('id', $user->id) }}
                        <div class="form-row d-flex justify-content-center">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="label-required">Company Name</label>
                                    {!! Form::text('company_name', $user->company->company_name,  ['class' => 'form-control', 'readonly']) !!}
                                </div>
                                <div class="form-group">
                                    <label class="label-required">Name</label>
                                    {!! Form::text('name', $user->name,  ['class' => 'form-control', 'required']) !!}
                                </div>
                                <div class="form-group">
                                    <label class="label-required">Email</label>
                                    {!! Form::text('email', $user->email,  ['class' => 'form-control', 'readonly']) !!}
                                </div>
                                <div class="form-group">
                                    <label class="label-required">Phone Number</label>
                                    <input type="text"
                                        required
                                        maxlength="50"
                                        name="phone_number"
                                        class="form-control"
                                        value="{{ old('phone_number', $user->phone_number) }}"
                                        onkeypress='return event.charCode >= 48 && event.charCode <= 57'
                                    >
                                </div>
                                <div class="form-group">
                                    {!! Form::label('Title ') !!}
                                    <select name="title" class="select2 form-control">
                                        <option value=""> Choose Title ... </option>
                                        <option  value="Mr." {{ $user->title == 'Mr.' ? 'selected' : ''}}>Mr.</option>
                                        <option value="Ms." {{ $user->title == 'Ms.' ? 'selected' : ''}}>Ms.</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    {!! Form::label('Job ') !!}
                                    {!! Form::text('job', $user->job,  ['class' => 'form-control']) !!}
                                </div>
                                <div class="form-group">
                                    {!! Form::label('Address') !!}
                                    {!! Form::textarea('address', old('address', $user->address), ['rows' => 3, 'class' => 'form-control']) !!}
                                </div>
                                <div class="form-group">
                                    {!! Form::label('Role ') !!}
                                    {!! Form::select(
                                        'role',
                                        array_merge(['' => 'Choose Role User ...'], $roles),
                                        $userRole,
                                        ['class' => 'select2 form-control mb-3 custom-select']
                                    ) !!}
                                </div>
                                <div class="form-group mt-5">
                                    <a href="{{ route('admin.users.index') }}" class="btn btn-gradient-danger waves-effect m-l-5">Cancel</a>
                                    <button type="submit" class="btn btn-gradient-primary waves-effect waves-light">
                                        Update
                                    </button>
                                </div><!--end form-group-->
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="float-right">
                                    @if (0 !== $user->status)
                                        <button type="button" class="btn btn-gradient-secondary waves-effect waves-light btn-status" data-id="{{ $user->id }}" data-value="{{ $user->name }}" data-status="0">New</button>
                                    @endif
                                    @if (1 !== $user->status)
                                        <button type="button" class="btn btn-gradient-warning waves-effect waves-light btn-status" data-id="{{ $user->id }}" data-value="{{ $user->name }}" data-status="1">Active</button>
                                    @endif
                                    @if (2 !== $user->status)
                                        <button type="button" class="btn btn-gradient-info waves-effect waves-light btn-status" data-id="{{ $user->id }}" data-value="{{ $user->name }}" data-status="2">Inactive</button>
                                    @endif
                                    <input type="hidden" id="routeUpdateStatus" value="{{ route('api.admin.user.update.route') }}">
                                    <input type="hidden" id="routeRedirectToIndex" value="{{ route('admin.users.index') }}">
                                    <input type="hidden" id="userId" value="{{ Auth::user()->id }}">
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
        const token = $('meta[name="csrf-token"]').attr('content');
        const routeUpdateStatus = $("#routeUpdateStatus").val();
        const routeRedirectToIndex = $("#routeRedirectToIndex").val();

        // setup ajax
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // click status
        $(".btn-status").on('click', function (e) {
            let id = $(this).data("id");
            let name = $(this).data("value");
            let status = $(this).data("status");
            let userId = $("#userId").val();
            let msg = '';
            let msg2 = '';
            let msg3 = '';
            let status_id = 0; // Status as NEW
            let route = routeUpdateStatus;
            let redirect = routeRedirectToIndex;

            if (status == "0") {
                msg = "new";
                msg2 = "New";
                msg3 = "New";
                status_id = 0; // Status as New
            } else if (status == "1") {
                msg = "active";
                msg2 = "Activation";
                msg3 = "Activated";
                status_id = 1; // Status as ACTIVE
            } else if (status == "2") {
                msg = "inactive";
                msg2 = "Inactivation";
                msg3 = "Inactivated";
                status_id = 2; // Status as INACTIVE
            }

            console.log(route);

            softAction(id, name, route, userId, msg, msg2, msg3, status_id, redirect);

            function softAction(id, name, route_name, userId, msg, msg2, msg3, status_id, redirect) {
                Swal.fire({
                    title: 'Please confirm',
                    text: msg2 + ' of User ' + name,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#ef4d56',
                    cancelButtonColor: '#50b380',
                    confirmButtonText: 'Yes, ' + msg +' it!',
                    html: false,
                    preConfirm: (e) => {
                        return new Promise((resolve) => {
                            setTimeout(() => {
                                resolve();
                            }, 50);
                        });
                    }
                }).then((result) => {
                    if (result.value) {
                        $.ajax({
                            url: route_name,
                            type: "POST",
                            data: {
                                "user_id": id,
                                "updated_by": userId,
                                "status_id": status_id
                            },
                            success: function(response) {
                                if (response == 'ok') {
                                    alertNotify("Success, User"+ " " + name +" has been " + msg3)
                                    setTimeout(function(){ window.location = redirect; }, 2000);
                                } else {
                                    alertNotify("Something went wrong!")
                                }
                            },
                            error: function (xhr, status, error) {
                                var err = eval("(" + xhr.responseText + ")");
                                Swal.fire({
                                    html: '<strong>Oops!</strong> ' + err.error.message
                                });
                            }
                        });
                    }
                })
            }

            function alertNotify(Msg) {
                Swal.fire({
                    icon: 'info',
                    title: 'Info',
                    text: Msg,
                })

                return true;
            }
        });
    </script>
@endsection
