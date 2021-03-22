class pageFormsCreate {
    static initValidationCreate() {
        let formValidationCreate = jQuery('#jq-validation-form-create');

        jQuery.validator.addMethod("uniqueRefrenceNumber", function (value, element, params) {
            let request = {}
            let checkValue = {}
            let thisValue = $(params[0]).val()
            if (selectorIdVal !== undefined) {
                if (thisValue !== "" && companyIdValue !== "" && selectorIdVal !== "") {
                    request = { 'company_id': companyIdValue, 'code': thisValue, 'except_id': selectorIdVal }
                }
            } else {
                if (thisValue !== "" && companyIdValue !== "") {
                    request = { 'company_id': companyIdValue, 'code': thisValue }
                }
            }
            if (thisValue !== "" && selectoreModelTypeValue === modelTypeOther) {
                checkValue = checkUniqueRefNumber(request)
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
                'company_id': {
                    required: true
                },
                'model_type': {
                    required: true
                },
                'reference_number': {
                    minlength: 3,
                    uniqueRefrenceNumber: ["#refrenceNumber", "Reference Number"]
                },
                'transaction_date': {
                    required: true
                },
                'description': {
                    required: true,
                    minlength: 3
                }
            }
        });

        function checkUniqueRefNumber(request) {
            let checkCode = function () {
                let result = null;
                $.ajax({
                    'async': false,
                    'type': "POST",
                    'global': false,
                    'dataType': 'json',
                    'url': routeCheckRefNumber,
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
