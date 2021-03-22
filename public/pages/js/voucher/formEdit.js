const configCodeAccount = $("#configCodeAccount").val();
const isAdmin = $("#isAdmin").val()
var counter = 0;
var counterAdditional = 0;

$(function () {
    const user_id = $("#userId").val();
    const adminStatus = $("#adminStatus").val();
    const routeStoreVoucher = $("#routeStoreVoucher").val();
    const companyIdDefault = $("#companyID").val();
    const voucherJson = JSON.parse($("#voucherJson").val());

    // All Selector
    const selectorTanggal = $("#tanggal");

    // date format set
    selectorTanggal.datepicker({
        autoclose:true,
        todayHighlight:true,
        format:'dd-mm-yyyy'
    });

    // set default button form append
    // myButton();
    // myButtonAdditional();

    // get first expense list
    // getListExpense("0-0");

    // set list type invoice
    getListTypeInvoice(companyIdDefault, voucherJson.voucher_type);

    // set value to partner
    fetchDataPartner(user_id, voucherJson.voucher_type, companyIdDefault, voucherJson.partner_id);

    // set list account
    getListAccountAsset(voucherJson.payment_account_id);

    //get data when edit to form edit
    addRowEdited(voucherJson);

    // cek admin status
    if (adminStatus == true) {
        $("#company_id").select2({ disabled: true });
    }

    // check configuration finance account
    if (!isAdmin) checkFinanceConfigAccountCompany(companyIdDefault, configCodeAccount)

    // only number in form
    $(".only-number").keypress(function (e) {
        if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
            return false;
        }
    });

    // setup ajax
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

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

    // modal validate
    $('#myForm').validate({
        rules: {
            'company_id': {
                required: true
            },
            'pelanggan': {
                required: true
            },
            'akun': {
                required: true
            },
            'voucher_number': {
                required: true
            },
            'deskripsi': {
                required: true
            },
            'type_voucher': {
                required: true
            },
            'tanggal': {
                required: true
            }
        },
        errorClass: 'invalid-feedback animated fadeIn',
        errorElement: 'div',
        errorPlacement: (error, el) => {
            jQuery(el).addClass('is-invalid');
            jQuery(el).parents('.form-group').append(error);
        },
        highlight: (el) => {
            jQuery(el).parents('.form-group').find('.is-invalid').removeClass('is-invalid').addClass('is-invalid');
        },
        success: (el) => {
            jQuery(el).parents('.form-group').find('.is-invalid').removeClass('is-invalid');
            jQuery(el).remove();
        }
    });

    // proses submit
    $(".btn-submit").click(function(e){
        e.preventDefault();
        if ($("#myForm").valid()) {

            var thisButtonIs = $(this).attr('id');

            var sendAjaxData = [];
            var dataAmount = [];
            var form = $('#myForm');
            var data = form.serializeObject();

            let v_posted = (thisButtonIs == "saveDraft") ? 0 : 1;
            var v_user_id = user_id;
            var v_account_id = $("select[name=akun]").val();
            var v_date = $("input[name=tanggal]").val();
            var v_novoucher = $("input[name=voucher_number]").val();
            var v_description = $("input[name=deskripsi]").val();
            var v_partner = $("select[name=pelanggan]").val();
            var v_type = $("select[name=type_voucher]").val();
            let v_company_id = $('#companyID').val();
            let v_voucher_id = $('#voucherID').val();

            // set invoice amount
            if ($('.totalamount').length > 1) {
                $('.totalamount').each(function(i, k) {
                    let getId = $(this).attr('id');
                    var getPositionIndex = getId.split('-');
                    var indexForm = getPositionIndex[1];
                    var container = [];

                    if ($('.myakun-' + indexForm).length > 1) {
                        $('.myakun-' + indexForm).each(function(x, y) {
                            var idExpense = data['akunpilih' + indexForm +'[]'][x];
                            var amountExpense = parseCurrency(data['amountakun' + indexForm +'[]'][x]);

                            container[x] = {
                                account_id: idExpense,
                                nominal: amountExpense,
                            }

                        });
                    } else {
                        var idExpense = data['akunpilih' + indexForm +'[]'];
                        var amountExpense = parseCurrency(data['amountakun' + indexForm +'[]']);

                        container[0] = {
                            account_id: idExpense,
                            nominal: amountExpense,
                        }
                    }

                    sendAjaxData[i] = {
                        invoice_id: data['noinvoice[]'][i],
                        amount: parseCurrency(data['amount[]'][i]),
                        final_amount: parseCurrency(data['subamount[]'][i]),
                        additional_accounts: container,
                    }
                });
            } else {
                var indexForm = "0";
                var container = [];

                if ($('.myakun-' + indexForm).length > 1) {
                    $('.myakun-' + indexForm).each(function(x, y) {
                        var idExpense = data['akunpilih' + indexForm +'[]'][x];
                        var amountExpense = parseCurrency(data['amountakun' + indexForm +'[]'][x]);

                        container[x] = {
                            account_id: idExpense,
                            nominal: amountExpense,
                        }
                    });
                } else {
                    var idExpense = data['akunpilih' + indexForm +'[]'];
                    var amountExpense = parseCurrency(data['amountakun' + indexForm +'[]']);

                    container[0] = {
                        account_id: idExpense,
                        nominal: amountExpense,
                    }
                }

                sendAjaxData = [{
                    invoice_id: data['noinvoice[]'],
                    amount: parseCurrency(data['amount[]']),
                    final_amount: parseCurrency(data['subamount[]']),
                    additional_accounts: container,
                }];
            }

            var dataPost = {
                user_id: v_user_id,
                company_id: v_company_id,
                date: v_date,
                asset_account_id_used: v_account_id,
                type: v_type,
                note: v_description,
                number: v_novoucher,
                partner_id: v_partner,
                is_posted: v_posted,
                voucher_id: v_voucher_id,
                data: sendAjaxData,
            };

            if (dataPost.invoice_number == "") {
                myAlert('No Invoice harus di pilih', '#noinvoice');
            } else {
                $.ajax({
                    type: 'POST',
                    url: routeStoreVoucher,
                    data: dataPost,
                    beforeSend: function() {
                        $('#submitForm').prop("disabled", true);
                        $('#submitForm').html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Loading...');
                    },
                    success:function(data) {

                        Swal.fire({
                            type: 'success',
                            title: 'Success',
                            text: 'Voucher has been updated'
                        });

                        setTimeout(function(){ window.location = data.url; }, 2000);

                        $('#submitForm').prop("disabled", false);
                        $('#submitForm').html('Save <i class="mdi mdi-chevron-down"></i>');

                    },
                    error: function (xhr, status, error) {
                        var err = eval("(" + xhr.responseText + ")");
                        Swal.fire({
                            html: '<strong>Oops!</strong> ' + err.message
                        });

                        $('#submitForm').prop("disabled", false);
                        $('#submitForm').html('Save <i class="mdi mdi-chevron-down"></i>');
                    }
                });
            }
        }
    });


});

