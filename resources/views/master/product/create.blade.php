@extends('layouts.backend.metrica.master')

@section('css')
    <link href="{{ URL::asset('metrica/assets/plugins/dropify/css/dropify.min.css') }}" rel="stylesheet">

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
                        <li class="breadcrumb-item"><a href="/currencies">Product</a></li>
                        <li class="breadcrumb-item active">Create Product</li>
                    </ol>
                </div>
                <h4 class="page-title">Create Product</h4>
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
                    {!! Form::open(['route' => 'master.product.store', 'class' => 'form-parsley', 'role' => 'form', 'id' => 'jq-validation-form-create', 'enctype' => 'multipart/form-data']) !!}
                    @csrf
                    <div class="form-row d-flex justify-content-center">
                        <div class="col-md-12">
                            @if ($isAdmin)
                                <div class="form-group">
                                    <label class="label-required">Company</label>
                                    <select name="company_id" class="select2 form-control select-company" required>
                                        <option value=""> Choose Company ... </option>
                                        @foreach ($list_company as $company)
                                            <option value="{{ $company->id }}">
                                                {{ $company->company_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            @endif
                            <div class="form-group">
                                <label class="label-required">Product Name</label>
                                {!! Form::text('product_name', old('product_name'), ['class' => 'form-control', 'autocomplete' => 'off']) !!}
                            </div>
                            <div class="form-group">
                                <label class="label-required">Product Category</label>
                                {!! Form::select('product_category', [], old('product_category'), ['placeholder' => 'Select Product Category...', 'class' => 'select2 form-control mb-5 custom-select', 'id' => 'product_category', 'name' => 'product_category', 'style' => 'width: 100%;']) !!}
                            </div>
                            <div class="form-group mb-4">
                                <button type="button" id="add-button" class="btn btn-outline-light btn-gradient-info btn-sm btn-modal">[+] Add New Product Category</button>
                            </div>                  
                            <div class="form-group">
                                <label class="label-required">SKU</label>
                                {!! Form::text('sku', old('sku'), ['class' => 'form-control', 'autocomplete' => 'off']) !!}
                            </div>
                            <div class="form-group">
                                <label class="label">Price</label>
                                {!! Form::text('price', old('price'), ['class' => 'form-control', 'autocomplete' => 'off', 'id' => 'price']) !!}
                            </div>                            
                            <div class="form-group">
                                <label class="label-required">Type</label>
                                <div class="radio-buttons">
                                    <label class="custom-radio">
                                        <input type="radio" class="form-control" name="type" value="1" required checked>
                                        <span class="radio-btn">
                                        <i class="fa fa-check"></i>
                                            <div class="option-check">
                                                <h3>Goods</h3>
                                            </div>
                                        </span>
                                    </label>
                                    <label class="custom-radio">
                                        <input type="radio" class="form-control" name="type" value="2" required>
                                        <span class="radio-btn">
                                        <i class="fa fa-check"></i>
                                        <div class="option-check">
                                            <h3>Service</h3>
                                        </div>
                                        </span>
                                    </label>
                                </div>
                            </div> 
                            <div class="form-group mt-5">
                                <button type="submit" class="btn btn-gradient-primary waves-effect waves-light">
                                    Save
                                </button>
                                <a href="{{ route('master.product.index') }}" class="btn btn-gradient-danger waves-effect m-l-5">Cancel</a>

                                <input type="hidden" id="routeListProductCategory" value="{{ route('api.product.category.list.by.company.route') }}">
                                <input type="hidden" id="routeStoreProductCategory" value="{{ route('api.product.category.store.route') }}">
                                <input type="hidden" id="companyID" value="{{ ($isAdmin) ? '' : Auth::user()->company_id }}">
                                <input type="hidden" id="adminStatus" value="{{ $isAdmin }}">
                                <input type="hidden" id="userId" value="{{ Auth::user()->id }}">
                            </div><!--end form-group-->
                        </div>
                    </div>
                    {!! Form::close() !!}<!--end form-->        
                </div><!--end card-body-->
            </div><!--end card-->
        </div> <!-- end col -->
    </div>
</div>
<!-- container -->

<!-- modal -->
<div class="modal fade" id="categoryModalInput" tabindex="-1" role="dialog" aria-labelledby="categoryModalInputLabel" aria-hidden="true">
    <form id="categoryModal" method="post" class="form-horizontal">
    @csrf
        <div class="modal-dialog" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title mt-0" id="categoryModalInputLabel">Create Product Category</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="col-md-12 form-group">
                    <label class="label-required">Product Category Name</label>                    
                    {!! Form::text('category_product_name', '', ['class' => 'form-control clear-input', 'autocomplete' => 'off', 'id' => 'category_product_name']) !!}
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary" id="submitModal">Save</button>
            </div>
            </div>
        </div>
    </form>
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
    <script src="{{ URL::asset('metrica/assets/pages-material/jquery.forms-advanced.js') }}"></script>

    <!-- Parsley js -->
    <script src="{{ URL::asset('metrica/assets/plugins/parsleyjs/parsley.min.js') }}"></script>
    <script src="{{ URL::asset('metrica/assets/plugins/dropify/js/dropify.min.js') }}"></script>
    <script src="{{ URL::asset('metrica/assets/pages/jquery.form-upload.init.js') }}"></script>

    <!-- Validation jquery -->
    <script src="{{ URL::asset('metrica/plugins/jquery-validation/jquery.validate.min.js') }}"></script>
    <script src="{{ URL::asset('metrica/plugins/jquery-validation/additional-methods.min.js') }}"></script>
    <script src="{{ URL::asset('metrica/assets/js/jq-validation/form_product.js') }}"></script> 
    
    <!-- Form jquery --> 
    <script src="{{ URL::asset('metrica/assets/plugins/accounting/accounting.js') }}"></script>
    <script src="{{ URL::asset('metrica/assets/plugins/accounting/autoNumeric.min.js') }}"></script>
    <script src="{{ URL::asset('pages/js/product/formInput.js') }}"></script>
@endsection
