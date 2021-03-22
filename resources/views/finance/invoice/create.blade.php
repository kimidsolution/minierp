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
                        <li class="breadcrumb-item active">Input Invoice</li>
                    </ol>
                </div>
                <h4 class="page-title">Input Invoice</h4>
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
                                <label class="name-partner">Pelanggan</label>
                                {!! Form::select('partner_id', $data_partner, null, ['placeholder' => 'Select Customer...', 'class' => 'select2 form-control mb-3 custom-select', 'id' => 'pelanggan', 'name' => 'pelanggan', 'style' => 'width: 100%; height:36px;']) !!}
                            </div>
                            <div class="col-md-2 mb-3 ml-3">
                                <label>Pilih Akun (Aset)</label>
                                {!! Form::select('account_id', $data_aset_accounts, null, ['class' => 'select2 form-control mb-3 custom-select', 'id' => 'akun', 'name' => 'akun', 'style' => 'width: 100%; height:36px;']) !!}
                            </div>
                            <div class="col-md-2 mb-3 ml-3">
                                <div class="form-check-inline my-4">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" name="ispurchase" id="ispurchase" data-parsley-multiple="groups" data-parsley-mincheck="2" value="purchase">
                                        <label class="custom-control-label" for="ispurchase">is Purchase</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-row mb-3">
                            <div class="col-md-2 mb-3">
                                <label for="validationServer01">Tanggal</label>
                                <input class="form-control" type="date" value="" id="tanggal" name="tanggal">
                            </div>
                            <div class="col-md-2 mb-3">
                                <label for="validationServer01">Jatuh Tempo</label>
                                <input class="form-control" type="date" value="" id="duedate" name="duedate">
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="validationServer01">No. Referensi</label>
                                {!! Form::text('invoice_number', $invoice_number, ['class' => 'form-control', 'placeholder' => 'Masukkan no referensi', 'id' => 'noref', 'name' => 'noref', 'readonly']) !!}
                            </div>
                            <div class="col-md-5 mb-3">
                                <label for="validationServer01">Deskripsi</label>
                                <input class="form-control" type="text" placeholder="Masukkan no deskripsi" id="deskripsi" name="deskripsi">
                                <span class="text-danger p-1">{{ $errors->first('deskripsi') }}</span>
                            </div>
                        </div>

                        <div class="form-row mb-2">
                            <table class="table table-bordered mb-0 table-centered">
                                <thead>
                                    <tr>
                                        <th style="width: 40%">Jenis Barang / Jasa</th>
                                        <th style="width: 10%">Jumlah</th>
                                        <th style="width: 20%">Harga Satuan</th>
                                        <th style="width: 20%">Total</th>
                                        <th style="width: 10%">#</th>
                                    </tr>
                                </thead>
                                <tbody id="transactions_table">
                                    <tr>
                                        <td>
                                            <select class="select2 form-control getproduct" style="width: 100%;" id="product-0" ids="'+counter+'" name="product[]"></select>
                                        </td>
                                        <td>
                                            <input class="form-control changeqty only-number" type="text" id="qty-0" name="qty[]" value="0" style="text-align: right;">
                                        </td>
                                        <td>
                                            <input class="form-control" type="text" id="harga-0" name="harga[]" value="0" style="text-align: right;" readonly>
                                        </td>
                                        <td>
                                            <input class="form-control totalproduct" type="text" id="total-0" name="total[]" value="0" style="text-align: right;" readonly>
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
                                    <label for="total-pajak" class="col-sm-4 col-form-label text-left">Sub Total</label>
                                    <div class="col-sm-8">
                                        <input class="form-control" type="text" value="0" id="total-totalbarangjasa" name="totalbarangjasa" style="text-align: right;" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="col-md-6">&nbsp;</div>
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label for="diskon" class="col-sm-4 col-form-label text-left">Diskon ( - ) %</label>
                                    <div class="col-sm-8">
                                        <div class="form-row">
                                            <div class="col-md-3"><input class="form-control only-number" type="text" value="0" name="diskonpersen" id="diskon-persen"></div>
                                            <div class="col-md-9"><input class="form-control" type="text" value="0" id="diskon-harga" name="diskonharga" style="text-align: right;" readonly></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="col-md-6">&nbsp;</div>
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label for="diskon" class="col-sm-4 col-form-label text-left">Pajak</label>
                                    <div class="col-sm-8">
                                        <div class="form-row">
                                            <div class="col-md-3">
                                                <select class="form-control custom-select" id="ppn" name="ppn" style="width: 100%; height:36px;">
                                                    <option value="">Pilih</option>
                                                    <option value="10">PPN</option>
                                            </select>
                                            </div>
                                            <div class="col-md-9"><input class="form-control" type="text" value="0" id="harga-pajak" name="hargapajak" style="text-align: right;" readonly></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
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
                                    <label for="total" class="col-sm-4 col-form-label text-left">Total</label>
                                    <div class="col-sm-8">
                                        <input class="form-control" type="text" value="0" id="subtotal" name="subtotal" style="text-align: right;" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="col-md-6">&nbsp;</div>
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label for="total" class="col-sm-4 col-form-label text-left">Uang Muka ( - )</label>
                                    <div class="col-sm-8">
                                        <input class="form-control auto-currency" type="text" value="0" id="uangmuka" style="text-align: right;" name="uangmuka" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="col-md-6">&nbsp;</div>
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label for="total" class="col-sm-4 col-form-label text-left">Sisa</label>
                                    <div class="col-sm-8">
                                        <input class="form-control" type="text" value="0" name="sisanominal" id="sisauang" style="text-align: right;" readonly>
                                        <div id="validate-sisa-minus" style="color: red; display: none;">
                                            Sisa nominal tidak boleh minus.
                                        </div>
                                        <div id="validate-sisa-nol" style="color: red; display: none;">
                                            Sisa nominal tidak boleh nol (0).
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group mt-5">
                            <button type="button" class="btn btn-gradient-primary dropdown-toggle btn-save" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" disabled>Save <i class="mdi mdi-chevron-down"></i></button>
                            <div class="dropdown-menu">
                                <a class="dropdown-item btn-submit" id="saverecreate" href="#">& Recreate</a>
                                <a class="dropdown-item btn-submit" id="savedetail" href="#">& Go to detail invoice</a>
                            </div>
                            <a href="{{ route('finance.invoices.index') }}"><button type="button" class="btn btn-gradient-danger waves-effect m-l-5">
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
            new AutoNumeric('.auto-currency', { currencySymbol :'Rp. ', decimalCharacter: ',', digitGroupSeparator: '.', decimalPlaces: 0});
            myCheckboxPurchase();

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

                var thisButtonIs = $(this).attr('id');

                var sendAjaxData = [];
                var form = $('#myForm');
                var data = form.serializeObject();

                var v_user_id = {{ Auth::user()->id }};
                var v_partner_id = $("select[name=pelanggan]").val();
                var v_date = $("input[name=tanggal]").val();
                var v_duedate = $("input[name=duedate]").val();
                var v_account_id = $("select[name=akun]").val();
                var v_description = $("input[name=deskripsi]").val();
                var v_invoice_number = $("input[name=noref]").val();
                var v_nominal_discount = parseCurrency($("input[name=diskonharga]").val());
                var v_nominal_tax = parseCurrency($("input[name=hargapajak]").val());
                var v_nominal_sub_total_amount = parseCurrency($("input[name=subtotal]").val());
                var v_nominal_downpayment = parseCurrency($("input[name=uangmuka]").val());
                var v_nominal_remaining = parseCurrency($("input[name=sisanominal]").val());
                // tambah tipe sales/purchase
                var v_type = 'sales';
                if ($('#ispurchase').is(":checked")) {
                    v_type = 'purchase';
                } else {
                    v_type = 'sales';
                }

                if ($('.totalproduct').length > 1) {
                    $('.totalproduct').each(function(i, k) {
                        sendAjaxData[i] = {
                            product_id: data['product[]'][i],
                            qty: data['qty[]'][i],
                            basic_price: parseCurrency(data['harga[]'][i]),
                            total: parseCurrency(data['total[]'][i]),
                        }
                    });
                } else {
                    sendAjaxData = [{
                        product_id: data['product[]'],
                        qty: data['qty[]'],
                        basic_price: parseCurrency(data['harga[]']),
                        total: parseCurrency(data['total[]']),
                    }];
                }

                var dataPost = {
                    user_id: v_user_id,
                    partner_id: v_partner_id,
                    date: v_date,
                    due_date: v_duedate,
                    asset_account_id: v_account_id,
                    description: v_description,
                    invoice_number: v_invoice_number,
                    nominal_discount: v_nominal_discount,
                    nominal_tax: v_nominal_tax,
                    nominal_sub_total_amount: v_nominal_sub_total_amount,
                    nominal_down_payment: v_nominal_downpayment,
                    nominal_remaining: v_nominal_remaining,
                    products: sendAjaxData,
                    type: v_type, // penambahan tipe sales/purchase
                };

                if (dataPost.partner_id == "") {
                    myAlert('Pelanggan harus di isi', '#pelanggan');
                } else if (dataPost.account_id == "") {
                    myAlert('Akun harus di isi', '#akun');
                } else if (dataPost.date == "") {
                    myAlert('date harus di isi', '#tanggal');
                } else if (dataPost.due_date == "") {
                    myAlert('due date harus di isi', '#duedate');
                } else if (dataPost.nominal_sub_total_amount == "0") {
                    myAlert('Belum ada barang/jasa yang di total', '#subtotal');
                } else {
                    $.ajax({
                        type: 'POST',
                        url: '{{ route('api.finance.invoice.create.route') }}',
                        data: dataPost,
                        success:function(data){

                            if (data.status == 'ok') {
                                var message = 'Invoice Berhasil Dibuat';
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

                            if (data.status == 'ok' && thisButtonIs == "savedetail") {                                
                                var url = '{{ route('finance.invoices.show', ['invoice' => ':id']) }}';
                                url = url.replace(':id', data.last_id_invoice);

                                window.location = url;
                            } else {
                                setTimeout(function(){ window.location = "{{ route('finance.invoices.create') }}"; }, 2000);
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

            //get list product first append
            getProduct(0);
            // set default button form append
            myButton();

            // only number in form
            $(".only-number").keypress(function (e) {
                if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
                    return false;
                }
            });
        });

        $(document).on('change', '.getproduct', function(){
            var dataById = {!! $data_products_2 !!}
            var getIdVal = $(this).find(":selected").val();

            let getId = $(this).attr('id');
            var getPositionIndex = getId.split('-');
            var indexForm = getPositionIndex[1];

            jQuery.map(dataById, function(obj) {
                if(obj.id == getIdVal) {
                    var qtyexist = $('#qty-' + indexForm).val();

                    let hargaBarang = obj.harga;
                    let totalHarga = parseInt(hargaBarang) * parseInt(qtyexist);

                    $("#harga-" + indexForm).val(formatCurrency(hargaBarang));
                    $("#total-" + indexForm).val(formatCurrency(totalHarga));                    
                }
            });

            calculationAll();
        });

        $(document).on('keyup', '.changeqty', function(){
            var getIdVal = $(this).val();

            let getId = $(this).attr('id');
            var getPositionIndex = getId.split('-');
            var indexForm = getPositionIndex[1];

            var hargasatuan = $("#harga-" + indexForm).val();

            var numTotal = 0;

            if (getIdVal.length > 0) {
                numTotal = parseInt(parseCurrency(hargasatuan)) * parseInt(getIdVal);
            }

            $("#total-" + indexForm).val(formatCurrency(numTotal));

            calculationAll();
        });    

        $(document).on('keyup', '#diskon-persen', function(){
            calcDiscount();            
            calculationAll();
        });   

        $(document).on('change', '#ppn', function(){
            calcPajak();
            calculationAll();
        }); 

        $(document).on('keyup', '#uangmuka', function(){

            calculationAll();
        });  

        // All Function

        function addRow() {
            var newRow = $("<tr>");
            var cols = "";

            cols += '<td><select class="select2 form-control getproduct" style="width: 100%;" id="product-'+counter+'" ids="'+counter+'" name="product[]"></select></td>';
            cols += '<td><input class="form-control changeqty" type="text" id="qty-'+counter+'" name="qty[]" value="0" style="text-align: right;"></td>';
            cols += '<td><input class="form-control" type="text" id="harga-'+counter+'" name="harga[]" value="0" style="text-align: right;" readonly></td>';
            cols += '<td><input class="form-control totalproduct" type="text" id="total-'+counter+'" name="total[]" value="0" style="text-align: right;" readonly></td>';
            cols += '<td><div class="dropdown d-inline-block float-right"><button type="button" id="btn-add-'+counter+'" class="btn btn-sm btn-gradient-primary waves-effect waves-light my-button-add" title="klik untuk tambah" onclick="addRow()"><i class="fas fa-plus"></i></button> &nbsp; <button type="button" id="btn-del-'+counter+'" class="btn btn-sm btn-gradient-danger waves-effect waves-light my-button-delete" onclick="delRow(this)" title="klik untuk hapus"><i class="far fa-trash-alt ml-1"></i></button></div></td>';

            newRow.append(cols).hide().show('slow');
            $("#transactions_table").append(newRow);
            myButton(counter);
            getProduct(counter);
            counter++;
        }

        function delRow(e) {
            $(e).closest("tr").remove();
            myButton(counter);
            calculationAll();
            // counter--;
        };

        function getProduct(counter){
            var dataById = {!! $data_products !!}

            $("#product-" + counter).select2({
                data: dataById
            });
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

        function calculationAll() {     
            // perubahan total product       
            var sumTotalProduct = 0;

            $('.totalproduct').each(function() {
                sumTotalProduct += Number(parseCurrency($(this).val()));
            });

            $("#total-totalbarangjasa").val(formatCurrency(sumTotalProduct));

            if(sumTotalProduct > 0){
                $("#uangmuka").attr("readonly", false); 
            }
            else{ 
                $("#uangmuka").attr("readonly", true); 
            }

            // perubahan untuk diskon
            calcDiscount();

            //perubahan untuk pajak
            calcPajak();

            //perubahan subtotal
            calcSubTotal();

            //perubahan uang muka
            calcUangMuka();

            // validate minus or nol sisa
            var sisauang = $("#sisauang").val();
            var sisauangInt = parseInt(parseCurrency(sisauang));

            if(sisauangInt > 0){
                $('.btn-save').prop('disabled', false);
                $('#validate-sisa-nol').hide();
                $('#validate-sisa-minus').hide();
            } else { 
                $('.btn-save').prop('disabled', true);
                if (sisauangInt == 0) {
                    $('#validate-sisa-nol').show();
                    $('#validate-sisa-minus').hide();
                } else {
                    $('#validate-sisa-minus').show();
                    $('#validate-sisa-nol').hide();
                }
            }       
        }   

        function calcDiscount() {
            var persendiskon = $('#diskon-persen').val();
            var totalBarangJasa = $("#total-totalbarangjasa").val();
            var valPersen = 0;
            var totalPersen = 0;

            if (persendiskon.length > 0) { 
                valPersen = parseInt($('#diskon-persen').val()) / 100;
                totalPersen = parseInt(parseCurrency(totalBarangJasa)) * valPersen;
            }

            $("#diskon-harga").val(formatCurrency(totalPersen));
        }

        function calcPajak() {
            var totalBarangJasa = $("#total-totalbarangjasa").val();
            var diskonHarga = $("#diskon-harga").val();
            var ppn = 0;

            if ($('#ppn').val() !== "") {                
                var valPersen = parseInt($('#ppn').val()) / 100;
                var totalPpn = (parseInt(parseCurrency(totalBarangJasa)) - parseInt(parseCurrency(diskonHarga))) * valPersen;

                $("#harga-pajak").val(formatCurrency(totalPpn));
            } else {
                $("#harga-pajak").val(formatCurrency(0));
            }
        }

        function calcSubTotal() {
            var totalBarangJasa = $("#total-totalbarangjasa").val();
            var diskonHarga = $("#diskon-harga").val();
            var hargaPajak = $("#harga-pajak").val();

            var subtotal = (parseInt(parseCurrency(totalBarangJasa)) - parseInt(parseCurrency(diskonHarga))) + parseInt(parseCurrency(hargaPajak));

            $("#subtotal").val(formatCurrency(subtotal));
        }

        function calcUangMuka() {
            var uangMuka = $("#uangmuka").val();
            var subtotal = $("#subtotal").val();
            var sisauang = 0;

            if (uangMuka.length > 0) {
                sisauang = parseInt(parseCurrency(subtotal)) - parseInt(parseCurrency(uangMuka));
                $("#sisauang").val(formatCurrency(sisauang));
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

        function myCheckboxPurchase()
        {
            $("#ispurchase").change(function() {
                if(this.checked) {
                    $('.name-partner').text('Vendor');
                } else {
                    $('.name-partner').text('Pelanggan');
                }
            });
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
