/*
 *  Document   : form_filter_month.js
 *  Author     : devrian
 *  Description: Validation form filter month date picker
 */

// More examples you can check out https://github.com/VinceG/twitter-bootstrap-wizard
// Docs you can check out https://jqueryvalidation.org/documentation/
class pageFormsFilter {
    static initValidationFilter() {
        let formValidationFilter = jQuery('#form-filter-month-period');

        jQuery.validator.addMethod("greaterThan", function(value, element, params) {
            if ($(params[0]).val() != '') {
                let fromDate = getDateByMonthYear($(params[0]).val(), '-')
                let toDate = getDateByMonthYear(value, '-')
                if (!/Invalid|NaN/.test(toDate)) return toDate >= fromDate;
                return isNaN(value) && isNaN($(params[0]).val()) || (Number(value) >= Number($(params[0]).val()));
            };
            return true;
        }, 'Must be greater than {1}.');

        jQuery.validator.addMethod("lessThan", function(value, element, params) {
            if ($(params[0]).val() != '') {
                let fromDate = getDateByMonthYear($(params[0]).val(), '-')
                let toDate = getDateByMonthYear(value, '-')
                if (!/Invalid|NaN/.test(toDate)) return toDate <= fromDate;
                return isNaN(value) && isNaN($(params[0]).val()) || (Number(value) <= Number($(params[0]).val()));
            };
            return true;
        }, 'Must be smaller than {1}.');

        jQuery.validator.addMethod("sameYear", function(value, element, params) {
            if ($(params[0]).val() != '') {
                let start = getDateByMonthYear($(params[0]).val(), '-')
                let end = getDateByMonthYear(value, '-')
                if (!/Invalid|NaN/.test(end)) return start.getFullYear() === end.getFullYear()
                return isNaN(value) && isNaN($(params[0]).val()) || (Number(start.getFullYear()) === Number(end.getFullYear()));
            };
            return true;
        },'Year period must be equal to {1}.');

        formValidationFilter.on('keyup keypress', (e) => {
            let code = e.keyCode || e.which;
            if (code === 13) {
                e.preventDefault()
                return false
            }
        })

        formValidationFilter.validate({
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
                'fromPeriod': {
                    required: true,
                    lessThan: ["#to-period", "to period"],
                    sameYear: ["#to-period", "to period"]
                },
                'toPeriod': {
                    required: true,
                    greaterThan: ["#from-period", "from period"],
                    sameYear: ["#from-period", "from period"]
                }
            }
        });
    }

    /*
     * Init functionality
     *
     */
    static init() {
        this.initValidationFilter();
    }
}

// Initialize when page loads
jQuery(() => {
    pageFormsFilter.init();
});
