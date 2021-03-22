@extends('layouts.backend.metrica.master')

@section('css')
<style>
.text-invoice {
    font-size: 30px;
    font-weight: bold;
}

.text-namaperusahaan {
    font-size: 18px;
}
</style>
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
                        <li class="breadcrumb-item active">Detail Invoice</li>
                    </ol>
                </div>
                <h4 class="page-title">Invoice</h4>
            </div><!--end page-title-box-->
        </div><!--end col-->
    </div>
    <!-- end page title end breadcrumb -->
    <div class="row">
        <div class="col-lg-10 mx-auto">
            <div class="card">
                <div class="card-body invoice-head"> 
                    <div class="row justify-content-center text-invoice mb-3">
                        INVOICE
                    </div>
                    <div class="row">
                        <div class="col-md-8">
                            <div class="float-left text-namaperusahaan">
                                <table>
                                    <tr>
                                        <td><img src="{{ ($company->logo == NULL || $company->logo == "") ? URL::asset('metrica/assets/images/no_logo.png') : Storage::disk('logo_company')->url($company->logo) }}" alt="logo-large" class="logo-lg" height="160">   </td>
                                        <td><b>{{ $company->name }}</b> <br/><br/> {{ $company->address }}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="float-right">
                                <table>
                                    <tr>
                                        <td width="120px"><b>Tgl Invoice</b></td>
                                        <td width="10px" style="text-align: left">:</td>
                                        <td style="text-align: left">{{ date('d-m-Y', strtotime($invoice->date)) }}</td>
                                    </tr>
                                    <tr>
                                        <td width="120px"><b>No. Invoice</b></td>
                                        <td width="10px" style="text-align: left">:</td>
                                        <td style="text-align: left">{{ $invoice->number }}</td>
                                    </tr>
                                    <tr>
                                        <td width="120px"><b>Jatuh Tempo</b></td>
                                        <td width="10px" style="text-align: left">:</td>
                                        <td style="text-align: left">{{ date('d-m-Y', strtotime($invoice->due_date)) }}</td>
                                    </tr>
                                    <tr>
                                        <td width="120px"><b>Status</b></td>
                                        <td width="10px" style="text-align: left">:</td>
                                        <td style="text-align: left">{{ ($invoice->is_posted == "no") ? "Draft" : $invoice->status_paid }}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div> 
                    <div class="row">
                        <div class="col-md-11 mt-4">
                            <table>
                                <tr>
                                    <td width="70px"><b>Client</b></td>
                                    <td width="10px" style="text-align: left">:</td>
                                    <td style="text-align: left">{{ $partner->name }} - {{ $partner->phone_number }} - {{ $partner->email }}</td>
                                </tr>
                                <tr>
                                    <td width="70px"><b>Alamat</b></td>
                                    <td width="10px" style="text-align: left">:</td>
                                    <td style="text-align: left">{{ $partner->address }}</td>
                                </tr>
                                <tr>
                                    <td width="70px"><b>NPWP</b></td>
                                    <td width="10px" style="text-align: left">:</td>
                                    <td style="text-align: left">{{ ($partner->tax_id_number == "" || $partner->tax_id_number == NULL) ? "-" : $partner->tax_id_number }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div><!--end card-body-->
                <div class="card-body">
                    <!-- table content -->
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="table-responsive">
                                <table class="table table-bordered mb-0">
                                    <thead>
                                        <tr>
                                            <th style="text-align: center">Produk/Jasa</th>
                                            <th style="text-align: center">Qty</th>
                                            <th style="text-align: center">Price</th>  
                                            <th style="text-align: center">Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($invoice_detail as $details)
                                            <tr>
                                                <th>{{ app('data.helper')->getNameproducts($details['product_id']) }}</th>
                                                <td style="text-align: center;">{{ $details['qty'] }}</td>
                                                <td>
                                                    <div class="row">
                                                        <div class="col-6">Rp. </div>
                                                        <div class="col-6"><div class="float-right">{{ number_format($details['basic_price']) }}</div></div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="row">
                                                        <div class="col-6">Rp. </div>
                                                        <div class="col-6"><div class="float-right">{{ number_format($details['total_price']) }}</div></div>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                        <tr>
                                            <th colspan="2" class="border-0"></th>                                                        
                                            <td class="border-0 font-14"><b>Discount</b></td>
                                            <td class="border-0 font-14" ><b>
                                                <div class="row">
                                                    <div class="col-6">Rp. </div>
                                                    <div class="col-6"><div class="float-right">{{ number_format($invoice->discount) }}</div></div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th colspan="2" class="border-0"></th>                                                        
                                            <td class="border-0 font-14"><b>PPN</b></td>
                                            <td class="border-0 font-14"><b>
                                                <div class="row">
                                                    <div class="col-6">Rp. </div>
                                                    <div class="col-6"><div class="float-right">{{ number_format($invoice->total_tax) }}</div></div>
                                                </div>
                                            </b></td>
                                        </tr>
                                        <tr>
                                            <th colspan="2" class="border-0"></th>                                                        
                                            <td class="border-0 font-14"><b>Sub Total</b></td>
                                            <td class="border-0 font-14"><b>
                                                <div class="row">
                                                    <div class="col-6">Rp. </div>
                                                    <div class="col-6"><div class="float-right">{{ number_format($invoice->down_payment + $invoice->final_amount) }}</div></div>
                                                </div>
                                            </b></td>
                                        </tr>
                                        <tr>
                                            <th colspan="2" class="border-0"></th>                                                        
                                            <td class="border-0 font-14"><b>Uang Muka</b></td>
                                            <td class="border-0 font-14"><b>
                                                <div class="row">
                                                    <div class="col-6">Rp. </div>
                                                    <div class="col-6"><div class="float-right">{{ number_format($invoice->down_payment) }}</div></div>
                                                </div>
                                            </b></td>
                                        </tr>
                                        <tr class="bg-dark text-white">
                                            <th colspan="2" class="border-0"></th>                                                        
                                            <td class="border-0 font-14"><b>Sisa</b></td>
                                            <td class="border-0 font-14"><b>
                                                <div class="row">
                                                    <div class="col-6">Rp. </div>
                                                    <div class="col-6"><div class="float-right">{{ number_format($invoice->final_amount) }}</div></div>
                                                </div>
                                            </b></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>                                            
                        </div>                                        
                    </div>

                    <div class="row mt-4">
                        <div class="col-md-6">
                        </div>            
                        <div class="col-md-6">
                            <div class="float-right text-center">
                                <h6 class="font-13 mb-4">Account Manager</h6>
                                <p class="mb-0 text-muted">
                                    <img src="{{ $financialManager->sign_image }}" alt="" class="sm" height="90">
                                </p>
                                <p class="mb-0 text-muted mt-4">{{ $financialManager->name }}</p>
                            </div>    
                        </div>                                       
                    </div>
                    <hr>
                    <div class="row d-flex">
                        <div class="col-lg-12 col-xl-4">
                            <div class="d-print-none">
                                {{-- <a href="javascript:window.print()" class="btn btn-gradient-info">Print</a> --}}
                                <a href="{{ route('finance.invoices.index') }}" class="btn btn-gradient-danger">Back</a>
                                @if ('no' == $invoice->is_posted)
                                <a href="{{ route('finance.invoices.posted', ['id' => $invoice->id]) }}" class="btn btn-gradient-primary">Posted</a>
                                <a href="{{ route('finance.invoices.edit', ['invoice' => $invoice->id]) }}" class="btn btn-gradient-success">Edit Invoice</a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div><!--end card-->
        </div><!--end col-->
    </div><!--end row-->

</div>
<!-- container -->

@endsection

@section('script')
@endsection
