/*
 *  Document   : form_product.js
 *  Author     : yosepnurawan
 *  Description: Validation form for master data product
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
