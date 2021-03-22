const configCodeAccount = $("#configCodeAccount").val();
const isAdmin = $("#isAdmin").val()
var counter = 0;

$(function () {
    // all constant
    const companyIdDefault = $("#companyID").val();
    const adminStatus = $("#adminStatus").val();
    const routeListProductCategory = $("#routeListProductCategory").val();
    const routeStoreProduct = $("#routeStoreProduct").val();
    const routeStoreInvoiceReceivable = $("#routeStoreInvoiceReceivable").val();
    const invoiceJson = JSON.parse($("#invoiceJson").val());
    const taxJson = JSON.parse($("#taxJson").val());

    // set fetch data category list
    let dataSend = { 'company_id': companyIdDefault}
    fetchDataProductCategory(routeListProductCategory, dataSend);

    // get list partner customer & both
    getPartner(companyIdDefault, invoiceJson.partner_id);

    // get list account asset
    getAccountAsset(companyIdDefault, invoiceJson.downpayment_account_id);

    //get data when edit to form edit
    addRowEdited(invoiceJson, taxJson);

    // format currency
    formatInputCurrencyValue('#price', 0);

    // cek admin status
    if (adminStatus == true) {
        $("#company_id").select2({ disabled: true });
    }

    // check configuration finance account
    if (!isAdmin) checkFinanceConfigAccountCompany(companyIdDefault, configCodeAccount)

    // date format set
    $("#tanggal").datepicker({
        autoclose:true,
        todayHighlight:true,
        format:'dd-mm-yyyy'
    }).on('changeDate', function (selected) {
        var minDate = new Date(selected.date.valueOf());
        $('#duedate').datepicker('setStartDate', minDate);
    });

    $("#duedate").datepicker({
        autoclose:true,
        todayHighlight:true,
        format:'dd-mm-yyyy'
    })
    .on('changeDate', function (selected) {
        var maxDate = new Date(selected.date.valueOf());
        $('#tanggal').datepicker('setEndDate', maxDate);
    });

    // only number in form
    $(".only-number").keypress(function (e) {
        if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
            return false;
        }
    });

    // show modal
    $(".btn-modal").on('click', function (e) {
        e.preventDefault();
        $('.clear-input').val('');

        $('#productModalInput').modal({
            show: true,
            backdrop: 'static',
            keyboard: false
        });
    });

    // modal validate
    $('#productModal').validate({
        rules: {
            'company_id': {
                required: true,
            },
            'product_name': {
                required: true,
                minlength: 3
            },
            'product_category': {
                required: true
            },
            'type': {
                required: true
            },
            'sku': {
                required: true,
                minlength: 3
            },
            'price': {
                required: true
            }
        },
        messages: {
            'company_id': {
                required: 'Company Name is required',
            },
            'product_name': {
                required: 'Product Name is required',
                minlength: 'It should contain minimum 3 characters'
            },
            'product_category': {
                required: 'Product Category is required',
            },
            'type': {
                required: 'Type is required',
            },
            'sku': {
                required: 'SKU Name is required',
                minlength: 'It should contain minimum 3 characters'
            },
            'price': {
                required: 'Price is required'
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
        },
    });

    // submit modal
    $('#productModal').on('submit', function (e) {
        e.preventDefault();

        if ($("#productModal").valid()) {

            $('#submitModal').prop("disabled", true);
            $('#submitModal').html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Loading...');

            let route = routeStoreProduct;
            let userId = $("#userId").val();
            let product_name = $("#product_name").val();
            let companyId = $("#companyID").val();
            let product_category = $("#product_category").val();
            let sku = $("#sku").val();
            let price = $("#price").val();
            let type = $('input[name="type"]:checked').val();;

            let dataPost = {
                user_id: userId,
                product_name: product_name,
                company_id: companyId,
                product_category: product_category,
                sku: sku,
                price: price,
                type: type
            };

            storeProductAction(dataPost, route);
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

    // setup ajax
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // form validate
    $('#myForm').validate({
        rules: {
            'company_id': {
                required: true
            },
            'pelanggan': {
                required: true
            },
            'account_id_asset': {
                required: true
            },
            'noref': {
                required: true
            },
            'tanggal': {
                required: true
            },
            'duedate': {
                required: true
            },
            'duedate': {
                required: true
            },
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

    // form submit
    $(".btn-submit").click(function(e){
        e.preventDefault();
        if ($("#myForm").valid()) {

            let thisButtonIs = $(this).attr('id');

            let sendAjaxData = [];
            let form = $('#myForm');
            let data = form.serializeObject();

            let v_invoice_id = invoiceJson.id;
            let v_posted = (thisButtonIs == "saveDraft") ? 0 : 1;
            let v_user_id = $('#userId').val();
            let v_company_id = $('#companyID').val();
            let v_partner_id = $("select[name=pelanggan]").val();
            let v_date = $("input[name=tanggal]").val();
            let v_duedate = $("input[name=duedate]").val();
            let v_description = $("input[name=deskripsi]").val();
            let v_invoice_number = $("input[name=noref]").val();
            let v_account_id_asset = $('#account_id_asset').val();

            let v_nominal_discount = parseCurrency($("input[name=diskonharga]").val());
            let v_nominal_ppn = parseCurrency($("input[name=hargapajak]").val());
            let v_nominal_sub_total_amount = parseCurrency($("input[name=subtotal]").val());
            let v_nominal_downpayment = parseCurrency($("input[name=uangmuka]").val());
            let v_nominal_remaining = parseCurrency($("input[name=sisanominal]").val());

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

            let dataPost = {
                invoice_id: v_invoice_id,
                user_id: v_user_id,
                company_id: v_company_id,
                partner_id: v_partner_id,
                account_id_asset: v_account_id_asset,
                date: v_date,
                due_date: v_duedate,
                description: v_description,
                invoice_number: v_invoice_number,
                nominal_discount: v_nominal_discount,
                nominal_vat: v_nominal_ppn,
                nominal_down_payment: v_nominal_downpayment,
                nominal_sub_total_amount: v_nominal_sub_total_amount,
                nominal_remaining: v_nominal_remaining,
                is_posted: v_posted,
                products: sendAjaxData
            };

            if (dataPost.nominal_sub_total_amount == "0") {
                myAlert('The remaining nominal cannot be zero', '#subtotal');
            } else {
                $.ajax({
                    type: 'POST',
                    url: routeStoreInvoiceReceivable,
                    data: dataPost,
                    beforeSend: function() {
                        $('#submitForm').prop("disabled", true);
                        $('#submitForm').html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Loading...');
                    },
                    success:function(data){

                        Swal.fire({
                            type: 'success',
                            title: 'Success',
                            text: 'Invoice has been updated'
                        });

                        setTimeout(function(){
                            window.location = data.url;

                            $('#submitForm').prop("disabled", false);
                            $('#submitForm').html('Save <i class="mdi mdi-chevron-down"></i>');

                        }, 2000);
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

// when change company
$(document).on('change', '.select-company', function(){
    const routeListProductCategory = $("#routeListProductCategory").val();
    let getIdVal = $(this).find(":selected").val();

    // check configuration finance account
    checkFinanceConfigAccountCompany(getIdVal, configCodeAccount)
    // put value selected
    $('#companyID').val(getIdVal);
    // get list partner customer & both
    getPartner(getIdVal);
    // get list Account Asset
    getAccountAsset(getIdVal);
    // get detail info company
    getCompanyDetail(getIdVal);
    // set fetch data category list
    let dataSend = { 'company_id': getIdVal}
    fetchDataProductCategory(routeListProductCategory, dataSend);
    //do form reset
    resetForm();
});

// when change product
$(document).on('change', '.getproduct', function(){
    const routeListProduct = $("#routeListProduct").val();

    let getIdVal = $(this).find(":selected").val();

    let getId = $(this).attr('id');
    let getPositionIndex = getId.split('-');
    let indexForm = getPositionIndex[1];

    let companyId = $("#companyID").val();
    let dataPost = { company_id: companyId };

    $.ajax({
        url: routeListProduct,
        type: "POST",
        data: dataPost,
        success: function(response) {
            if (response.status) {
                jQuery.map(response.data, function(obj) {
                    if(obj.id == getIdVal) {
                        var qtyexist = $('#qty-' + indexForm).val();

                        let hargaBarang = obj.harga;
                        let totalHarga = parseInt(hargaBarang) * parseInt(qtyexist);

                        formatInputCurrencyValue("#harga-" + indexForm, hargaBarang);
                        $("#total-" + indexForm).val(formatCurrency(totalHarga));
                        $("#type-" + indexForm).val(obj.type);

                        let badgeTypeProduct = (obj.type == "1") ? '<span class="badge badge-soft-secondary">Goods</span>' : '<span class="badge badge-soft-pink">Service</span>';

                        $("#typeproduct-" + indexForm).html(badgeTypeProduct);
                    }
                });

                calculationAll();
            }
        },
        error: function(xhr) { // if error occured
            console.log(xhr);
        }
    });
});

// when change price product
$(document).on('keyup', '.change-price', function(){
    var getVal = $(this).val();

    let getId = $(this).attr('id');
    var getPositionIndex = getId.split('-');
    var indexForm = getPositionIndex[1];

    var qty = $("#qty-" + indexForm).val();

    var numTotal = 0;

    if (getVal.length > 0) {
        numTotal = parseInt(parseCurrency(getVal)) * parseInt(qty);
    }

    $("#total-" + indexForm).val(formatCurrency(numTotal));

    calculationAll();
});

// when change value discount
$(document).on('keyup', '#diskon-harga', function(){
    let thisVal = $(this).val();

    if (parseCurrency(thisVal) > 0) {
        $('#diskon-persen').val('');
    }

    calculationAll();
});

// when change qty
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

// when change diskon
$(document).on('keyup', '#diskon-persen', function(){
    calcDiscount();
    calculationAll();
});

// when change uang muka
$(document).on('keyup', '#uangmuka', function(){

    calculationAll();
});

// when change payment term
$(document).on('change', '#payment_term', function(){
    let value = $(this).val();
    let startDate = $('#tanggal').val();
    let start = moment(startDate, "DD-MM-YYYY");

    let date = moment(start).add(parseInt(value), 'days');
    let new_date = (startDate == "") ? "" : moment(date, "DD-MM-YYYY").format('DD-MM-YYYY');

    $('#duedate').val(new_date);
});

// when change date
$(document).on('change', '#tanggal', function(){
    let startDate = $(this).val();
    let endDate = $('#duedate').val();

    let start = moment(startDate, "DD-MM-YYYY");
    let end = moment(endDate, "DD-MM-YYYY");
    let days = (endDate == "") ? '' : moment.duration(start.diff(end)).asDays() * (-1);

    $('#payment_term').val(days);
});

// when add row
function addRow() {
    var newRow = $("<tr>");
    var cols = "";

    cols += '<td><select class="select2 form-control getproduct" style="width: 100%;" id="product-'+counter+'" ids="'+counter+'" name="product[]"></select></td>';
    cols += '<td><div id="typeproduct-'+counter+'"><span class="badge badge-soft-secondary">-</span></div></td>';
    cols += '<td><input class="form-control changeqty only-number" type="text" id="qty-'+counter+'" name="qty[]" value="0" style="text-align: right;"></td>';
    cols += '<td><input class="form-control change-price" type="text" id="harga-'+counter+'" name="harga[]" value="0" style="text-align: right;"><input class="form-control gettype" type="hidden" id="type-'+counter+'" name="type[]" value="" style="text-align: right;" readonly></td>';
    cols += '<td><input class="form-control totalproduct" type="text" id="total-'+counter+'" name="total[]" value="0" style="text-align: right;" readonly></td>';
    cols += '<td><div class="dropdown d-inline-block float-right"><button type="button" id="btn-add-'+counter+'" class="btn btn-sm btn-gradient-primary waves-effect waves-light my-button-add" title="klik untuk tambah" onclick="addRow()"><i class="fas fa-plus"></i></button> &nbsp; <button type="button" id="btn-del-'+counter+'" class="btn btn-sm btn-gradient-danger waves-effect waves-light my-button-delete" onclick="delRow(this)" title="klik untuk hapus"><i class="far fa-trash-alt ml-1"></i></button></div></td>';

    newRow.append(cols).hide().show('slow');
    $("#transactions_table").append(newRow);
    myButton(counter);
    getProduct(counter);
    counter++;
}

// when del row
function delRow(e) {
    $(e).closest("tr").remove();
    myButton(counter);
    calculationAll();
    // counter--;
};

// button functionality
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

// to get partner data
function getPartner(companyId, PartnerId = '') {
    const routeListPartnerCustomerBothCompany = $("#routeListPartnerCustomerBothCompany").val();
    let dataPost = { company_id: companyId };

    $.ajax({
        url: routeListPartnerCustomerBothCompany,
        type: "POST",
        data: dataPost,
        beforeSend: function() {
            $("#pelanggan").empty();
        },
        success: function(response) {
            if (response.status) {
                $("#pelanggan").append('<option value="">Select Partner</option>');
                $.each(response.data,function(key, value) {
                    let selected = (PartnerId == value.id) ? 'selected' : '';
                    $("#pelanggan").append("<option value='" + value.id + "'" + selected + ">" + value.text + "</option>");
                });

                $("#pelanggan").select2({ disabled: true });
            }
        },
        error: function(xhr) { // if error occured
            $("#pelanggan").empty();
            $("#pelanggan").append('<option selected="selected" value="">Select Partner</option>');
        }
    });

    $("#pelanggan").select2();
}

// to get account asset data
function getAccountAsset(companyId, AssetId = '') {
    const selectorAccountList = $("#account_id_asset")
    const routeFinanceConfigAccountDp = $("#routeFinanceConfigAccountDp").val();
    const financeConfigStatus = $("#financeConfigStatus").val();
    const financeConfigCode = $("#financeConfigCode").val();

    let dataPost = {
        company_id: companyId,
        configuration_status: financeConfigStatus,
        configuration_code: financeConfigCode
    };

    $.ajax({
        url: routeFinanceConfigAccountDp,
        type: "GET",
        data: dataPost,
        beforeSend: () => {
            flushSelectOption(selectorAccountList, "Choose Account ...")
        },
        success: function(response) {
            if (response.status) {
                let details = response.data.details
                $.each(details, function (key, value) {
                    let account = value.account
                    let selected = (AssetId == account.id) ? 'selected' : ''
                    let option = `<option value="${account.id}" ${selected}>
                        ${account.account_code + ' - ' + ucFirst(account.naming)}
                    </option>`
                    selectorAccountList.append(option)
                });
            }
        },
        error: function(xhr) { // if error occured
            flushSelectOption(selectorAccountList, "Choose Account ...")
        }
    });

    selectorAccountList.select2()
}

// to get company Detail
function getCompanyDetail(companyId) {
    const routeCompanyDetail = $("#routeCompanyDetail").val();
    let dataPost = { company_id: companyId };

    $.ajax({
        url: routeCompanyDetail,
        type: "POST",
        data: dataPost,
        success: function(response) {
            if (response.status) {
                let status_vat = (response.data.vat_enabled == true) ? 1 : 0;
                $('#vatEnabledCompany').val(status_vat);
            }
        },
        error: function(xhr) { // if error occured
            console.log(xhr);
        }
    });
}

// to get product data
function getProduct(counter, idProduct = ''){

    const routeListProduct = $("#routeListProduct").val();
    let companyId = $("#companyID").val();
    let dataPost = { company_id: companyId };

    $.ajax({
        url: routeListProduct,
        type: "POST",
        data: dataPost,
        beforeSend: function() {
            $("#product-" + counter).empty();
        },
        success: function(response) {
            if (response.status) {
                // to append in list box
                $("#product-" + counter).append('<option value="">Select Product / Service...</option>');
                $.each(response.data,function(key, value) {
                    let selected = (idProduct == value.id) ? 'selected' : '';
                    $("#product-" + counter).append("<option value='" + value.id + "'" + selected + ">" + value.text + "</option>");

                    if (selected == 'selected') {
                        // to get type of product when edited form
                        $("#type-" + counter).attr('value', value.type);
                        let badgeTypeProduct = (value.type == "1") ? '<span class="badge badge-soft-secondary">Goods</span>' : '<span class="badge badge-soft-pink">Service</span>';

                        $("#typeproduct-" + counter).html(badgeTypeProduct);
                    }
                });
            }
        },
        error: function(xhr) { // if error occured
            $("#product-" + counter).empty();
            $("#product-" + counter).append('<option selected="selected" value="">Select Product / Service...</option>');
        }
    });

    $("#product-" + counter).select2();
}

// to get product category  modal
function fetchDataProductCategory(routeFetch, dataPost) {
    $.ajax({
        url: routeFetch,
        type: 'POST',
        data: dataPost,
        success: function (response) {
            if (response.status) {
                $("#product_category").empty();
                $("#product_category").append('<option selected="selected" value="">Select Product Category...</option>');
                $.each(response.data,function(key, value) {
                    $("#product_category").append('<option value=' + value.id + '>' + value.text + '</option>');
                });
            }
        },
        error: function(xhr) { // if error occured
            $("#product_category").empty();
            $("#product_category").append('<option selected="selected" value="">Select Product Category...</option>');
        }
    })
}

// to save product add in modal
function storeProductAction(dataPost, route) {
    $.ajax({
        url: route,
        type: "POST",
        data: dataPost,
        success: function(response) {
            alertNotify("Success, added Product");

            // add value in listbox
            $('.getproduct').each(function() {
                let getId = $(this).attr('id');
                $('#' + getId).append('<option value=' + response.data.id + '>' + response.data.product_name + '</option>');
            });

            $('#productModalInput').modal('hide');

            $('#submitModal').prop("disabled", false);
            $('#submitModal').html('Save');
        },
        error: function (xhr, status, error) {
            var err = eval("(" + xhr.responseText + ")");

            $('#submitModal').prop("disabled", false);
            $('#submitModal').html('Save');

            Swal.fire({
                html: '<strong>Oops!</strong> ' + err.message
            });
        }
    });
}

// to set format currency form
function formatInputCurrencyValue(nameID, value) {
    if (AutoNumeric.getAutoNumericElement(nameID) === null) {
        return new AutoNumeric(nameID, { currencySymbol :'Rp. ', decimalCharacter: ',', digitGroupSeparator: '.', decimalPlaces: 0}).set(value);
    }

    return AutoNumeric.getAutoNumericElement(nameID, { currencySymbol :'Rp. ', decimalCharacter: ',', digitGroupSeparator: '.', decimalPlaces: 0}).set(value);
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

// for all calculation jquery
function calculationAll() {
    // perubahan total product
    let sumTotalProduct = 0;
    let persendiskon = $('#diskon-persen').val();

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
    if (persendiskon > 0) {
        calcDiscount();
    }

    //perubahan untuk pajak
    calcPajak();

    //perubahan untuk pph23
    // calcPPH();

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
    let persendiskon = $('#diskon-persen').val();
    let totalBarangJasa = $("#total-totalbarangjasa").val();
    let valPersen = 0;
    let totalPersen = 0;

    if (persendiskon.length > 0) {
        valPersen = parseInt($('#diskon-persen').val()) / 100;
        totalPersen = parseInt(parseCurrency(totalBarangJasa)) * valPersen;
    }

    formatInputCurrencyValue('#diskon-harga', totalPersen);
}

function calcPajak() {
    let totalBarangJasa = $("#total-totalbarangjasa").val();
    let diskonHarga = $("#diskon-harga").val();
    let vatEnabled = $("#vatEnabledCompany").val();
    let pajakPPN = 10;

    if (vatEnabled == "1") {
        var valPersen = pajakPPN / 100;
        var totalPpn = (parseInt(parseCurrency(totalBarangJasa)) - parseInt(parseCurrency(diskonHarga))) * valPersen;

        $("#harga-pajak").val(formatCurrency(totalPpn));
    } else {
        $("#harga-pajak").val(formatCurrency(0));
    }
}

function calcPPH() {
    let sumTotalService = 0;

    // add value in based on type service = 2
    $('.gettype').each(function() {
        let getId = $(this).attr('id');
        let getPositionIndex = getId.split('-');
        let indexForm = getPositionIndex[1];

        let getTotalProduct = $("#total-" + indexForm).val();
        let getTypeProduct = $("#type-" + indexForm).val();

        if (getTypeProduct == "2") {
            sumTotalService += parseInt(parseCurrency(getTotalProduct));
        }
    });

    let persenPPH = 2 / 100;
    let totalPPH = sumTotalService * persenPPH;

    $("#pph").val(formatCurrency(totalPPH));
}

function calcSubTotal() {
    var totalBarangJasa = $("#total-totalbarangjasa").val();
    var diskonHarga = $("#diskon-harga").val();
    var hargaPajak = $("#harga-pajak").val();
    var hargaPPh = $("#pph").val();

    var subtotal = (parseInt(parseCurrency(totalBarangJasa)) - parseInt(parseCurrency(diskonHarga))) + parseInt(parseCurrency(hargaPajak)) - parseInt(parseCurrency(hargaPPh));

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

// info alert function
function myAlert(Msg, focus) {
    Swal.fire({
        icon: 'error',
        title: 'Oops...',
        text: Msg,
    })

    $(focus).focus();

    return false;
}

function alertNotify(Msg) {
    Swal.fire({
        icon: 'info',
        title: 'Info',
        text: Msg,
    })

    return true;
}

// to reset form functionality
function resetForm()
{
    $("#transactions_table").empty();

    var newRow = $("<tr>");
    var cols = "";

    cols += '<td><select class="select2 form-control getproduct" style="width: 100%;" id="product-0" ids="0" name="product[]"></select></td>';
    cols += '<td><div id="typeproduct-0"><span class="badge badge-soft-secondary">-</span></div></td>';
    cols += '<td><input class="form-control changeqty only-number" type="text" id="qty-0" name="qty[]" value="0" style="text-align: right;"></td>';
    cols += '<td><input class="form-control change-price" type="text" id="harga-0" name="harga[]" value="0" style="text-align: right;"><input class="form-control gettype" type="hidden" id="type-0" name="type[]" value="" style="text-align: right;" readonly></td>';
    cols += '<td><input class="form-control totalproduct" type="text" id="total-0" name="total[]" value="0" style="text-align: right;" readonly></td>';
    cols += '<td><div class="dropdown d-inline-block float-right"><button type="button" id="btn-add-0" class="btn btn-sm btn-gradient-primary waves-effect waves-light my-button-add" title="klik untuk tambah" onclick="addRow()"><i class="fas fa-plus"></i></button> &nbsp; <button type="button" id="btn-del-0" class="btn btn-sm btn-gradient-danger waves-effect waves-light my-button-delete" onclick="delRow(this)" title="klik untuk hapus"><i class="far fa-trash-alt ml-1"></i></button></div></td>';

    newRow.append(cols).hide().show('slow');
    $("#transactions_table").append(newRow);
    myButton(0);
    getProduct(0);

    // reset all form
    $('#diskon-persen').val(0);
    formatInputCurrencyValue('#diskon-harga', 0);
    formatInputCurrencyValue('#uangmuka', 0);

    calculationAll();
}

// when add row when edited form
function addRowEdited(dataInvoice, taxJson) {
    let invoiceDetail = dataInvoice.invoice_details;

    // set empty first
    $("#transactions_table").empty();

    // loop ur data
    $.each(invoiceDetail,function(key, value) {
        var newRow = $("<tr>");
        var cols = "";

        cols += '<td><select class="select2 form-control getproduct" style="width: 100%;" id="product-'+counter+'" ids="'+counter+'" name="product[]"></select></td>';
        cols += '<td><div id="typeproduct-'+counter+'"><span class="badge badge-soft-secondary">-</span></div></td>';
        cols += '<td><input class="form-control changeqty only-number" type="text" id="qty-'+counter+'" name="qty[]" value="'+ value.quantity +'" style="text-align: right;"></td>';
        cols += '<td><input class="form-control change-price" type="text" id="harga-'+counter+'" name="harga[]" value="0" style="text-align: right;"><input class="form-control gettype" type="hidden" id="type-'+counter+'" name="type[]" value="" style="text-align: right;"></td>';
        cols += '<td><input class="form-control totalproduct" type="text" id="total-'+counter+'" name="total[]" value="0" style="text-align: right;" readonly></td>';
        cols += '<td><div class="dropdown d-inline-block float-right"><button type="button" id="btn-add-'+counter+'" class="btn btn-sm btn-gradient-primary waves-effect waves-light my-button-add" title="klik untuk tambah" onclick="addRow()"><i class="fas fa-plus"></i></button> &nbsp; <button type="button" id="btn-del-'+counter+'" class="btn btn-sm btn-gradient-danger waves-effect waves-light my-button-delete" onclick="delRow(this)" title="klik untuk hapus"><i class="far fa-trash-alt ml-1"></i></button></div></td>';

        // hide and append to html
        newRow.append(cols).hide().show('slow');
        $("#transactions_table").append(newRow);

        // initializaiton value to price format
        formatInputCurrencyValue('#harga-'+counter+'', value.price);
        formatInputCurrencyValue('#total-'+counter+'', (value.quantity * value.price));

        // button configuration
        myButton(counter);
        // product configuration
        getProduct(counter, value.product_id);

        counter++;
    });

    // set discount
    formatInputCurrencyValue('#diskon-harga', dataInvoice.discount);
    // set down payment
    formatInputCurrencyValue('#uangmuka', dataInvoice.down_payment);

    // set ppn
    var ppn_value = (taxJson.hasOwnProperty('ppn')) ? taxJson.ppn : 0;
    formatInputCurrencyValue('#harga-pajak', ppn_value);

    // count all calculation here
    // perubahan total product
    let sumTotalProduct = 0;
    let persendiskon = $('#diskon-persen').val();

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
    if (persendiskon > 0) {
        calcDiscount();
    }

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
