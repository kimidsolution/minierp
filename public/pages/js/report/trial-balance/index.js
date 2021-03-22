$(function() {
    const token = $('meta[name="csrf-token"]').attr('content')
    const routeFetch = $("#routeFetch").val()
    const selectorDate = $(".date-month")
    const isAdmin = $("#isAdmin").val()
    const selectorButtonModal = $("#buttonModalFilter")
    const selectorTitleCompany = $("#title-company")
    const selectorLocCompany = $("#loc-company")
    const selectorCompany = $("#companyId")
    const selectorFromPeriod = $("#from-period")
    const selectorToPeriod = $("#to-period")
    const selectorLoader = $(".section-loader")
    const selectorModal = $("#modal-form-filter")
    const selectorFilterCompany = $("#filter-company")
    const selectorTableBody = $("#trial-balance-table tbody")
    const selectorTitlePeriod = $("#period-report")
    const selectorFormFilter = $("#form-filter-month-period")
    const selectorTableHeadColOpen = $("#trial-balance-table thead #col-open")
    const selectorTableHeadColHeader = $("#trial-balance-table thead #column-header")

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

    function setHtmlHeaderMutation(dataList) {
        let result = ``
        let rangeMonth = 0
        selectorTableHeadColOpen.nextAll().remove()
        if (dataList.length > 0) rangeMonth += dataList[0].mutations.length

        result = `
            <th id="col-mutation" style="text-align: center; width:20%;" colspan="${rangeMonth * 2}">
                MUTATION TRANSACTION
            </th>
            <th id="col-netmutation" style="width:10%;" rowspan="3">NET MUTATION</th>
            <th id="col-end" style="width:10%;" rowspan="3">ENDING BALANCE</th>
        `

        selectorTableHeadColOpen.after(result)
    }

    function setHtmlHeaderMonthsMutation(dataList) {
        let result = ``
        let resultMutation = ``
        selectorTableHeadColHeader.next().remove()

        if (dataList.length > 0) {
            dataList.forEach((data) => {
                resultMutation = ``
                if (data.mutations.length > 0) {
                    data.mutations.forEach((mutation) => {
                        resultMutation += `<th style="font-size: 0.75rem; width: 100px;" colspan="2">
                            ${mutation.month_name}
                        </th>`
                    })
                }
            })
        }
        result += `<tr class="sticky-header-second" id="column-months">
            ${resultMutation}
        </tr>`

        selectorTableHeadColHeader.after(result)
    }

    function setHtmlHeaderBalanceMutation(dataList) {
        let result = ``
        let resultMutation = ``
        $("#trial-balance-table thead #column-months").next().remove()

        if (dataList.length > 0) {
            dataList.forEach((data) => {
                resultMutation = ``
                let rowMutation = `<th>DEBIT BALANCE <br/>(Rp)</th><th>CREDIT BALANCE <br/>(Rp)</th>`
                if (data.mutations.length > 0) data.mutations.forEach(() => resultMutation += rowMutation)
            })
        }
        result += `<tr class="sticky-header-third" id="column-balance">
            ${resultMutation}
        </tr>`

        $("#trial-balance-table thead #column-months").after(result)
    }

    function setHtmlFillData(dataList) {
        let result = ``
        if (dataList.length > 0) {
            dataList.forEach((data) => {
                let resultMutation = ``
                if (data.mutations.length > 0) {
                    data.mutations.forEach((mutation) => {
                        resultMutation += `
                            <td class="text-right">${checkNegative(formatCurrency(mutation.nominal_mutation_debit))}</td>
                            <td class="text-right">${checkNegative(formatCurrency(mutation.nominal_mutation_credit))}</td>
                        `
                    })
                }
                result += `<tr>
                    <td class="text-center hard_left">${data.account_code}</td>
                    <td class="text-left next_left">
                        <div class="iffyTip hideText2">
                            ${data.account_description}
                        </div>
                    </td>
                    <td class="text-right">${checkNegative(formatCurrency(data.nominal_open_balance))}</td>
                    ${resultMutation}
                    <td class="text-right">${checkNegative(formatCurrency(data.nominal_net_mutation))}</td>
                    <td class="text-right">${checkNegative(formatCurrency(data.nominal_end_balance))}</td>
                </tr>`
            })
        }

        return result
    }

    function setHtmlFooterResult(dataList, data) {
        let result = ``
        let resultMutation = ``
        let total_open_balance = 0
        let total_net_mutation = 0
        let total_end_balance = 0

        if (dataList.length > 0) {
            dataList.forEach((data) => {
                total_open_balance += parseInt(data.nominal_open_balance)
                total_net_mutation += parseInt(data.nominal_net_mutation)
                total_end_balance += parseInt(data.nominal_end_balance)
            })
        }

        if (data.mutations.length > 0) {
            data.mutations.forEach((mutation) => {
                resultMutation += `
                    <td class="text-right">${checkNegative(formatCurrency(mutation.total_mutation_debit))}</td>
                    <td class="text-right">${checkNegative(formatCurrency(mutation.total_mutation_credit))}</td>
                `
            })
        }

        result += `<tr class="border-top-total">
            <td class="text-center hard_left" colspan="2">TOTAL</td>
            <td class="text-right">${checkNegative(formatCurrency(total_open_balance))}</td>
            ${resultMutation}
            <td class="text-right">${checkNegative(formatCurrency(total_net_mutation))}</td>
            <td class="text-right">${checkNegative(formatCurrency(total_end_balance))}</td>
        </tr>`

        return result
    }

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
                datatype: "JSON",
                beforeSend: () => {
                    selectorLoader.show()
                    selectorTableBody.empty()
                },
                data: {
                    'company_id': companyId,
                    'start_period': startPeriod,
                    'end_period': endPeriod
                },
                success: function (response) {
                    return new Promise(() => {
                        setTimeout(() => {
                            let htmlBody = ``
                            let dataResponse = response.data
                            selectorLoader.hide()

                            setHtmlHeaderMutation(dataResponse.content_account_balances)
                            setHtmlHeaderMonthsMutation(dataResponse.content_account_balances)
                            setHtmlHeaderBalanceMutation(dataResponse.content_account_balances)
                            htmlBody += setHtmlFillData(dataResponse.content_account_balances)
                            htmlBody += setHtmlFooterResult(dataResponse.content_account_balances, dataResponse.total_account_group_month)
                            selectorTableBody.html(htmlBody)
                        }, 1000)
                    })
                }
            })
        } catch (error) {
            console.log(error)
        }
    }
})