// when change type voucher
$(document).on('change', '#type_voucher', function(){
    const user_id = $("#userId").val();
    const companyIdDefault = $("#companyID").val();

    let thisVal = $(this).val();

    fetchDataPartner(user_id, thisVal, companyIdDefault);
});

// when change type partner
$(document).on('change', '.partner', function(e){
    resetForm();
    setListInvoice(0);
});

$(document).on('change', '.invoice', function(e){
    let getId = $(this).attr('id');
    var getPositionIndex = getId.split('-');
    var indexForm = getPositionIndex[1];

    let idPartner = $('#pelanggan').val();
    let routeFetchInvoice = $("#routeFetchInvoice").val()
    let user_id = $("#userId").val();
    let type = $("#type_voucher").val();
    let companyID = $("#companyID").val();

    // initial value
    $("#invoicenum-" + indexForm).val($(this).val());

    // set fetch data
    let dataSend = { 'user_id': user_id, 'type': type, 'partner_id': idPartner, 'company_id': companyID}
    fetchDataInvoice(routeFetchInvoice, dataSend, indexForm);

    // show hide config
    if($(this).val() != ""){
        $("#amount-" + indexForm).attr("readonly", false);
        $(".tr-" + indexForm).show('slow');
    } else{
        resetForm();
        setListInvoice(0);
    }
});

$(document).on('change', '.akunchoose', function(e){
    let getId = $(this).attr('id');
    var getPositionIndex = getId.split('-');
    var getParentPosition = getPositionIndex[1];
    var getChildPosition = getPositionIndex[2];

    if($(this).val() != "") {
        $("#amountakun-" + getParentPosition + "-" + getChildPosition).attr("readonly", false);
    } else {
        $("#amountakun-" + getParentPosition + "-" + getChildPosition).attr("readonly", true);
    }

    formatInputCurrencyValue("#amountakun-" + getParentPosition + "-" + getChildPosition, 0);

    calculationAll();
    calculationAmountParent(getId);
});

$(document).on('keyup', '.totalamount', function(){

    let getId = $(this).attr('id');
    var getPositionIndex = getId.split('-');
    var indexForm = getPositionIndex[1];

    calculationAll();
    calculationAmountParent("row-" + indexForm);
});

$(document).on('keyup', '.amount-expense', function(){
    var getId = $(this).attr('id');

    calculationAll();
    calculationAmountParent(getId);
});

// when change company
$(document).on('change', '.select-company', function(){
    let getIdVal = $(this).find(":selected").val();
    // check configuration finance account
    checkFinanceConfigAccountCompany(getIdVal, configCodeAccount)
    // put value selected
    $('#companyID').val(getIdVal);
    //get list type invoice list
    getListTypeInvoice(getIdVal);
    //set account when company set
    getListAccountAsset();
    //do form reset
    resetForm();
});

