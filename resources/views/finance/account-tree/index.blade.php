@extends('layouts.backend.metrica.master')

@section('css')
<link href="{{ URL::asset('metrica/plugins/treeview/themes/default/style.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ URL::asset('metrica/plugins/treeview/file-explore.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('content')
<div class="spinner-border thumb-md text-primary part-loader"></div>
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="page-title-box">
                <h4 class="page-title">Account</h4>
            </div>
        </div>
        <!-- end col-->
    </div>
    <!--end row-->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <input type="hidden" id="routeFetch" value="{{ route('api.account.list.account.route') }}">
                <input type="hidden" id="companyId" value="{{ $company->company_id }}">
                <input type="hidden" id="routeFetchStore" value="{{ route('api.account.store.route') }}">
                <input type="hidden" id="userId" value="{{ Auth::user()->id }}">
                <div class="card-body">
                    <h4 class="mt-0 header-title">Account of {{ $company->company->name }}</h4>
                    <div class="div-tree-account">
                    </div>
                </div><!--end card-body-->
            </div>
        </div>
        <!-- end col -->
    </div>
    <!-- end row -->
</div>
<!-- container -->


@endsection

@section('script')
<script src="{{ URL::asset('metrica/plugins/jquery-validation/jquery.validate.min.js') }}"></script>
<script src="{{ URL::asset('pages/js/global/serializeObject.min.js') }}"></script>
<script src="{{ URL::asset('pages/js/global/notifications.js') }}"></script>
<script src="{{ URL::asset('pages/js/global/time.js') }}"></script>
<script src="{{ URL::asset('pages/js/account-tree/index.js') }}"></script>
@endsection
