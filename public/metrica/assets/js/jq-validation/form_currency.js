const token = $('meta[name="csrf-token"]').attr('content')
const selectorIdVal = $("#idCurrency").val()
const routeCheckIsoCode = $("#routeCheckIsoCode").val()
/*
 *  Document   : form_currency.js
 *  Description: Validation form for master data currency
 */

// More examples you can check out https://github.com/VinceG/twitter-bootstrap-wizard
// Docs you can check out https://jqueryvalidation.org/documentation/
class pageFormsCreate {
    static initValidationCreate() {
        let formValidationCreate = jQuery('#jq-validation-form-create');

        jQuery.validator.addMethod("uniqueIsoCode", function (value, element, params) {
            let request = {}
            let checkValue = {}
            let thisValue = $(params[0]).val()
            if (selectorIdVal !== undefined) {
                if (thisValue !== "" && selectorIdVal !== "") {
                    request = { 'iso_code': thisValue, 'except_id': selectorIdVal }
                }
            } else {
                if (thisValue !== "") {
                    request = { 'iso_code': thisValue }
                }
            }
            if (thisValue !== "") {
                checkValue = checkUniqueIsoCode(request)
                if (checkValue.status !== undefined) return checkValue.status === true
            }
            return true
        }, '{1} has been registered.')

        formValidationCreate.on('keyup keypress', (e) => {
            let code = e.keyCode || e.which;
            if (code === 13) {
                e.preventDefault()
                return false
            }
        })

        formValidationCreate.validate({
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
            rules: {
                'name': {
                    required: true,
                    minlength: 3
                },
                'code': {
                    required: true,
                    minlength: 3,
                },
                'iso_code': {
                    required: true,
                    minlength: 3,
                    maxlength: 3,
                    uniqueIsoCode: ["#isoCode", "ISO Code"]
                },
                'symbol': {
                    required: true,
                }
            }
        });

        function checkUniqueIsoCode(request) {
            let checkCode = function () {
                let result = null;
                $.ajax({
                    'async': false,
                    'type': "POST",
                    'global': false,
                    'dataType': 'json',
                    'url': routeCheckIsoCode,
                    'data': request,
                    'success': function (data) {
                        result = data
                    }
                })
                return result
            }()
            return checkCode
        }
    }

    /*
     * Init functionality
     *
     */
    static init() {
        this.initValidationCreate();
    }
}

// Initialize when page loads
jQuery(() => {
    pageFormsCreate.init();
});
