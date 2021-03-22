$(document).ready(function(e){
    const companyIdDefault = $("#companyID").val();
    const adminStatus = $("#adminStatus").val();
    const routeListProductCategory = $("#routeListProductCategory").val();
    const routeStoreProductCategory = $("#routeStoreProductCategory").val();

    // set list if user based on admin
    if (adminStatus == '') {
        // set fetch data
        let dataSend = { 'company_id': companyIdDefault}
        fetchDataProductCategory(e, routeListProductCategory, dataSend);

        // enable button first
        $("#add-button").attr("disabled", false)
    } else{
        // disable button first
        $("#add-button").attr("disabled", true)
    }   

    // format currency
    formatInputCurrencyValue('#price', 0);

    // show modal
    $(".btn-modal").on('click', function (e) {
        e.preventDefault();
        $('.clear-input').val('');

        $('#categoryModalInput').modal({ 
            show: true,
            backdrop: 'static',
            keyboard: false
        });
    });

    // modal validate
    $('#categoryModal').validate({
        rules: {
            'category_product_name': {
                required: true,
                minlength: 3
            }
        },
        messages: {                    
            'category_product_name': {
                required: 'Product Category Name is required',
                minlength: 'It should contain minimum 3 characters',
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
        },
    });

    // submit modal
    $('#categoryModal').on('submit', function (e) {        
        e.preventDefault();
        
        if ($("#categoryModal").valid()) { 
            $('#submitModal').prop("disabled", true);
            $('#submitModal').html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Loading...');

            let route = routeStoreProductCategory;

            let userId = $("#userId").val();
            let companyId = $("#companyID").val();
            let product_category_name = $("#category_product_name").val();

            let dataPost = {
                company_id: companyId,
                product_category_name: product_category_name,
                user_id: userId,
            };

            storeProductCategoryAction(dataPost, route);
        }
    });
});

$(document).on('change', '.select-company', function(e){      
    const routeListProductCategory = $("#routeListProductCategory").val();      

    let url_fetch_product_category = routeListProductCategory;
    let valueSelected = $(this).val();

    // set value
    $("#companyID").val(valueSelected)

    // validate if null 
    if (valueSelected == '') {
        $("#add-button"). attr("disabled", true)
    } else {
        $("#add-button"). attr("disabled", false)
    }

    // set fetch data
    let dataSend = { 'company_id': valueSelected}
    fetchDataProductCategory(e, url_fetch_product_category, dataSend);
}); 

function fetchDataProductCategory(e, routeFetch, dataPost) {
    try {
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
    } catch (e) {
        console.log(e)
    }
}

function formatInputCurrencyValue(nameID, value) {
    if (AutoNumeric.getAutoNumericElement(nameID) === null) {
        return new AutoNumeric(nameID, { currencySymbol :'Rp. ', decimalCharacter: ',', digitGroupSeparator: '.', decimalPlaces: 0}).set(value);
    }

    return AutoNumeric.getAutoNumericElement(nameID, { currencySymbol :'Rp. ', decimalCharacter: ',', digitGroupSeparator: '.', decimalPlaces: 0}).set(value);
}

function storeProductCategoryAction(dataPost, route) {
    $.ajax({
        url: route,
        type: "POST",
        data: dataPost,
        success: function(response) {
            alertNotify("Success, added Product Category");

            $('#submitModal').prop("disabled", false);
            $('#submitModal').html('Save');

            $('#categoryModalInput').modal('hide');
            listProductCategory(response.data.id);
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

function listProductCategory(idProductCategory) {    
    const routeListProductCategory = $("#routeListProductCategory").val();
    
    let companyId = $("#companyID").val();    
    let dataPost = { company_id: companyId };

    $.ajax({
        url: routeListProductCategory,
        type: "POST",
        data: dataPost,
        success: function(response) {
            if (response.status) { 
                $("#product_category").empty();
                $("#product_category").append('<option selected="selected" value="">Select Product Category...</option>');

                $.each(response.data,function(key, value) {
                    let selected = (idProductCategory == value.id) ? 'selected' : '';
                    $("#product_category").append('<option value=' + value.id + ' ' + selected + '>' + value.text + '</option>');
                });
            }
        },
        error: function(e) {
            console.log(e)
        }
    });
}

function parseCurrency(MyNum) {
    let num = accounting.unformat(MyNum, ",");
    return num
}

function alertNotify(Msg) {
    Swal.fire({
        icon: 'info',
        title: 'Info',
        text: Msg,
    })

    return true;
}