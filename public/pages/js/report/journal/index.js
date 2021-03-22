$(function() {
    const token = $('meta[name="csrf-token"]').attr('content')
    const routeFetch = $("#routeFetch").val()
    const routeFetchAccount = $("#routeFetchAccount").val()
    const isAdmin = $("#isAdmin").val()
    const selectorDateRangeVal = $("#reportrange").val()
    const selectorLoader = $(".part-loader")
    const selectorUrl = $("#idUrl")
    const selectorCompany = $("#companyId")
    const selectorAccount = $("#accountId")
    const selectorTitleCompany = $("#title-company")
    const selectorPeriodReport = $("#period-report")
    const selectorFilterCompany = $("#filter-company")
    const selectorButtonModal = $("#buttonModalFilter")
    const selectorModalFilter = $("#modal-form-filter")
    const selectorForm = $("#form-filter-journal")
    const selectorTableTbody = $("#journal-table tbody")

    let accountIdValue = selectorAccount.val()
    let companyId = !isAdmin ? selectorCompany.val() : null
    let splitRangeDate = selectorDateRangeVal.split(' to ')
    let startDate = splitRangeDate[0]
    let endDate = splitRangeDate[1]

    selectorLoader.hide()
    if (!isAdmin) selectorModalFilter.modal('show')
    if (companyId !== null) getAccountList(companyId)

    selectorAccount.on("select2:select", (e) => {
        e.preventDefault()
        accountIdValue = e.target.value
    })

    selectorFilterCompany.on("select2:select", (e) => {
        e.preventDefault()
        accountIdValue = null
        companyId = e.target.value
        flushSelectOption(selectorAccount, "Choose Account ...")
        flushSelectorWithCondition(selectorButtonModal, companyId, "disabled")

        selectorUrl.val('')
        $("#load_more_button").removeClass('items-inline')
        $("#load_more_button").addClass('items-hide')
        selectorTableTbody.empty()
        selectorPeriodReport.text('')
        selectorTitleCompany.text('')
        if (companyId !== "") {
            getAccountList(companyId)
            fetchData(e, startDate, endDate, null, false)
            selectorPeriodReport.text('Period: ' + startDate + ' to ' + endDate)
            selectorTitleCompany.text($(e.params.data.element).data("id"))
        }
    })

    selectorForm.on('submit', function(e) {
        e.preventDefault();
        let form = $(this).serializeObject()

        splitRangeDate = form.reportrange.split(' to ')
        startDate = splitRangeDate[0]
        endDate = splitRangeDate[1]

        selectorPeriodReport.text('Period: ' + startDate + ' to ' + endDate)
        selectorModalFilter.modal('hide')
        fetchData(e, startDate, endDate, null, false)
    })

    $(document).on('click', '#load_more_button', function (e) {
        let uriId = selectorUrl.val()
        let htmlButtonLoader = `<span class="spinner-border spinner-border-sm" aria-hidden="true"></span>&nbsp Loading...`
        $("#load_more_button").attr('disabled', true)
        $("#load_more_button").html(htmlButtonLoader)
        setTimeout(function () {
            fetchData(e, startDate, endDate, uriId, true)
        }, 1000)
    })

    function fetchData(e, startDate, endDate, urlFetch, isLoadMore) {
        e.preventDefault()
        let request =  {
            'company_id': companyId,
            'start_date': startDate,
            'end_date': endDate,
            'to_json': false
        }
        if (accountIdValue !== null && accountIdValue !== "") request.account_id = accountIdValue
        try {
            $.ajax({
                url: urlFetch !== null ? urlFetch : routeFetch,
                type: 'GET',
                headers: { 'X-CSRF-TOKEN': token },
                datatype: "html",
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
                    if (isLoadMore) {
                        let htmlButtonLoadMore = `<button type="button"
                            class="btn btn-primary btn-sm"
                            id="load_more_button"
                            style="width: 15%; margin-bottom: 2%;"
                        >
                            Load More
                        </button>`

                        $("#load_more_button").removeAttr('disabled')
                        $("#load_more_button").replaceWith(htmlButtonLoadMore)

                        if (response.links.next_page_url === null) {
                            $("#load_more_button").removeClass('items-inline')
                            $("#load_more_button").addClass('items-hide')
                        } else {
                            selectorUrl.val(response.links.next_page_url)
                        }
                        if (selectorUrl.val() !== '') selectorTableTbody.append(response.dataHtml)
                    } else {
                        selectorLoader.hide()
                        if (response.dataHtml !== "") {
                            if (response.links.next_page_url !== null) {
                                $("#load_more_button").removeClass('items-hide')
                                $("#load_more_button").addClass('items-inline')
                                selectorUrl.val(response.links.next_page_url)
                            }
                            selectorTableTbody.append(response.dataHtml)
                        } else {
                            let rowEmpty = `<tr><td colspan="6" class="text-center">No data available</td></tr>`
                            selectorTableTbody.append(rowEmpty)
                        }
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
                                selectorAccount.append(option)
                            })
                        }
                    }, 500)
                })
            }
        })
    }
})