function formatInputCurrencyValue(nameID, value) {
    if (AutoNumeric.getAutoNumericElement(nameID) === null) {
        return new AutoNumeric(nameID, { currencySymbol :'Rp. ', decimalCharacter: ',', digitGroupSeparator: '.', decimalPlaces: 0}).set(value);
    }

    return AutoNumeric.getAutoNumericElement(nameID, { currencySymbol :'Rp. ', decimalCharacter: ',', digitGroupSeparator: '.', decimalPlaces: 0}).set(value);
}

function fetchDataPartner(userid, type, companyId, partnerId = '') {
    const routeFetchPartner = $("#routeFetchPartner").val();
    const companyIdDefault = $("#companyID").val();
    const user_id = $("#userId").val();

    let dataSend = {
        'user_id': (userid == '') ? user_id : userid,
        'type': (type == '') ? 1 :  type,
        'company_id': (companyId == '') ? companyIdDefault :  companyId
    }

    $.ajax({
        url: routeFetchPartner,
        type: 'POST',
        datatype: "json",
        data: dataSend,
        success: function (response) {
            if (response.status) {
                $("#pelanggan").empty();
                $("#pelanggan").append('<option value="">Select Partner...</option>');
                $.each(response.data,function(key, value) {
                    let selected = (partnerId == value.id) ? 'selected' : '';
                    $("#pelanggan").append("<option value='" + value.id + "'" + selected + ">" + value.text + "</option>");
                });
                $("#pelanggan").select2({ disabled: true });
            }
        },
        error: function(xhr) { // if error occured
            $("#pelanggan").empty();
            $("#pelanggan").append('<option selected="selected" value="">Select Partner...</option>');
        }
    })
}

function fetchDataInvoiceList(routeFetchInvoice, dataSend, counter, invoiceId = '') {
    let dataArray = [];
    $('.invoice').each(function() {
        dataArray.push($(this).val());
    });

    $.ajax({
        url: routeFetchInvoice,
        type: 'POST',
        datatype: "json",
        data: dataSend,
        success: function (response) {
            if (response.status) {
                $('.select2').select2();
                $("#noinvoice-" + counter).empty();
                $("#noinvoice-" + counter).append('<option value="">Select Invoice...</option>');

                $.each(response.data,function(key, value) {
                    if (jQuery.inArray(value.id.toString(),dataArray) == -1) {
                        let selected = (invoiceId == value.id) ? 'selected' : '';
                        $("#noinvoice-" + counter).append("<option value='" + value.id + "'" + selected + ">" + value.text + "</option>");

                        // to set value to hidden textbox when edited
                        if (selected == 'selected') {
                            $('#invoicenum-' + counter).val(invoiceId);

                            // set fetch data invoice data in texbox
                            let routeListVoucherInvoiceEdit = $('#routeListVoucherInvoiceEdit').val();
                            let voucherID = $('#voucherID').val();
                            fetchDataInvoiceEdit(routeListVoucherInvoiceEdit, voucherID, invoiceId, counter);
                        }
                    }
                });
            }
        }
    })
}

function setListInvoice(counter, invoiceId = '', partnerId = '') {
    let idPartner = (partnerId == '') ? $("select[name=pelanggan]").val() : partnerId;
    let routeFetchInvoice = $("#routeFetchInvoice").val()
    let user_id = $("#userId").val();
    let companyIdDefault = $("#companyID").val();
    let type = $("#type_voucher").val();

    let dataSend = { 'user_id': user_id, 'type': type, 'partner_id': idPartner, 'company_id': companyIdDefault}

    fetchDataInvoiceList(routeFetchInvoice, dataSend, counter, invoiceId);
}

