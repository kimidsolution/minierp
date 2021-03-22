$(function () {
    const selectorDateRange = $("#reportrange")
    let start = moment()
    let end = moment()

    selectorDateRange.daterangepicker({
        startDate: start,
        endDate: end,
        ranges: {
            'Today': [moment(), moment()],
            'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
            'Last 7 Days': [moment().subtract(6, 'days'), moment()],
            'Last 30 Days': [moment().subtract(29, 'days'), moment()],
            'This Month': [moment().startOf('month'), moment().endOf('month')],
            'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
            'Next Month': [moment().add(1, 'month').startOf('month'), moment().add(1, 'month').endOf('month')],
            'This Year': [moment().startOf('year'), moment().endOf('year')],
            'Last Year': [moment().subtract(1, 'year').startOf('year'), moment().subtract(1, 'year').endOf('year')],
            'Next Year': [moment().add(1, 'year').startOf('year'), moment().add(1, 'year').endOf('year')],
        },
        locale: {
            format: 'DD-MM-YYYY',
            separator: ' to '
        }
    })

    selectorDateRange.on('apply.daterangepicker', function(ev, picker) {
        $(this).val(picker.startDate.format('DD-MM-YYYY') + ' to ' + picker.endDate.format('DD-MM-YYYY'))
    })

    selectorDateRange.on('cancel.daterangepicker', function(ev, picker) {
        $(this).val('')
    })
})
