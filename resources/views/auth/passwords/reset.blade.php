<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title>Sempoa - ERP</title>
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta content="Sempoa ERP" name="description" />
        <meta content="" name="author" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />

        <!-- App favicon -->
        <link rel="shortcut icon" href="{{ asset('metrica/assets/images/favicon.ico') }}" />

        <!-- App css -->
        <link href="{{ asset('metrica/assets/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
        <link href="{{ asset('metrica/assets/css/jquery-ui.min.css') }}" rel="stylesheet" />
        <link href="{{ asset('metrica/assets/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
        <link href="{{ asset('metrica/assets/css/metisMenu.min.css') }}" rel="stylesheet" type="text/css" />
        <link href="{{ asset('metrica/assets/css/app.min.css') }}" rel="stylesheet" type="text/css" />
    </head>

    <body class="account-body accountbg">
        <!-- Log In page -->
        <div class="container">
            <div class="row vh-100">
                <div class="col-12 align-self-center">
                    <div class="auth-page">
                        <div class="card auth-card shadow-lg">
                            <div class="card-body">
                                <div class="px-3">
                                    <div class="auth-logo-box">
                                        <a href="{{ url('/') }}" class="logo logo-admin"><img src="{{ asset('metrica/assets/images/logo-sm.png') }}" height="55" alt="logo" class="auth-logo" /></a>
                                    </div>
                                    <!--end auth-logo-box-->

                                    <div class="text-center auth-logo-text">
                                        <h4 class="mt-0 mb-3 mt-5">Reset Password</h4>
                                    </div>
                                    <!--end auth-logo-text-->

                                    <form method="POST" class="form-horizontal auth-form my-4" id="jq-validation-form-reset-password" action="{{ route('password.update') }}">
                                        @csrf

                                        <input type="hidden" name="token" value="{{ $token }}">

                                        <div class="form-group">
                                            <label for="useremail">Email</label>
                                            <div class="input-group mb-3">
                                                <span class="auth-form-icon">
                                                    <i class="dripicons-mail"></i>
                                                </span>
                                                <input type="email" class="form-control" name="email" placeholder="Enter Email" value="{{ $email ?? old('email') }}" />
                                            </div>
                                        </div>
                                        <!--end form-group-->

                                        <div class="form-group">
                                            <label for="userpassword">Password</label>
                                            <div class="input-group mb-3">
                                                <span class="auth-form-icon">
                                                    <i class="dripicons-lock"></i>
                                                </span>
                                                <input type="password" class="form-control" id="password" name="password" placeholder="Enter password" />
                                            </div>
                                        </div>
                                        <!--end form-group-->

                                        <div class="form-group">
                                            <label for="conf_password">Confirm Password</label>
                                            <div class="input-group mb-3">
                                                <span class="auth-form-icon">
                                                    <i class="dripicons-lock-open"></i>
                                                </span>
                                                <input type="password" class="form-control" name="password_confirmation" placeholder="Enter Confirm Password" />
                                            </div>
                                        <!--end form-group-->

                                        </div>
                                        <!--end form-group-->

                                        <div class="form-group mb-0 row">
                                            <div class="col-12 mt-2">
                                                <button class="btn btn-gradient-primary btn-round btn-block waves-effect waves-light" type="submit">Reset <i class="fas fa-sign-in-alt ml-1"></i></button>
                                            </div>
                                            <!--end col-->
                                        </div>
                                        <!--end form-group-->
                                    </form>
                                    <!--end form-->

                                    @if($errors->any())
                                        @foreach ($errors->all() as $error)
                                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                    <span aria-hidden="true"><i class="mdi mdi-close"></i></span>
                                                </button>
                                                <strong>Error!</strong> {{ $error }}
                                            </div>
                                        @endforeach
                                    @endif

                                </div>
                                <!--end /div-->
                            </div>
                            <!--end card-body-->
                        </div>
                        <!--end card-->
                    </div>
                    <!--end auth-card-->
                </div>
                <!--end col-->
            </div>
            <!--end row-->
        </div>
        <!--end container-->
        <!-- End Log In page -->

        <!-- jQuery  -->
        <script src="{{ asset('metrica/assets/js/jquery.min.js') }}"></script>
        <script src="{{ asset('metrica/assets/js/jquery-ui.min.js') }}"></script>
        <script src="{{ asset('metrica/assets/js/bootstrap.bundle.min.js') }}"></script>
        <script src="{{ asset('metrica/assets/js/metis-menu.min.js') }}"></script>
        <script src="{{ asset('metrica/assets/js/waves.js') }}"></script>
        <script src="{{ asset('metrica/assets/js/feather.min.js') }}"></script>
        <script src="{{ asset('metrica/assets/js/jquery.slimscroll.min.js') }}"></script>

        <!-- App js -->
        <script src="{{ asset('metrica/assets/js/app.js') }}"></script>

        <!-- Validation jquery -->
        <script src="{{ URL::asset('metrica/plugins/jquery-validation/jquery.validate.min.js') }}"></script>
        <script src="{{ URL::asset('metrica/plugins/jquery-validation/additional-methods.min.js') }}"></script>
        <script src="{{ URL::asset('metrica/assets/js/jq-validation/form_auth.js') }}"></script>
    </body>
</html>
