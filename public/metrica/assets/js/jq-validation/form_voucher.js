/*
 *  Document   : form_voucher.js
 *  Author     : yosepnurawan
 *  Description: Validation form for transaction voucher
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
                'partner_id': {
                    required: true
                },
                'account_id': {
                    required: true,
                },
                'tanggal': {
                    required: true
                },
                'voucher_number': {
                    required: true
                },
                'noinvoice': {
                    required: true
                },
            },
            messages: {
                'partner_id': {
                    required: 'Partner wajib dipilih'
                },
                'account_id': {
                    required: 'Akun wajib dipilih'
                },
                'tanggal': {
                    required: 'Tanggal tidak boleh kosong'
                },
                'voucher_number': {
                    required: 'No Voucher tidak boleh kosong'
                },
                'noinvoice': {
                    required: 'No Invoice wajib dipilih'
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
