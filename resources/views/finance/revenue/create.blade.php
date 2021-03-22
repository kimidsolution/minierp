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
                        <li class="breadcrumb-item"><a href="javascript:void(0);">Finance</a></li>
                        <li class="breadcrumb-item"><a href="javascript:void(0);">Pages</a></li>
                        <li class="breadcrumb-item active">Input Revenue</li>
                    </ol>
                </div>
                <h4 class="page-title">Input Revenue</h4>
            </div><!--end page-title-box-->
        </div><!--end col-->
    </div>
    <!--end row-->

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">        
                    <p class="text-muted mb-4">Silahkan isi form berikut dengan benar.</p>

                    <form class="form-parsley" id="myForm" action="#">
                        @csrf
                        <div class="form-row mb-3">
                            <div class="col-md-4 mb-3">
                                <label>Pilih Akun</label>
                                {!! Form::select('account_id', $data_aset_accounts, null, ['class' => 'select2 form-control mb-3 custom-select', 'id' => 'akun', 'name' => 'akun', 'style' => 'width: 100%; height:36px;']) !!}
                            </div>
                        </div>

                        <div class="form-row mb-3">
                            <div class="col-md-2 mb-3">
                                <label for="validationServer01">Tanggal</label>
                                <input class="form-control" type="date" value="" id="tanggal" name="tanggal">
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="validationServer01">No. Referensi</label>
                                {!! Form::text('revenue_number', $revenue_reference_number, ['class' => 'form-control', 'placeholder' => 'Masukkan no referensi', 'id' => 'noref', 'name' => 'noref', 'readonly']) !!}
                            </div>
                            <div class="col-md-7 mb-3">
                                <label for="validationServer01">Deskripsi</label>
                                <input class="form-control" type="text" placeholder="Masukkan no deskripsi" id="deskripsi" name="deskripsi">
                                <span class="text-danger p-1">{{ $errors->first('deskripsi') }}</span>
                            </div>
                        </div>

                        <div class="form-row mb-2">
                            <table class="table table-bordered mb-0 table-centered">
                                <thead>
                                    <tr>
                                        <th style="width: 40%">Akun</th>
                                        <th style="width: 40%">Total</th>
                                        <th style="width: 20%">#</th>
                                    </tr>
                                </thead>
                                <tbody id="transactions_table">
                                    <tr>
                                        <td>
                                            <select class="select2 form-control getakunrev" style="width: 100%;" id="revakun-0" ids="'+counter+'" name="revakun[]"></select>
                                        </td>
                                        <td>
                                            <input class="form-control only-number totalrevenue changetotalrev" type="text" id="revtotal-0" name="revtotal[]" value="0" style="text-align: right;" readonly>
                                        </td>
                                        <td>
                                            <div class="dropdown d-inline-block float-right">
                                                <button type="button" id="btn-add-0" class="btn btn-sm btn-gradient-primary waves-effect waves-light my-button-add" title="klik untuk tambah" onclick="addRow()"><i class="fas fa-plus"></i></button> &nbsp; 
                                                <button type="button" id="btn-del-0" class="btn btn-sm btn-gradient-danger waves-effect waves-light my-button-delete" onclick="delRow(this)" title="klik untuk hapus"><i class="far fa-trash-alt ml-1"></i></button>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="form-row">
                            <div class="col-md-6">&nbsp;</div>
                            <div class="col-md-6">
                                <hr style="border: 0.2px solid #50649c"/>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="col-md-6">&nbsp;</div>
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label for="total" class="col-sm-4 col-form-label text-left">Subtotal</label>
                                    <div class="col-sm-8">
                                        <input class="form-control" type="text" value="0" id="subtotal" name="subtotal" style="text-align: right;" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group mt-5">
                            <button type="submit" class="btn btn-gradient-primary waves-effect waves-light btn-submit">
                                Save
                            </button>
                            <a href="{{ route('finance.revenues.index') }}"><button type="button" class="btn btn-gradient-danger waves-effect m-l-5">
                                Cancel
                            </button></a>
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

    <script>

        var counter = 1;  

        $(document).ready(function(e){
            formatInputCurrency('#revtotal-0');

            // serialize object
            $.fn.serializeObject = function () {
                var o = {};
                var a = this.serializeArray();
                $.each(a, function () {
                    if (o[this.name] !== undefined) {
                        if (!o[this.name].push) {
                            o[this.name] = [o[this.name]];
                        }
                        o[this.name].push(this.value || '');
                    } else {
                        o[this.name] = this.value || '';
                    }
                });
                return o;
            };

            // setup ajax
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            // proses submit
            $(".btn-submit").click(function(e){             
                e.preventDefault();

                var sendAjaxData = [];
                var form = $('#myForm');
                var data = form.serializeObject();

                var v_user_id = {{ Auth::user()->id }};
                var v_date = $("input[name=tanggal]").val();
                var v_amount = parseCurrency($("input[name=subtotal]").val());
                var v_description = $("input[name=deskripsi]").val();
                var v_reference_number = $("input[name=noref]").val();
                var v_paid_to = $("select[name=akun]").val();

                if ($('.totalrevenue').length > 1) {
                    $('.totalrevenue').each(function(i, k) {
                        sendAjaxData[i] = {
                            account_id: data['revakun[]'][i],
                            nominal: parseCurrency(data['revtotal[]'][i])
                        }
                    });
                } else {
                    sendAjaxData = [{
                        account_id: data['revakun[]'],
                        nominal: parseCurrency(data['revtotal[]'])
                    }];
                }

                var dataPost = {
                    user_id: v_user_id,
                    date: v_date,
                    amount: v_amount,
                    description: v_description,
                    reference_number: v_reference_number,
                    paid_to: v_paid_to,
                    items: sendAjaxData,
                };

                if (dataPost.paid_from == "") {
                    myAlert('Akun harus di isi', '#akun');
                } else if (dataPost.date == "") {
                    myAlert('Tanggal harus di isi', '#tanggal');
                } else if (dataPost.amount == "0") {
                    myAlert('Belum ada akun yang di total', '#subtotal');
                } else {

                    $.ajax({
                        type: 'POST',
                        url: '{{ route('finance.revenues.store') }}',
                        data: dataPost,
                        success:function(data){

                            if (data == 'ok') {
                                var message = 'Revenue Berhasil Dibuat';
                                var type = 'success';
                                var title = 'Berhasil';
                            } else {
                                var message = data.message;
                                var type = 'error';
                                var title = 'Gagal';
                            }

                            Swal.fire({
                                type: type,
                                title: title,
                                text: message
                            });

                            if (data == 'ok') {
                                setTimeout(function(){ location.reload(); }, 2000);
                            }

                        },
                        error: function (xhr, status, error) {
                            var err = eval("(" + xhr.responseText + ")");
                            Swal.fire({
                                html: '<strong>Oops!</strong> ' + err.message
                            });
                        }
                    });
                }
            });

            //get list akun first append
            getAccountRevenue(0);

            // set default button form append
            myButton();

            // calculate first
            calculationAll();

            // only number in form
            $(".only-number").keypress(function (e) {
                if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
                    return false;
                }
            });
        });

        $(document).on('keyup', '.changetotalrev', function(){
            calculationAll();
        });

        $(document).on('change', '.getakunrev', function(){
            var getIdVal = $(this).find(":selected").val();
            
            let getId = $(this).attr('id');
            var getPositionIndex = getId.split('-');
            var indexForm = getPositionIndex[1];

            if(getIdVal != ""){
                $("#revtotal-" + indexForm).attr("readonly", false); 
            }
            else{ 
                $("#revtotal-" + indexForm).attr("readonly", true); 
                $("#revtotal-" + indexForm).val(formatCurrency(0));
            }

            calculationAll();
        });

        // All Function

        function addRow() {
            var newRow = $("<tr>");
            var cols = "";

            cols += '<td><select class="select2 form-control getakunrev" style="width: 100%;" id="revakun-'+counter+'" ids="'+counter+'" name="revakun[]"></select></td>';
            cols += '<td><input class="form-control only-number totalrevenue changetotalrev" type="text" id="revtotal-'+counter+'" name="revtotal[]" value="0" style="text-align: right;" readonly></td>';
            cols += '<td><div class="dropdown d-inline-block float-right"><button type="button" id="btn-add-'+counter+'" class="btn btn-sm btn-gradient-primary waves-effect waves-light my-button-add" title="klik untuk tambah" onclick="addRow()"><i class="fas fa-plus"></i></button> &nbsp; <button type="button" id="btn-del-'+counter+'" class="btn btn-sm btn-gradient-danger waves-effect waves-light my-button-delete" onclick="delRow(this)" title="klik untuk hapus"><i class="far fa-trash-alt ml-1"></i></button></div></td>';

            newRow.append(cols).hide().show('slow');
            $("#transactions_table").append(newRow);
            formatInputCurrency('#revtotal-' + counter);
            myButton(counter);
            getAccountRevenue(counter);
            calculationAll();
            counter++;
        }

        function delRow(e) {
            $(e).closest("tr").remove();
            myButton(counter);
            calculationAll();
        };

        function getAccountRevenue(counter){
            var dataById = {!! $data_revenue_accounts !!};

            $("#revakun-" + counter).select2({
                data: dataById
            });
        }

        function calculationAll() {     
            // perubahan total product       
            var sumTotalRevenue = 0;

            $('.totalrevenue').each(function() {
                sumTotalRevenue += Number(parseCurrency($(this).val()));
            });

            $("#subtotal").val(formatCurrency(sumTotalRevenue));         
        } 

        function myButton() {
            var jmlAdd = $('.my-button-add').length;
            var jmlDel = $('.my-button-delete').length;

            $(".my-button-add").each(function( index ) {
                if (jmlAdd !== (index + 1)) {
                    $('#' + $(this).attr('id')).hide();
                } else {
                    $('#' + $(this).attr('id')).show();
                }
            });

            if (jmlDel <= 1) {
                $('.my-button-delete').hide();
            } else {
                $('.my-button-delete').show();
            }
        }

        function myAlert(Msg, focus) {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: Msg,
            })

            $(focus).focus();

            return false;
        }

        function formatCurrency(MyNum)
        {
            let num = accounting.formatMoney(MyNum, "Rp. ", 0, ".", ",");
            return num
        }

        function parseCurrency(MyNum)
        {
            let num = accounting.unformat(MyNum, ",");
            return num
        }

        function formatInputCurrency(nameID)
        {
            return new AutoNumeric(nameID, { currencySymbol :'Rp. ', decimalCharacter: ',', digitGroupSeparator: '.', decimalPlaces: 0});
        }
    </script>

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
    <script src="{{ URL::asset('metrica/assets/pages/jquery.validation.init.js') }}"></script>

    <script src="{{ URL::asset('metrica/assets/plugins/accounting/accounting.js') }}"></script>
    <script src="{{ URL::asset('metrica/assets/plugins/accounting/autoNumeric.min.js') }}"></script>
@endsection