function resetForm() {
    $("#transactions_table").empty();

    var newRow = $("<tbody>");
    var cols = "";

    // parent
    cols += '<tr><td style="width: 25%;"><select class="select2 form-control invoice" id="noinvoice-0" ids="0" name="noinvoice[]" style="width: 100%;"></select><input class="form-control" type="hidden" id="invoicenum-0" name="invoicenum[]" value="" style="text-align: right;" readonly></td>';
    cols += '<td style="width: 25%;"><input class="form-control" type="text" id="descinvoice-0" name="descinvoice[]" value="" style="text-align: left;" readonly></td>';
    cols += '<td style="width: 20%;"><input class="form-control" type="text" id="amountpaid-0" name="amountpaid[]" value="0" style="text-align: right;" readonly></td>';
    cols += '<td style="width: 20%;"><input class="form-control totalamount totalamount-0" type="text" id="amount-0" name="amount[]" value="0" style="text-align: right;" readonly></td>';
    cols += '<td style="width: 10%;"><div class="dropdown d-inline-block float-right"> <button type="button" id="btn-add-0" class="btn btn-sm btn-gradient-primary waves-effect waves-light my-button-add" title="klik untuk tambah" onclick="addRow()"><i class="fas fa-plus"></i></button> &nbsp; <button type="button" id="btn-del-0" class="btn btn-sm btn-gradient-danger waves-effect waves-light my-button-delete" onclick="delRow(this)" title="klik untuk hapus"><i class="far fa-trash-alt ml-1"></i></button></div></td></tr>';

    // child
    cols += '<tr style="background-color: #f0fff9; display: none;" class="tr-0">';
    cols += '<td colspan="5" style="padding: 0px; width: 100%;"><table class="table table-bordered mb-0 table-centered"><tbody id="additional-expense-0" style="background-color: #f0fff9;">';
    cols += '<tr id="row-0-0" class="additional">';
    cols += '<td style="width: 25%"></td>';
    cols += '<td style="width: 25%"><div class="dropdown d-inline-block float-right"><button type="button" onclick="delRowAdditional(this)" id="btn-del-0-0" class="btn btn-sm btn-outline-purple my-button-delete-0"><i class="fas fa-minus"></i></button><button type="button" id="btn-add-0-0" class="btn btn-sm btn-outline-pink my-button-add-0" onclick="addRowAdditional(this)"><i class="fas fa-plus"></i></button></div></td>';
    cols += '<td style="width: 20%"><select class="select2 form-control akunchoose myakun-0" style="width: 100% !important;" id="akunpilih-0-0" ids="0" name="akunpilih0[]"><option selected="selected" value="" style="width: 100%;">Choose Account...</option></select></td>';
    cols += '<td style="width: 20%"><input class="form-control amount-expense amountexpense-0" type="text" id="amountakun-0-0" name="amountakun0[]" value="0" style="text-align: right;" readonly></td>';
    cols += '<td style="width: 10%"><div id="info-purchase"></div></td>';
    cols += '</tr></tbody></table></td></tr>';

    // amount
    cols += '<tr style="background-color: #ffe0e0; display: none;" class="tr-0">';
    cols += '<td style="width: 25%;"></td>';
    cols += '<td style="width: 25%;"></td>';
    cols += '<td style="width: 20%; text-align: right;"><b>Total</b></td>';
    cols += '<td style="width: 20%;"><input class="form-control subtotalamount" type="text" id="subamount0" name="subamount[]" value="0" style="text-align: right;" readonly></td>';
    cols += '<td style="width: 10%;"></td></tr>';

    // do append
    newRow.append(cols).hide().show('slow');
    $("#transactions_table").append(newRow);

    // set listbox
    getListExpense("0-0");

    // config button
    myButton(0);
    myButtonAdditional(0);

    // do button
    calculationAll();
    calculationAmountParent("reset-0");
}

function addRow() {

    var newRow = $("<tbody>");
    var cols = "";

    //parent
    cols += '<tr><td style="width: 25%;"><select class="select2 form-control invoice" style="width: 100%;" id="noinvoice-'+counter+'" ids="'+counter+'" name="noinvoice[]"></select><input class="form-control" type="hidden" id="invoicenum-'+counter+'" name="invoicenum[]" value="" style="text-align: right;" readonly></td>';
    cols += '<td style="width: 25%;"><input class="form-control" type="text" id="descinvoice-'+counter+'" name="descinvoice[]" value="" style="text-align: left;" readonly></td>';
    cols += '<td style="width: 20%;"><input class="form-control" type="text" id="amountpaid-'+counter+'" name="amountpaid[]" value="0" style="text-align: right;" readonly></td>';
    cols += '<td style="width: 20%;"><input class="form-control totalamount totalamount-'+counter+'" type="text" id="amount-'+counter+'" name="amount[]" value="0" style="text-align: right;" readonly></td>';
    cols += '<td style="width: 10%;"><div class="dropdown d-inline-block float-right"><button type="button" id="btn-add-'+counter+'" class="btn btn-sm btn-gradient-primary waves-effect waves-light my-button-add" title="klik untuk tambah" onclick="addRow()"><i class="fas fa-plus"></i></button> &nbsp; <button type="button" id="btn-del-'+counter+'" class="btn btn-sm btn-gradient-danger waves-effect waves-light my-button-delete" onclick="delRow(this)" title="klik untuk hapus"><i class="far fa-trash-alt ml-1"></i></button></div></td></tr>';

    //child
    cols += '<tr style="background-color: #f0fff9; display: none;" class="tr-'+counter+'">';
    cols += '<td colspan="5" style="padding: 0px; width: 100%;"><table class="table table-bordered mb-0 table-centered"><tbody id="additional-expense-'+counter+'" style="background-color: #f0fff9;">';
    cols += '<tr id="row-'+counter+'-'+counterAdditional+'" class="additional">';
    cols += '<td style="width: 25%"></td>';
    cols += '<td style="width: 25%"><div class="dropdown d-inline-block float-right"><button type="button" onclick="delRowAdditional(this)" id="btn-del-'+counter+'-'+counterAdditional+'" class="btn btn-sm btn-outline-purple my-button-delete-'+counter+'"><i class="fas fa-minus"></i></button><button type="button" id="btn-add-'+counter+'-'+counterAdditional+'" class="btn btn-sm btn-outline-pink my-button-add-'+counter+'" onclick="addRowAdditional(this)"><i class="fas fa-plus"></i></button></div></td>';
    cols += '<td style="width: 20%"><select class="select2 form-control akunchoose myakun-'+counter+'" id="akunpilih-'+counter+'-'+counterAdditional+'" name="akunpilih'+counter+'[]" style="width: 100% !important;" ids="'+counterAdditional+'"><option selected="selected" value="">Choose Account...</option></select></td>';
    cols += '<td style="width: 20%"><input class="form-control amount-expense amountexpense-'+counter+'" type="text" id="amountakun-'+counter+'-'+counterAdditional+'" name="amountakun'+counter+'[]" value="0" style="text-align: right;" readonly></td>';
    cols += '<td style="width: 10%"><div id="info-purchase"></div></td>';
    cols += '</tr></tbody></table></td></tr>';

    //amount
    cols += '<tr style="background-color: #ffe0e0; display: none;" class="tr-'+counter+'">';
    cols += '<td style="width: 25%;"></td>';
    cols += '<td style="width: 25%;"></td>';
    cols += '<td style="width: 20%; text-align: right;"><b>Total</b></td>';
    cols += '<td style="width: 20%;"><input class="form-control subtotalamount" type="text" id="subamount'+counter+'" name="subamount[]" value="0" style="text-align: right;" readonly></td>';
    cols += '<td style="width: 10%;"></td></tr>';

    // for append
    newRow.append(cols).hide().show('slow');
    $("#transactions_table").append(newRow);

    // config button view
    myButton(counter);
    myButtonAdditional(counterAdditional);

    // set listbox
    getListExpense(counter + "-" + counterAdditional);
    setListInvoice(counter);

    // do calculation
    calculationAll();
    calculationAmountParent("reset-" + counter);

    counter++;
    counterAdditional++;
}

