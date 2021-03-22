/*
 *  Document   : form_auth.js
 *  Author     : devrian
 *  Description: Validation form for auth user
 */

// More examples you can check out https://github.com/VinceG/twitter-bootstrap-wizard
// Docs you can check out https://jqueryvalidation.org/documentation/
class pageFormsLogin {
    static initValidationLogin() {
        let formValidationLogin = jQuery('#jq-validation-form-login');

        formValidationLogin.validate({
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
                'email': {
                    required: true,
                    minlength: 3,
                    email: true
                },
                'password': {
                    required: true,
                    minlength: 8
                },
            }
        });
    }

    /*
     * Init functionality
     *
     */
    static init() {
        this.initValidationLogin();
    }
}

class pageFormsRegister {
    static initValidationRegister() {
        let formValidationRegister = jQuery('#jq-validation-form-register');

        formValidationRegister.validate({
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
                'email': {
                    required: true,
                    minlength: 3,
                    email: true
                },
                'password': {
                    required: true,
                    minlength: 8
                },
                'password_confirmation': {
                    required: true,
                    equalTo: '#password'
                }
            }
        });
    }

    /*
     * Init functionality
     *
     */
    static init() {
        this.initValidationRegister();
    }
}

class pageFormsResetPassword {
    static initValidationResetPassword() {
        let formValidationResetPassword = jQuery('#jq-validation-form-reset-password');

        formValidationResetPassword.validate({
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
                'email': {
                    required: true,
                    minlength: 3,
                    email: true
                },
                'password': {
                    required: true,
                    minlength: 8
                },
                'password_confirmation': {
                    required: true,
                    equalTo: '#password'
                }
            }
        });
    }

    /*
     * Init functionality
     *
     */
    static init() {
        this.initValidationResetPassword();
    }
}

class pageFormsEmailResetPassword {
    static initValidationEmailResetPassword() {
        let formValidationEmailResetPassword = jQuery('#jq-validation-form-email-reset-password');

        formValidationEmailResetPassword.validate({
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
        this.initValidationEmailResetPassword();
    }
}

// Initialize when page loads
jQuery(() => {
    pageFormsLogin.init();
    pageFormsRegister.init();
    pageFormsResetPassword.init();
    pageFormsEmailResetPassword.init();
});
