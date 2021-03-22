class pageFormsCreate {
    static initValidationCreate() {
        let formValidationCreate = jQuery('#jq-validation-form-create');

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
                'config_code': {
                    required: true
                },
                'config_status': {
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

// Initialize when page loads
jQuery(() => {
    pageFormsCreate.init();
});