function delRow(e) {
    $(e).closest("tbody").remove();
    myButton(counter);
    calculationAll();
};

function addRowAdditional(e) {
    var newCountParent = counter - 1;
    var getId = $(e).attr('id');
    var getPositionIndex = getId.split('-');
    var getParentPosition = getPositionIndex[2];

    var newRow = $("<tr id='row-"+getParentPosition+"-"+counterAdditional+"'  class='additional'>");
    var cols = "";

    cols += '<td style="width: 25%"></td>';
    cols += '<td style="width: 25%"><div class="dropdown d-inline-block float-right"><button type="button" onclick="delRowAdditional(this)" id="btn-del-'+getParentPosition+'-'+counterAdditional+'" class="btn btn-sm btn-outline-purple my-button-delete-'+getParentPosition+'"><i class="fas fa-minus"></i></button><button type="button" id="btn-add-'+getParentPosition+'-'+counterAdditional+'" class="ml-1 btn btn-sm btn-outline-pink my-button-add-'+getParentPosition+'" onclick="addRowAdditional(this)"><i class="fas fa-plus"></i></button></div></td>';
    cols += '<td style="width: 20%"><select class="select2 form-control akunchoose myakun-'+getParentPosition+'" id="akunpilih-'+getParentPosition+'-'+counterAdditional+'" name="akunpilih'+getParentPosition+'[]" style="width: 100% !important;" ids="'+counterAdditional+'"><option selected="selected" value="">Choose Account...</option></select></td>';
    cols += '<td style="width: 20%"><input class="form-control amount-expense amountexpense-'+getParentPosition+'" type="text" id="amountakun-'+getParentPosition+'-'+counterAdditional+'" name="amountakun'+getParentPosition+'[]" value="0" style="text-align: right;" readonly></td>';
    cols += '<td style="width: 10%" id="info-purchase"></td>';

    // for append
    newRow.append(cols).hide().show('slow');
    $("#additional-expense-" + getParentPosition).append(newRow);

    // config button
    myButtonAdditional(counterAdditional);

    // set listbox
    getListExpense(getParentPosition + "-" + counterAdditional);

    // do calculation
    calculationAll();
    calculationAmountParent("reset-" + getParentPosition);

    counterAdditional++;
}

