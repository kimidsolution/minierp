$(function() {
    const token = $('meta[name="csrf-token"]').attr('content')
    const routeFetch = $("#routeFetch").val()
    const routeFetchAccount = $("#routeFetchAccount").val()
    const routeToJournal = '/finance/report/journal/transaction/'
    const isAdmin = $("#isAdmin").val()
    const selectorDateRangeVal = $("#reportrange").val()
    const selectorLoader = $(".section-loader")
    const selectorUrl = $("#idUrl")
    const selectorCompany = $("#companyId")
    const selectorAccount = $("#accountId")
    const selectorTitleCompany = $("#title-company")
    const selectorLocCompany = $("#loc-company")
    const selectorPeriodReport = $("#period-report")
    const selectorAccountName = $("#txt_account_name")
    const selectorAccountCode = $("#txt_account_number")
    const selectorFilterCompany = $("#filter-company")
    const selectorButtonModal = $("#buttonModalFilter")
    const selectorModalFilter = $("#modal-form-filter")
    const selectorForm = $("#form-filter-general-ledger")
    const selectorTableTbody = $("#general-ledger-table tbody")
    const selectorOptionZero = $("input[name='options_zero']")

    let accountIdValue = selectorAccount.val()
    let companyId = !isAdmin ? selectorCompany.val() : null
    let splitRangeDate = selectorDateRangeVal.split(' to ')
    let startDate = splitRangeDate[0]
    let endDate = splitRangeDate[1]
    let loadMoreAmount = null
    let optionZeroValue = false

    selectorAccountName.text('-')
    selectorAccountCode.text('-')
    selectorLoader.hide()
    if (!isAdmin) selectorModalFilter.modal('show')
    if (companyId !== null) getAccountList(companyId)

    selectorOptionZero.on("change", function (e) {
        e.preventDefault()
        optionZeroValue = $(this).val() === 'true' ? true : false
        if (accountIdValue !== null && accountIdValue !== "" && companyId !== null) {
            fetchData(e, startDate, endDate, accountIdValue, null, false, optionZeroValue)
        }
    })

    selectorAccount.on("select2:select", (e) => {
        e.preventDefault()
        accountIdValue = e.target.value
        selectorAccountName.text('-')
        selectorAccountCode.text('-')

        let naming = $(e.params.data.element).data("id")
        if (accountIdValue !== "") {
            let namingSplit = naming.split(' - ')
            selectorAccountName.text(namingSplit[1])
            selectorAccountCode.text(namingSplit[0])
        }
    })

    selectorTableTbody.on("click", ".clickable-row", function () {
        let link = $(this).data("href")
        window.open(link, "_blank")
    })


    selectorFilterCompany.on("select2:select", (e) => {
        e.preventDefault()
        accountIdValue = null
        loadMoreAmount = null
        companyId = e.target.value
        flushSelectOption(selectorAccount, "Choose Account ...")
        flushSelectorWithCondition(selectorButtonModal, companyId, "disabled")

        selectorUrl.val('')
        $("#load_more_button").removeClass('items-inline')
        $("#load_more_button").addClass('items-hide')
        selectorTableTbody.empty()
        selectorPeriodReport.text('')
        selectorTitleCompany.text('')
        selectorLocCompany.text('')
        selectorAccountName.text('-')
        selectorAccountCode.text('-')
        if (companyId !== "") {
            getAccountList(companyId)
            let dataId = $(e.params.data.element).data("id").split('-')

            selectorTitleCompany.text(dataId[0])
            selectorLocCompany.text(dataId[1])
            if (accountIdValue !== null && accountIdValue !== "") {
                fetchData(e, startDate, endDate, accountIdValue, null, false, optionZeroValue)
                selectorPeriodReport.text('Period: ' + startDate + ' to ' + endDate)
            }
        }
    })

    selectorForm.validate({
        errorClass: 'invalid-feedback animated fadeIn',
        errorElement: 'div',
        errorPlacement: (error, el) => {
            jQuery(el).addClass('is-invalid');
            jQuery(el).parents('.form-group').append(error)
        },
        highlight: (el) => {
            jQuery(el).parents('.form-group').find('.is-invalid').removeClass('is-invalid').addClass('is-invalid')
        },
        success: (el) => {
            jQuery(el).parents('.form-group').find('.is-invalid').removeClass('is-invalid')
            jQuery(el).remove()
        },
        rules: {
            'account_id': {
                required: true
            }
        }
    })

    selectorForm.on('submit', function(e) {
        e.preventDefault();
        let form = $(this).serializeObject()

        splitRangeDate = form.reportrange.split(' to ')
        startDate = splitRangeDate[0]
        endDate = splitRangeDate[1]

        if (accountIdValue !== null && accountIdValue !== "") {
            selectorPeriodReport.text('Period: ' + startDate + ' to ' + endDate)
            selectorModalFilter.modal('hide')
            fetchData(e, startDate, endDate, accountIdValue, null, false, optionZeroValue)
        }
    })

    $(document).on('click', '#load_more_button', function (e) {
        let uriId = selectorUrl.val()
        let htmlButtonLoader = `<span class="spinner-border spinner-border-sm" aria-hidden="true"></span>&nbsp Loading...`
        if ($(this).data("id") !== undefined) loadMoreAmount = $(this).data("id")
        $("#load_more_button").attr('disabled', true)
        $("#load_more_button").html(htmlButtonLoader)
        setTimeout(function () {
            fetchData(e, startDate, endDate, accountIdValue, uriId, true, optionZeroValue)
        }, 1000)
    })

    function setCurrency(value, optionZero = false) {
        if (optionZero === true)  return value !== 0 ? checkNegative(formatCurrency(value)) : 0
        return value !== 0 ? checkNegative(formatCurrency(value)) : ''
    }

    function fetchData(e, startDate, endDate, accountId, urlFetch, isLoadMore, optionZero = false) {
        e.preventDefault()

        let request =  {
            'company_id': companyId,
            'start_date': startDate,
            'end_date': endDate,
            'account_id': accountId,
        }

        if (loadMoreAmount !== null) request.last_amount_row = loadMoreAmount

        try {
            $.ajax({
                url: urlFetch !== null ? urlFetch : routeFetch,
                type: 'GET',
                headers: { 'X-CSRF-TOKEN': token },
                datatype: "JSON",
                beforeSend: () => {
                    if (isLoadMore) {
                        $("#load_more_button").removeClass('items-hide')
                        $("#load_more_button").addClass('items-inline')
                    } else {
                        selectorUrl.val('')
                        selectorLoader.show()
                        $("#load_more_button").removeClass('items-inline')
                        $("#load_more_button").addClass('items-hide')
                        selectorTableTbody.empty()
                    }
                },
                data: request,
                success: function (response) {
                    let rowHtml = ``
                    let responseData = response.dataFill
                    let lastBalanceAccount = responseData.data.last_balance_account
                    let dataResultTotal = responseData.data.data_result_total
                    let listData = responseData.data.list_data

                    let lastIndexData = [...listData].pop()
                    let lastAmountBalance = listData.length > 0 && lastIndexData !== undefined ? lastIndexData.balance : null
                    let htmlButtonLoadMore = getHtmlButtonLoadMore(lastAmountBalance)

                    let rowHeader = getHtmlRowAccountBalance(lastBalanceAccount, listData, optionZero)
                    let rowList = getHtmlRowListData(listData, optionZero)
                    let rowFooter = getHtmlRowTotalResult(dataResultTotal, listData, optionZero)

                    loadMoreAmount = lastAmountBalance

                    if (isLoadMore) {
                        $("#load_more_button").removeAttr('disabled')
                        $("#load_more_button").replaceWith(htmlButtonLoadMore)

                        if (responseData.next_page_url === null) {
                            $("#load_more_button").removeClass('items-inline')
                            $("#load_more_button").addClass('items-hide')
                        } else {
                            selectorUrl.val(responseData.next_page_url)
                        }

                        if (selectorUrl.val() !== '') {
                            if (responseData.next_page_url === null) rowHtml = `${rowList} ${rowFooter}`
                            else rowHtml = `${rowList}`
                        }
                        selectorTableTbody.append(rowHtml)
                    } else {
                        return new Promise(() => {
                            setTimeout(() => {
                                selectorLoader.hide()
                                if (responseData.next_page_url !== null) {
                                    $("#load_more_button").removeClass('items-hide')
                                    $("#load_more_button").addClass('items-inline')
                                    selectorUrl.val(responseData.next_page_url)
                                    rowHtml = `${rowHeader} ${rowList}`
                                } else {
                                    rowHtml = `${rowHeader} ${rowList} ${rowFooter}`
                                }
                                selectorTableTbody.append(rowHtml)
                            }, 1000)
                        })
                    }
                }
            })
        } catch (e) {
            console.log(e)
        }
    }

    function getAccountList(companyIdParam) {
        let request = { 'company_id': companyIdParam }

        $.ajax({
            url: routeFetchAccount,
            type: 'GET',
            headers: { 'X-CSRF-TOKEN': token },
            data: request,
            beforeSend: () => {
                flushSelectOption(selectorAccount, "Choose Account ...")
            },
            success: function (response) {
                return new Promise(() => {
                    setTimeout(() => {
                        let listData = response.data
                        if (listData.length > 0) {
                            listData.forEach((data) => {
                                let option = new Option(ucFirst(data.account_naming), data.id, false, false)
                                option.setAttribute("data-id", data.account_naming)
                                selectorAccount.append(option)
                            })
                        }
                    }, 500)
                })
            }
        })
    }

    function getHtmlButtonLoadMore(dataId = null) {
        let dataIdAttr = dataId !== null ? `data-id=${dataId}` : ``

        return `<button type="button" ${dataIdAttr}
            id="load_more_button"
            class="btn btn-primary btn-sm"
            style="width: 15%; margin-bottom: 2%;"
        >
            Load More
        </button>`
    }

    function getHtmlRowAccountBalance(data, dataList, optionZero) {
        let result = ``

        if (data !== undefined && dataList.length > 0) {
            result += `<tr style="background-color: #f1f5fa;">
                <td colspan="4" style="width: 30%;">
                    ${data.description + ' : '}
                    ${selectorAccountName.text()}
                    <span style="font-weight: 500; letter-spacing: 1px; margin-left: 5px;" class="badge badge-primary">
                        ${selectorAccountCode.text()}
                    </span>
                </td>
                <td class="row-currency" style="width: 12%; text-align: right;">${setCurrency(data.debit, optionZero)}</td>
                <td class="row-currency" style="width: 12%; text-align: right;">${setCurrency(data.credit, optionZero)}</td>
                <td class="row-currency" style="width: 12%; text-align: right;">${setCurrency(data.balance, optionZero)}</td>
            </tr>`
        }

        return result
    }

    function getHtmlRowTotalResult(data, dataList, optionZero) {
        let result = ``

        if (data !== undefined && dataList.length > 0) {
            result += `<tr class="border-top-total">
                <td colspan="4" style="width: 10%; text-align: center;">Total</td>
                <td class="row-currency" style="width: 12%; text-align: right;">${setCurrency(data.debit, optionZero)}</td>
                <td class="row-currency" style="width: 12%; text-align: right;">${setCurrency(data.credit, optionZero)}</td>
                <td class="row-currency" style="width: 12%; text-align: right;">${setCurrency(data.balance, optionZero)}</td>
            </tr>`
        }

        return result
    }

    function getHtmlRowListData(dataList, optionZero) {
        let result = ``

        if (dataList.length > 0) {
            dataList.forEach((data) => {
                if (data.debit !== 0) {
                    debitTitle = `<a class="title-link-table"
                        title="Click to journal"
                        target="_blank"
                        href="${routeToJournal+ data.transaction_id}"
                    >
                        ${setCurrency(data.debit, optionZero)}
                    </a>`
                } else {
                    debitTitle = `${setCurrency(data.debit, optionZero)}`
                }
                if (data.credit !== 0) {
                    creditTitle = `<a class="title-link-table"
                        title="Click to journal"
                        target="_blank"
                        href="${routeToJournal+ data.transaction_id}"
                    >
                        ${setCurrency(data.credit, optionZero)}
                    </a>`
                } else {
                    creditTitle = `${setCurrency(data.credit, optionZero)}`
                }

                result += `<tr class="clickable-row" data-href="${routeToJournal+ data.transaction_id}">
                    <td style="width: 10%; text-align: center;">
                        <div class="iffyTip hideText2">${data.reference_number !== null ? data.reference_number : ''}</div>
                    </td>
                    <td style="width: 10%; text-align: center;">${data.date !== null ? data.date : ''}</td>
                    <td style="width: 30%;">
                        ${data.account_name !== null ? data.account_name : ''}
                        <span style="font-weight: 500; letter-spacing: 1px; float: right;" class="badge badge-primary">
                            ${data.account_code !== null ? data.account_code : ''}
                        </span>
                    </td>
                    <td style="text-align: center; width: 14%;">
                        <div class="iffyTip hideText2">${data.description !== null ? data.description : ''}</div>
                    </td>
                    <td class="row-currency" style="width: 12%; text-align: right;">${setCurrency(data.debit, optionZero)}</td>
                    <td class="row-currency" style="width: 12%; text-align: right;">${setCurrency(data.credit, optionZero)}</td>
                    <td class="row-currency" style="width: 12%; text-align: right;">
                        ${setCurrency(data.balance, true)}
                        <i class="${data.icon_balance}"></i>
                    </td>
                </tr>`
            })
        } else {
            result = `<tr><td colspan="7" class="text-center">No data available</td></tr>`
        }

        return result
    }
})
