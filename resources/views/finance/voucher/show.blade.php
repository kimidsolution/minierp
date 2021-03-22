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
                        <li class="breadcrumb-item active">Detail Voucher</li>
                    </ol>
                </div>
                <h4 class="page-title">Voucher</h4>
            </div><!--end page-title-box-->
        </div><!--end col-->
    </div>
    <!-- end page title end breadcrumb -->
    <div class="row">
        <div class="col-lg-10 mx-auto">
            <div class="card">
                <div class="card-body invoice-head"> 
                    <div class="row justify-content-center text-invoice mb-3">
                        VOUCHER
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
                                        <td width="150px"><b>Date</b></td>
                                        <td width="10px" style="text-align: left">:</td>
                                        <td style="text-align: left">{{ date('d-m-Y', strtotime($voucher->voucher_date)) }}</td>
                                    </tr>
                                    <tr>
                                        <td width="150px"><b>Voucher Number</b></td>
                                        <td width="10px" style="text-align: left">:</td>
                                        <td style="text-align: left">{{ $voucher->voucher_number }}</td>
                                    </tr>
                                    <tr>
                                        <td width="150px"><b>Voucher Type</b></td>
                                        <td width="10px" style="text-align: left">:</td>
                                        <td style="text-align: left">{{ app('data.helper')->getTypeOfVoucher($voucher->voucher_type) }}</td>
                                    </tr>
                                    <tr>
                                        <td width="150px"><b>Status</b></td>
                                        <td width="10px" style="text-align: left">:</td>
                                        <td style="text-align: left">{{ ($voucher->is_posted == 2) ? "Draft" : 'Posted' }}</td>
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
                                    <td style="text-align: left">{{ is_null($partner) ? null : $partner->partner_name }} - {{ is_null($partner) ? null : $partner->phone_number }} - {{ is_null($partner) ? null : $partner->email }}</td>
                                </tr>
                                <tr>
                                    <td width="70px"><b>Address</b></td>
                                    <td width="10px" style="text-align: left">:</td>
                                    <td style="text-align: left">{{ is_null($partner) ? null : $partner->address }}</td>
                                </tr>
                                <tr>
                                    <td width="70px"><b>NPWP</b></td>
                                    <td width="10px" style="text-align: left">:</td>
                                    @if (is_null($partner))
                                    <td style="text-align: left"></td>
                                    @else
                                        <td style="text-align: left">{{ ($partner->tax_id_number == "" || $partner->tax_id_number == NULL) ? "-" : $partner->tax_id_number }}</td>  
                                    @endif
                                </tr>
                            </table>
                        </div>
                    </div>
                </div><!--end card-body-->
                <div class="card-body">

                    <div class="row">
                        <div class="col-lg-12">
                        @php $sum_amount_all = 0; @endphp
                        @foreach ($invoices as $details)
                            <div class="card"> 
                                <h5 class="card-header bg-secondary text-white mt-0">
                                    <div class="row">
                                        <div class="col-6">No. Invoice : {{ $details['invoice_number'] }}</div>
                                        <div class="col-6"><div class="float-right">{{ $details['note'] }}</div></div>
                                    </div>    
                                </h5>                                       
                                <div class="card-body">
                                    <table class="table table-hover table-sticky">
                                        <tbody>
                                            <tr>
                                                <td data-column="info" class="text-left" style="padding: 10px 10px 10px 10px; color: #425176; font-size: 13px;">
                                                    <b>Amount Paid</b>
                                                </td>
                                                <td data-column="amount" class="text-right" style="font-size: 13px;">
                                                    {{ app('string.helper')->defFormatCurrency($details['pivot']['amount'], "Rp ") }}
                                                </td>
                                            </tr>
                                            @php $sum_expense = 0; @endphp
                                            @foreach ($voucher_detail as $voucher_details)
                                                @if ($voucher_details->invoice_id == $details['id'])
                                                    @foreach ($voucher_details->voucher_detail_expenses as $voucher_detail_expenses)
                                                        <tr>
                                                            <td data-column="info" class="text-left" style="padding: 10px 10px 10px 20px; color: #425176; font-size: 13px;">
                                                                <b>{{ app('data.helper')->getAccountName($voucher_detail_expenses['account_id']) }}</b>
                                                            </td>
                                                            <td data-column="amount" class="text-right" style="font-size: 13px; color: #e43636;">
                                                                ( {{ app('string.helper')->defFormatCurrency($voucher_detail_expenses['amount'], "Rp ") }} )
                                                            </td>
                                                        </tr>
                                                        @php $sum_expense += $voucher_detail_expenses['amount']; @endphp
                                                    @endforeach
                                                @endif
                                            @endforeach
                                        </tbody>
                                    </table>                                       
                                </div><!--end card-body-->
                                <p class="card-footer bg-light m-0 text-right"><b>{{ app('string.helper')->defFormatCurrency($details['pivot']['amount'] - $sum_expense, "Rp ") }}</b></p>
                            </div>
                            @php $sum_amount_all += ($details['pivot']['amount'] - $sum_expense); @endphp
                        @endforeach
                        </div>
                    </div>

                    <!-- table content -->
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="table-responsive">
                                <table class="table table-bordered mb-0">
                                    <tbody>
                                        <tr class="bg-dark text-white">
                                            <th class="border-0"></th>                                                        
                                            <td class="border-0 font-14"><b>
                                                <div class="row">
                                                    <div class="col-6"></div>
                                                    <div class="col-6"><div class="float-right">Sub Total</div></div>
                                                </div>
                                            </b></td>
                                            <td class="border-0 font-14"><b>
                                                <div class="row">
                                                    <div class="col-6"><div class="float-right">Rp. </div></div>
                                                    <div class="col-6"><div class="float-right">{{ number_format($sum_amount_all) }}</div></div>
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
                                <a href="{{ route('finance.vouchers.index') }}" class="btn btn-gradient-danger">Back</a>
                                @if (2 == $voucher->is_posted)
                                <a href="{{ route('finance.vouchers.posted', ['id' => $voucher->id]) }}" class="btn btn-gradient-primary">Posted</a>
                                <a href="{{ route('finance.vouchers.edit', ['voucher' => $voucher->id]) }}" class="btn btn-gradient-success">Edit Voucher</a>
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
