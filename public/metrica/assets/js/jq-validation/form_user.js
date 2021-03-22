/*
 *  Document   : form_user.js
 *  Author     : yosepnurawan
 *  Description: Validation form for master data user
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
                    required: true,
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
                'password': {
                    required: true,
                    minlength: 8
                },
                'role': {
                    required: true
                }
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

class pageFormsUpdate {
    static initValidationUpdate() {
        let formValidationUpdate = jQuery('#jq-validation-form-update');

        formValidationUpdate.validate({
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
                    required: true,
                },
                'name': {
                    required: true,
                    minlength: 3
                },
                'email': {
                    required: true,
                    minlength: 3,
                    email: true
                }
            }
        });
    }

    /*
     * Init functionality
     *
     */
    static init() {
        this.initValidationUpdate();
    }
}

// Initialize when page loads
jQuery(() => {
    pageFormsCreate.init();
    pageFormsUpdate.init();
});