function delRowAdditional(e) {
    var getId = $(e).attr('id');
    var getPositionIndex = getId.split('-');
    var getNamePosition = getPositionIndex[2];
    var getNamePosition2 = getPositionIndex[3];

    // do remove
    $("#row-" + getNamePosition + "-" + getNamePosition2).remove();

    // config button
    myButtonAdditional(counterAdditional);

    // do calculation
    calculationAll();
    calculationAmountParent("reset-" + getNamePosition);
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

function myButtonAdditional() {
    var getLengthAdditional = $('.additional').length;

    $('.additional').each(function( index ) {
        var getId = $(this).attr('id');
        var getPositionIndex = getId.split('-');
        var getPositionParent = getPositionIndex[1];
        var jmlAdd = $('.my-button-add-' + getPositionParent).length;
        var jmlDel = $('.my-button-delete-' + getPositionParent).length;

        $(".my-button-add-" + getPositionParent).each(function( index2 ) {
            if (jmlAdd !== (index2 + 1)) {
                $('#' + $(this).attr('id')).hide();
            } else {
                $('#' + $(this).attr('id')).show();
            }
        });

        if (jmlDel <= 1) {
            $('.my-button-delete-' + getPositionParent).hide();
        } else {
            $('.my-button-delete-' + getPositionParent).show();
        }
    });
}

function getListAccountAsset(accountId = '') {
    const selectorSelect2 = $(".select2")
    const selectorAccountList = $("#akun")
    const routeFinanceConfigAccountAsset = $("#routeFinanceConfigAccountAsset").val()
    const financeConfigStatus = $("#financeConfigStatus").val()
    const financeConfigCode = $("#financeConfigCode").val()
    const companyID = $("#companyID").val()

    let dataSend = {
        company_id: companyID,
        configuration_status: financeConfigStatus,
        configuration_code: financeConfigCode
    };

    $.ajax({
        url: routeFinanceConfigAccountAsset,
        type: 'GET',
        data: dataSend,
        beforeSend: () => {
            flushSelectOption(selectorAccountList, "Choose Account ...")
        },
        success: function (response) {
            if (response.status) {
                selectorSelect2.select2()
                let details = response.data.details
                $.each(details, function (key, value) {
                    let account = value.account
                    let selected = (accountId == account.id) ? 'selected' : ''
                    let option = `<option value="${account.id}" ${selected}>
                        ${account.account_code + ' - ' + ucFirst(account.naming)}
                    </option>`
                    selectorAccountList.append(option)
                })
            }
        }
    })
}

function getListExpense(pos, valuesDetailExpenses = '') {
    const selectorSelect2 = $(".select2")
    const selectorAccountName = ".myakun-"
    const selectorAccountNameSelected = "#akunpilih-"
    const routeFinanceConfigAccountAsset = $("#routeFinanceConfigAccountAsset").val()
    const financeConfigStatus = $("#financeConfigStatus").val()
    const financeConfigCodeOtherExpense = $("#financeConfigCodeOtherExpense").val()
    const companyID = $("#companyID").val()

    let position = pos
    let getPositionIndex = position.split('-')
    let getParentPosition = getPositionIndex[0]
    let getChildPosition = getPositionIndex[1]

    let dataSend = {
        company_id: companyID,
        configuration_status: financeConfigStatus,
        configuration_code: financeConfigCodeOtherExpense
    }

    let dataArray = []
    $(selectorAccountName + getParentPosition).each(function() {
        dataArray.push($(this).val())
    })

    $.ajax({
        url: routeFinanceConfigAccountAsset,
        type: 'GET',
        data: dataSend,
        beforeSend: () => {
            flushSelectOption($(selectorAccountNameSelected + getParentPosition + "-" + getChildPosition + ""), "Choose Account ...")
        },
        success: function (response) {
            if (response.status) {
                selectorSelect2.select2()
                let details = response.data.details
                $.each(details, function (key, value) {
                    let account = value.account
                    let selected = (valuesDetailExpenses.account_id == account.id) ? 'selected' : ''
                    if (jQuery.inArray(account.id.toString(), dataArray) == -1) {
                        let option = `<option value="${account.id}" ${selected}>
                            ${account.account_code + ' - ' + ucFirst(account.naming)}
                        </option>`
                        $(selectorAccountNameSelected + getParentPosition + "-" + getChildPosition + "").append(option)

                        if (selected === 'selected') {
                            $("#amountakun-" + getParentPosition + "-" + getChildPosition).attr("readonly", false);

                            formatInputCurrencyValue("#amountakun-" + getParentPosition + "-" + getChildPosition, valuesDetailExpenses.amount);
                        }
                    }
                })
            }
        }
    })
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

function calculationAll() {
    // perubahan total product
    var sumTotalAmount = 0;
    var sumTotalAmountChild = 0;
    var type = $('#type').val()

    // amount parent
    $('.totalamount').each(function() {
        sumTotalAmount += Number(parseCurrency($(this).val()));
    });

    // amount child
    $('.amount-expense').each(function() {
        sumTotalAmountChild += Number(parseCurrency($(this).val()));
    });

    var totalAmountAll = (type == "purchase") ? (sumTotalAmount + sumTotalAmountChild) : (sumTotalAmount - sumTotalAmountChild);

    $("#totalpayment").val(formatCurrency(totalAmountAll));
}

function calculationAmountParent(getId) {
    // perubahan total product
    var sumTotalAmount = 0;
    var sumTotalAmountChild = 0;
    var type = $('#type').val();

    // set ID
    var getPositionIndex = getId.split('-');
    var getParentPosition = getPositionIndex[1];

    // amount parent
    $('.totalamount-' + getParentPosition).each(function() {
        sumTotalAmount += Number(parseCurrency($(this).val()));
    });

    // amount child
    $('.amountexpense-' + getParentPosition).each(function() {
        sumTotalAmountChild += Number(parseCurrency($(this).val()));
    });

    var totalAmountAll = (type == "purchase") ? (sumTotalAmount + sumTotalAmountChild) : (sumTotalAmount - sumTotalAmountChild);
    $("#subamount" + getParentPosition).val(formatCurrency(totalAmountAll));
}

function parseCurrency(MyNum)
{
    let num = accounting.unformat(MyNum, ",");
    return num
}

function fetchDataInvoice(routeFetchInvoice, dataSend, counter) {
    let noInvoice = $('#invoicenum-' + counter).val();

    $.ajax({
        url: routeFetchInvoice,
        type: 'POST',
        datatype: "json",
        data: dataSend,
        success: function (response) {
            if (response.status) {
                var dataChoose = response.data.find(x => x.id == noInvoice);

                if (noInvoice !== "") {
                    var desc = dataChoose.detail.note;
                    var final_ammount = dataChoose.detail.remaining_payment;
                } else {
                    var desc = '';
                    var final_ammount = 0;
                }

                $('#descinvoice-' + counter).val(desc);
                formatInputCurrencyValue('#amount-' + counter, final_ammount);
                formatInputCurrencyValue('#amountpaid-' + counter, final_ammount);

                // do calculation
                calculationAll();
                calculationAmountParent("reset-" + counter);
            }
        },
        error: function(xhr) { // if error occured
            console.log(xhr);
        }
    });
}

function fetchDataInvoiceEdit(routeListVoucherInvoiceEdit, voucherID, invoiceId, counter) {
    let dataSend = { 'invoice_id': invoiceId, 'voucher_id': voucherID }

    $.ajax({
        url: routeListVoucherInvoiceEdit,
        type: 'POST',
        datatype: "json",
        data: dataSend,
        success: function (response) {
            if (response.status) {

                $('#descinvoice-' + counter).val(response.data.description);
                formatInputCurrencyValue('#amount-' + counter, response.data.nominal_amount);
                formatInputCurrencyValue('#amountpaid-' + counter, response.data.remaining_nominal_amount_must_be_paid);

                $("#amount-" + counter).attr("readonly", false);

                // do calculation
                calculationAll();
                calculationAmountParent("reset-" + counter);
            }
        },
        error: function(xhr) { // if error occured
            console.log(xhr);
        }
    });
}

function getListTypeInvoice(companyID = '', voucherType = '') {
    $("#type_voucher").empty();
    $("#type_voucher").append('<option value="">Select Type...</option>');
    if (companyID !== '') {
        $("#type_voucher").append('<option value="1">Receiving</option>');
        $("#type_voucher").append('<option value="2">Payment</option>');
    }

    if (voucherType !== "") {
        $("#type_voucher").val(voucherType);
        $("#type_voucher").select2({ disabled: true });
    }
}

function addRowEdited(dataVoucher) {
    let voucherDetail = dataVoucher.voucher_details;

    $("#transactions_table").empty();

    // loop ur data
    $.each(voucherDetail,function(key, value) {
        var newRow = $("<tbody>");
        var cols = "";
        var voucher_detail_expenses = value.voucher_detail_expenses;

        // parent
        cols += '<tr><td style="width: 25%;"><select class="select2 form-control invoice" id="noinvoice-'+counter+'" ids="'+counter+'" name="noinvoice[]" style="width: 100%;"></select><input class="form-control" type="hidden" id="invoicenum-'+counter+'" name="invoicenum[]" value="" style="text-align: right;" readonly></td>';
        cols += '<td style="width: 25%;"><input class="form-control" type="text" id="descinvoice-'+counter+'" name="descinvoice[]" value="" style="text-align: left;" readonly></td>';
        cols += '<td style="width: 20%;"><input class="form-control" type="text" id="amountpaid-'+counter+'" name="amountpaid[]" value="0" style="text-align: right;" readonly></td>';
        cols += '<td style="width: 20%;"><input class="form-control totalamount totalamount-'+counter+'" type="text" id="amount-'+counter+'" name="amount[]" value="0" style="text-align: right;" readonly></td>';
        cols += '<td style="width: 10%;"><div class="dropdown d-inline-block float-right"> <button type="button" id="btn-add-'+counter+'" class="btn btn-sm btn-gradient-primary waves-effect waves-light my-button-add" title="klik untuk tambah" onclick="addRow()"><i class="fas fa-plus"></i></button> &nbsp; <button type="button" id="btn-del-'+counter+'" class="btn btn-sm btn-gradient-danger waves-effect waves-light my-button-delete" onclick="delRow(this)" title="klik untuk hapus"><i class="far fa-trash-alt ml-1"></i></button></div></td></tr>';

        // child
        cols += '<tr style="background-color: #f0fff9;" class="tr-'+counter+'">';
        cols += '<td colspan="5" style="padding: 0px; width: 100%;">';
        cols += '<table class="table table-bordered mb-'+counter+' table-centered">';
        cols += '<tbody id="additional-expense-'+counter+'" style="background-color: #f0fff9;">';

        // loop ur data
        if (voucher_detail_expenses.length > 0) {
            var colschild = "";
            $.each(voucher_detail_expenses,function(keys, values) {

                // child
                colschild += '<tr id="row-'+counter+'-'+counterAdditional+'" class="additional">';
                colschild += '<td style="width: 25%"></td>';
                colschild += '<td style="width: 25%"><div class="dropdown d-inline-block float-right"><button type="button" onclick="delRowAdditional(this)" id="btn-del-'+counter+'-'+counterAdditional+'" class="btn btn-sm btn-outline-purple my-button-delete-'+counter+'"><i class="fas fa-minus"></i></button> <button type="button" id="btn-add-'+counter+'-'+counterAdditional+'" class="btn btn-sm btn-outline-pink my-button-add-'+counter+'" onclick="addRowAdditional(this)"><i class="fas fa-plus"></i></button></div></td>';
                colschild += '<td style="width: 20%"><select class="select2 form-control akunchoose myakun-'+counter+'" style="width: 100% !important;" id="akunpilih-'+counter+'-'+counterAdditional+'" ids="'+counterAdditional+'" name="akunpilih'+counter+'[]"><option selected="selected" value="" style="width: 100%;">Choose Account...</option></select></td>';
                colschild += '<td style="width: 20%"><input class="form-control amount-expense amountexpense-'+counter+'" type="text" id="amountakun-'+counter+'-'+counterAdditional+'" name="amountakun'+counter+'[]" value="0" style="text-align: right;" readonly></td>';
                colschild += '<td style="width: 10%"><div id="info-purchase"></div></td>';
                colschild += '</tr>';

                // set listbox
                getListExpense(counter + "-" + counterAdditional, values);

                counterAdditional++;
            });

        } else {
            var colschild = "";

            // child
            colschild += '<tr id="row-'+counter+'-'+counterAdditional+'" class="additional">';
            colschild += '<td style="width: 25%"></td>';
            colschild += '<td style="width: 25%"><div class="dropdown d-inline-block float-right"><button type="button" onclick="delRowAdditional(this)" id="btn-del-'+counter+'-'+counterAdditional+'" class="btn btn-sm btn-outline-purple my-button-delete-'+counter+'"><i class="fas fa-minus"></i></button> <button type="button" id="btn-add-'+counter+'-'+counterAdditional+'" class="btn btn-sm btn-outline-pink my-button-add-'+counter+'" onclick="addRowAdditional(this)"><i class="fas fa-plus"></i></button></div></td>';
            colschild += '<td style="width: 20%"><select class="select2 form-control akunchoose myakun-'+counter+'" style="width: 100% !important;" id="akunpilih-'+counter+'-'+counterAdditional+'" ids="'+counterAdditional+'" name="akunpilih'+counter+'[]"><option selected="selected" value="" style="width: 100%;">Choose Account...</option></select></td>';
            colschild += '<td style="width: 20%"><input class="form-control amount-expense amountexpense-'+counter+'" type="text" id="amountakun-'+counter+'-'+counterAdditional+'" name="amountakun'+counter+'[]" value="0" style="text-align: right;" readonly></td>';
            colschild += '<td style="width: 10%"><div id="info-purchase"></div></td>';
            colschild += '</tr>';

            // set listbox
            getListExpense(counter + "-" + counterAdditional, '');

            counterAdditional++;
        }

        cols += colschild;
        cols += '</tbody></table></td></tr>';

        // amount
        cols += '<tr style="background-color: #ffe0e0;" class="tr-'+counter+'">';
        cols += '<td style="width: 25%;"></td>';
        cols += '<td style="width: 25%;"></td>';
        cols += '<td style="width: 20%; text-align: right;"><b>Total</b></td>';
        cols += '<td style="width: 20%;"><input class="form-control subtotalamount" type="text" id="subamount'+counter+'" name="subamount[]" value="0" style="text-align: right;" readonly></td>';
        cols += '<td style="width: 10%;"></td></tr>';

        // do append
        newRow.append(cols).hide().show('slow');
        $("#transactions_table").append(newRow);

        // config button
        myButton();
        // config button
        myButtonAdditional();

        // set listbox with partner
        setListInvoice(counter, value.invoice_id, dataVoucher.partner_id);

        counter++;

    });
}
