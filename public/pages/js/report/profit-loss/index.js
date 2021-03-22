$(function() {
    const token = $('meta[name="csrf-token"]').attr('content')
    const routeFetch = $("#routeFetch").val()
    const selectorDate = $(".date-month")
    const isAdmin = $("#isAdmin").val()
    const selectorCompany = $("#companyId")
    const selectorFromPeriod = $("#from-period")
    const selectorToPeriod = $("#to-period")
    const selectorButtonModal = $("#buttonModalFilter")
    const selectorTitleCompany = $("#title-company")
    const selectorLocCompany = $("#loc-company")
    const selectorLoader = $(".section-loader")
    const selectorModal = $("#modal-form-filter")
    const selectorFilterCompany = $("#filter-company")
    const selectorTableBody = $("#profit-loss-table tbody")
    const selectorTitlePeriod = $("#period-report")
    const selectorFormFilter = $("#form-filter-month-period")

    let companyId = !isAdmin ? selectorCompany.val() : null
    let startPeriod = selectorFromPeriod.val()
    let endPeriod = selectorToPeriod.val()

    selectorDate.datepicker({
        autoclose: true,
        autoOpen: false,
        format: "MM-yyyy",
        startView: "months",
        minViewMode: "months"
    })

    selectorLoader.hide()
    if (!isAdmin) selectorModal.modal('show')

    selectorFilterCompany.on("select2:select", (e) => {
        e.preventDefault()
        companyId = e.target.value
        flushSelectorWithCondition(selectorButtonModal, companyId, "disabled")

        selectorTableBody.empty()
        selectorTitlePeriod.text('')
        selectorTitleCompany.text('')
        selectorLocCompany.text('')

        if (companyId !== "") {
            let dataId = $(e.params.data.element).data("id").split('-')
            selectorTitleCompany.text(dataId[0])
            selectorLocCompany.text(dataId[1])
            if (startPeriod !== '' && endPeriod !== '') fetchData(startPeriod, endPeriod)
        }
    })

    selectorFormFilter.on('submit', function(e) {
        e.preventDefault()
        let form = $(this).serializeObject()
        startPeriod = form.fromPeriod
        endPeriod = form.toPeriod
        if (startPeriod !== '' && endPeriod !== '') fetchData(startPeriod, endPeriod)
    })

    function fetchData(startPeriod, endPeriod) {
        let startParse = getDateByMonthYear(startPeriod, '-')
        let endParse = getDateByMonthYear(endPeriod, '-')
        let startMonth = startParse.getMonth() + 1
        let endMonth = endParse.getMonth() + 1
        if (endMonth >= startMonth && startParse.getFullYear() === endParse.getFullYear()) {
            selectorModal.modal('hide')
            selectorTitlePeriod.text('Period: ' + startPeriod + ' to ' + endPeriod)
            getDataFetch(formatDateYmd(startPeriod, false), formatDateYmd(endPeriod, false))
        }
    }

    function getDataFetch(startPeriod, endPeriod) {
        try {
            $.ajax({
                url: routeFetch,
                type: 'GET',
                headers: { 'X-CSRF-TOKEN': token },
                datatype: "html",
                beforeSend: () => {
                    selectorLoader.show()
                    selectorTableBody.empty()
                },
                data: {
                    'company_id': companyId,
                    'start_period': startPeriod,
                    'end_period': endPeriod,
                    'to_json': false
                },
                success: function (response) {
                    selectorLoader.hide()
                    if (response.data.resource_income.length > 0 && response.data.resource_expense.length > 0) {
                        selectorTableBody.append(response.dataHtml)
                        collapseIcon()
                    }
                }
            })

        } catch (error) {
            console.log(error)
        }
    }

    function collapseIcon() {
        $(".collapse").on('show.bs.collapse', function () {
            $(this).prev(".header-title-row").find(".fa").removeClass("dripicons-chevron-right").addClass("dripicons-chevron-down")
        }).on('hide.bs.collapse', function() {
            $(this).prev(".header-title-row").find(".fa").removeClass("dripicons-chevron-down").addClass("dripicons-chevron-right")
        });
    }
})
