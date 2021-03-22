@extends('layouts.backend.metrica.master')

@section('css')
<link href="{{ URL::asset('metrica/plugins/dropify/css/dropify.min.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="page-title-box">
                <h4 class="page-title">Profile</h4>
            </div>
        </div>
        <!-- end col-->
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
        <div class="col-lg-12 mx-auto">
            <div class="card">
                <div class="card-body">
                    <div class="">
                        {!! Form::open(['route' => 'profile.store', 'class' => 'form-horizontal form-material mb-0', 'files' => true]) !!}
                            @csrf
                            <div class="form-row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        {!! Form::label('Name') !!}
                                        {!! Form::text('name', $user->name, ['class' => 'form-control', 'required']) !!}
                                    </div>
                                    <div class="form-group">
                                        {!! Form::label('Email') !!}
                                        {!! Form::email('email', $user->email, ['class' => 'form-control', 'required']) !!}
                                    </div>
                                    <div class="form-group">
                                        {!! Form::label('Signature') !!}
                                        <input type="file" id="input-file-now" name="sign" class="dropify" accept="image/*" data-default-file="{{ Storage::disk('user_sign')->url($user->signature) }}" /> 
                                    </div>
                                    <div class="form-group mt-4">
                                        <button type="submit" class="btn btn-gradient-primary waves-effect waves-light">
                                            Save
                                        </button>
                                        <a href="{{ route('home') }}" class="btn btn-gradient-danger waves-effect m-l-5">Cancel</a>
                                    </div><!--end form-group-->
                                </div>
                            </div>
                        {!! Form::close() !!}<!--end form-->
                    </div>
                </div>                                            
            </div>
        </div> <!--end col-->                                          
    </div><!--end row-->
</div>
<!-- container -->

@endsection

@section('script')
<script src="{{ URL::asset('metrica/plugins/dropify/js/dropify.min.js') }}"></script>
<script src="{{ URL::asset('metrica/assets/pages/jquery.form-upload.init.js') }}"></script>
@endsection
