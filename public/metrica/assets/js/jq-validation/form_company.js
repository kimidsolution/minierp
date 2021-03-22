/*
 *  Document   : form_company.js
 *  Author     : yosepnurawan
 *  Description: Validation form for master data company
 */

// More examples you can check out https://github.com/VinceG/twitter-bootstrap-wizard
// Docs you can check out https://jqueryvalidation.org/documentation/
class pageFormsCreate {
    static initValidationCreate() {
        let formValidationCreate = jQuery('#jq-validation-form-create');

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
                'company_name': {
                    required: true,
                    minlength: 3
                },
                'address': {
                    required: true,
                    minlength: 3
                },
                'city': {
                    required: true
                },
                'country': {
                    required: true
                },
                'email': {
                    required: true,
                    minlength: 3,
                    email: true
                },
                'phone': {
                    required: true
                },
                'vat_enabled': {
                    required: true
                },
                'pic_id': {
                    required: true
                },
                'pic_name': {
                    required: true,
                    minlength: 3
                },
                'pic_email': {
                    required: true,
                    minlength: 3,
                    email: true
                },
                'pic_phone': {
                    required: true
                },
                'password': {
                    required: true,
                    minlength: 8
                },
                'password_confirm': {
                    required: true,
                    equalTo : "#password",
                    minlength: 8
                },
                'currency_id': {
                    required: true
                },
            }
        });
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
