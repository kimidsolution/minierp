/*
 *  Document   : form_partner.js
 *  Author     : yosepnurawan
 *  Description: Validation form for master data partner
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
                'company_id': {
                    required: true
                },
                'name': {
                    required: true,
                    minlength: 3
                },
                'email': {
                    required: true,
                    minlength: 3,
                    email: true
                },
                'country': {
                    required: true,
                    minlength: 2
                },
                'city': {
                    required: true,
                    minlength: 2
                },
                'pic_name': {
                    minlength: 3
                },
                'pic_email': {
                    minlength: 3,
                    email: true
                },
                'address': {
                    required: true,
                    minlength: 3
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
