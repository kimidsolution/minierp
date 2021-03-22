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
                        <li class="breadcrumb-item active">Detail Invoice Payable</li>
                    </ol>
                </div>
                <h4 class="page-title">Invoice Payable</h4>
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
                                        <td><b>{{ $company->company_name }}</b> <br/><br/> {{ $company->address }}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        <div class="col-md-4 mt-4">
                            <div class="float-right">
                                <table>
                                    <tr>
                                        <td width="120px"><b>Date</b></td>
                                        <td width="10px" style="text-align: left">:</td>
                                        <td style="text-align: left">{{ date('d-m-Y', strtotime($invoice->invoice_date)) }}</td>
                                    </tr>
                                    <tr>
                                        <td width="120px"><b>Invoice Number</b></td>
                                        <td width="10px" style="text-align: left">:</td>
                                        <td style="text-align: left">{{ $invoice->invoice_number }}</td>
                                    </tr>
                                    <tr>
                                        <td width="120px"><b>Due Date</b></td>
                                        <td width="10px" style="text-align: left">:</td>
                                        <td style="text-align: left">{{ date('d-m-Y', strtotime($invoice->due_date)) }}</td>
                                    </tr>
                                    <tr>
                                        <td width="120px"><b>Status</b></td>
                                        <td width="10px" style="text-align: left">:</td>
                                        <td style="text-align: left">{{ ($invoice->is_posted == 2) ? "Draft" : app('data.helper')->getStatusOfInvoice($invoice->payment_status) }}</td>
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
                                    <td style="text-align: left">{{ $partner->partner_name }} - {{ $partner->phone_number }} - {{ $partner->email }}</td>
                                </tr>
                                <tr>
                                    <td width="70px"><b>Address</b></td>
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
                                            <th style="text-align: center">Product / Service</th>
                                            <th style="text-align: center">Type</th>
                                            <th style="text-align: center">Qty</th>
                                            <th style="text-align: center">Price</th>  
                                            <th style="text-align: center">Total Price</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($invoice_detail as $details)
                                            <tr>
                                                <th>{{ app('data.helper')->getNameproducts($details['product_id']) }}</th>
                                                <td style="text-align: center;">{{ app('data.helper')->getTypeOfProduct($details['product_id']) }}</td>
                                                <td style="text-align: center;">{{ $details['quantity'] }}</td>
                                                <td>
                                                    <div class="row">
                                                        <div class="col-6">Rp. </div>
                                                        <div class="col-6"><div class="float-right">{{ number_format($details['price']) }}</div></div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="row">
                                                        <div class="col-6">Rp. </div>
                                                        <div class="col-6"><div class="float-right">{{ number_format($details['quantity'] * $details['price']) }}</div></div>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                        <tr>
                                            <th colspan="3" class="border-0"></th>                                                        
                                            <td class="border-0 font-14"><b>VAT</b></td>
                                            <td class="border-0 font-14"><b>
                                                <div class="row">
                                                    <div class="col-6">Rp. </div>
                                                    <div class="col-6"><div class="float-right">{{ (array_key_exists("ppn", $inv_tax)) ? number_format($inv_tax['ppn']) : number_format(0) }}</div></div>
                                                </div>
                                            </b></td>
                                        </tr>
                                        <tr>
                                            <th colspan="3" class="border-0"></th>                                                        
                                            <td class="border-0 font-14"><b>WithHolding Tax
                                            </b></td>
                                            <td class="border-0 font-14"><b>
                                                <div class="row">
                                                    <div class="col-6">Rp. </div>
                                                    <div class="col-6"><div class="float-right">{{ (array_key_exists("pph23", $inv_tax)) ? number_format($inv_tax['pph23']) : number_format(0) }}</div></div>
                                                </div>
                                            </b></td>
                                        </tr>
                                        <tr>
                                            <th colspan="3" class="border-0"></th>                                                        
                                            <td class="border-0 font-14"><b>Sub Total</b></td>
                                            <td class="border-0 font-14"><b>
                                                <div class="row">
                                                    <div class="col-6">Rp. </div>
                                                    <div class="col-6"><div class="float-right">{{ number_format($invoice->down_payment + $invoice->total_amount) }}</div></div>
                                                </div>
                                            </b></td>
                                        </tr>
                                        <tr>
                                            <th colspan="3" class="border-0"></th>                                                        
                                            <td class="border-0 font-14"><b>Downpayment</b></td>
                                            <td class="border-0 font-14"><b>
                                                <div class="row">
                                                    <div class="col-6">Rp. </div>
                                                    <div class="col-6"><div class="float-right">{{ number_format($invoice->down_payment) }}</div></div>
                                                </div>
                                            </b></td>
                                        </tr>
                                        <tr class="bg-dark text-white">
                                            <th colspan="3" class="border-0"></th>                                                        
                                            <td class="border-0 font-14"><b>Total Remaining</b></td>
                                            <td class="border-0 font-14"><b>
                                                <div class="row">
                                                    <div class="col-6">Rp. </div>
                                                    <div class="col-6"><div class="float-right">{{ number_format($invoice->total_amount) }}</div></div>
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
                                    @if(!is_null($financialManager))
                                        <img src="{{ $financialManager->sign_image }}" alt="" class="sm" height="90">
                                    @else
                                        <img src="" alt="" class="sm" height="90">
                                    @endif
                                </p>
                                    @if(!is_null($financialManager))
                                        <p class="mb-0 text-muted mt-4">{{ $financialManager->name }}</p>
                                    @else
                                        <p class="mb-0 text-muted mt-4"></p>
                                    @endif
                            </div>
                        </div>                                    
                    </div>
                    <hr>
                    <div class="row d-flex">
                        <div class="col-lg-12 col-xl-4">
                            <div class="d-print-none">
                                <a href="{{ route('finance.invoices.payable.index') }}" class="btn btn-gradient-danger">Back</a>
                                @if (2 == $invoice->is_posted)
                                <a href="{{ route('finance.invoices.payable.posted', ['id' => $invoice->id]) }}" class="btn btn-gradient-primary">Posted</a>
                                <a href="{{ route('finance.invoices.payable.edit', ['payable' => $invoice->id]) }}" class="btn btn-gradient-success">Edit Invoice</a>
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
