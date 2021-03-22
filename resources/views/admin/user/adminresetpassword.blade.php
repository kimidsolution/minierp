@extends('layouts.backend.metrica.master')

@section('css')
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
                    </ol>
                </div>
                <h4 class="page-title">Reset Password</h4>
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
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <p class="text-muted mb-4">Please fill in the following form correctly.</p>
                    {!! Form::open(['route' => 'admin.update.user.password', 'class' => 'form-parsley', 'role' => 'form', 'id' => 'jq-validation-form-create']) !!}
                    @csrf
                        {!! Form::hidden('user_id', $idDecrypted) !!}
                        <div class="form-row">
                            <div class="col-md-6 form-group">
                                {!! Form::label('Old Password *') !!}
                                {!! Form::password('old_password', ['class' => 'form-control', 'required']) !!}
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col-md-6 form-group">
                                {!! Form::label('New Password *') !!}
                                {!! Form::password('password', ['class' => 'form-control', 'required']) !!}
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col-md-6 form-group">
                                {!! Form::label('Confirmation New Password *') !!}
                                {!! Form::password('new_password', ['class' => 'form-control', 'required']) !!}
                            </div>
                        </div>
                        <div class="form-group mt-4">
                            <button type="submit" class="btn btn-gradient-primary waves-effect waves-light">
                                Save
                            </button>
                            <a href="{{ route('admin.users.index') }}" class="btn btn-gradient-danger waves-effect m-l-5">Cancel</a>
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
@endsection
