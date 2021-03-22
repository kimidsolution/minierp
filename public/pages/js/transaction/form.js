const modelTypeInvoice = "1"
const modelTypeVoucher = "2"
const modelTypeOther = "3"

const token = $('meta[name="csrf-token"]').attr('content')

const routeFetchStore = $("#routeFetchStore").val()
const routeFetchAccount = $("#routeFetchAccount").val()
const routeFetchInvoice = $("#routeFetchInvoice").val()
const routeFetchVoucher = $("#routeFetchVoucher").val()
const routeFetchDetails = $("#routeFetchDetails").val()
const routeCheckRefNumber = $("#routeCheckRefNumber").val()

const selectorIdVal = $("#idTransaction").val()
const selectoreModelTypeValue = $("#modelType").val()
const selectorTransactionType = $("#transactionType").val()
const selectorReferenceNumberVal = $("#valueReferenceNumber").val()

const selectorModelId = $("#modelId")
const selectorCompany = $("#companyId")
const selectorDivModelId = $("#divModelId")
const selectorDivProcessing = $("#divProcessing")
const selectorForm = $("#jq-validation-form-create")
const selectorTransactionDate = $("#transactionDate")
const selectorDivRefNumber = $("#divReferenceNumber")
const selectorCheckingBalance = $("#checkingBalance")
const selectorTransactionDetailsTable = $("#transactions_table")

let counter = 1
let exceptAccountId = []
let transactionStatus = null
let companyIdValue = selectorCompany.val()

$(function () {
    if (selectorIdVal === undefined) rowButtonTodo(0)
    if (companyIdValue !== "" && selectorIdVal === undefined) getAccountList(0)
    if (selectorIdVal !== undefined && selectorReferenceNumberVal !== undefined) {
        transactionStatus = 1
        getTransactionDetails(selectorIdVal)
        handleModelType(selectoreModelTypeValue, selectorReferenceNumberVal)
    }
    selectorTransactionDate.datepicker({
        autoclose: true,
        todayHighlight: true,
        format: "dd-mm-yyyy"
    })
})

$(document).on("change", "#companyId", function () {
    resetRow()
    companyIdValue = $(this).val()
    if (companyIdValue !== "") getAccountList(0)
})

$(document).on("keyup", ".currencyInput", function () { calculateAll() })

$(document).on("change", ".selectorAccount", function () { manipulateButtonSave() })

$(document).on("click", ".btn-submit", function () { transactionStatus = $(this).attr('id') === "saveDraft" ? 1 : 2 })

$(document).on("change", "#modelType", function (e) { handleModelType(e.target.value) })

selectorForm.on("submit", function (e) {
    e.preventDefault()
    let formObjectRequest = $(this).serializeObject()
    let checkBefore = checkBeforeSave(formObjectRequest)
    if (checkBefore) {
        formObjectRequest.transaction_status = transactionStatus
        if (formObjectRequest.model_id === "") formObjectRequest.model_id = "other"
        actionForm(formObjectRequest)
    }
})

function checkBeforeSave(objectForm) {
    return objectForm.model_type !== "" &&
        objectForm.company_id !== "" &&
        objectForm.transaction_date !== "" &&
        objectForm.description !== ""
}

function rowButtonTodo() {
    let totalAdd = $('.my-button-add').length
    let totalDel = $('.my-button-delete').length
    $('.my-button-add').each(function (index) {
        totalAdd !== (index + 1) ? $('#' + $(this).attr('id')).hide() : $('#' + $(this).attr('id')).show()
    })
    totalDel <= 1 ? $('.my-button-delete').hide() : $('.my-button-delete').show()
}

function calculateTotalBySelectorPrefix(selectorEach, selectorTotal) {
    let totalValue = 0
    selectorEach.each(function () {
        totalValue += Number(unformatCurrency($(this).val()))
    })
    selectorTotal.val(formatCurrency(totalValue, "Rp. "))
}

function pushFilterExceptAccountId() {
    let accountId = []
    $(".selectorAccount").each(function () {
        if ($(this).val() !== "") accountId.push($(this).val())
    })
    exceptAccountId = accountId.filter((v, i, a) => a.indexOf(v) === i)
}

function calculateAll() {
    $(".currencyInput").each(function () {
        let idIndex = $(this).attr("id").split("-")
        if (idIndex.length > 0) {
            let prefix = idIndex[0]
            let index = idIndex[1]
            let prefixClass = prefix === "debit_amount" ? 'debit_amount-' : 'credit_amount-'
            let selectorIndexValue = document.getElementById(prefixClass + index)

            formatInputCurrencyValueRupiah(selectorIndexValue, selectorIndexValue.value)
            calculateTotalBySelectorPrefix(
                prefix === "debit_amount" ? $(".currencyInputDebit") : $(".currencyInputCredit"),
                prefix === "debit_amount" ? $("#totalDebit") : $("#totalCredit"),
            )
            manipulateButtonSave()
        }
    })
}

function manipulateButtonSave() {
    $(".selectorAccount").each(function () {
        let accountVal = $(this).val()
        let totalDebit = parseInt(unformatCurrency($("#totalDebit").val()))
        let totalCredit = parseInt(unformatCurrency($("#totalCredit").val()))

        if (accountVal !== "") {
            if (totalDebit === totalCredit) {
                showCheckingBalance(false)
                $("#submitForm").prop("disabled", false)
            } else {
                showCheckingBalance()
                $("#submitForm").prop("disabled", true)
            }
        } else {
            $("#submitForm").prop("disabled", true)
        }
    })
}

function showCheckingBalance(error = true) {
    let classRmErr = error ? "text-has-success" : "text-has-error"
    let classErr = error ? "text-has-error" : "text-has-success"
    let textErr = error ? "Unbalanced Transactions" : "Balanced Transactions"
    selectorCheckingBalance.removeClass(classRmErr)
    selectorCheckingBalance.addClass(classErr)
    selectorCheckingBalance.text(textErr)
}

function handleModelType(value, selectedReferenceNumber = null) {
    selectorDivProcessing.removeClass("items-hide")
    flushSelectOption(selectorModelId, "Choose Reference ...")
    if (value !== "" && (value === modelTypeInvoice || value === modelTypeVoucher)) {
        selectorDivRefNumber.addClass("items-hide")
        selectorDivModelId.removeClass("items-hide")
        if (value === modelTypeInvoice) getRefrenceList(routeFetchInvoice, selectedReferenceNumber)
        if (value === modelTypeVoucher) getRefrenceList(routeFetchVoucher, selectedReferenceNumber)
    } else {
        selectorDivRefNumber.removeClass("items-hide")
        selectorDivModelId.addClass("items-hide")
        selectorDivProcessing.addClass("items-hide")
    }
}

function getRefrenceList(routeFetch, selectedValue = null) {
    let request = { 'company_id': companyIdValue, 'type': selectorTransactionType }
    $.ajax({
        url: routeFetch,
        type: 'POST',
        headers: { 'X-CSRF-TOKEN': token },
        data: request,
        beforeSend: () => {
            selectorDivModelId.addClass("items-hide")
            flushSelectOption(selectorModelId, "Choose Reference ...")
        },
        success: function (response) {
            return new Promise(() => {
                setTimeout(() => {
                    selectorDivProcessing.addClass("items-hide")
                    selectorDivModelId.removeClass("items-hide")
                    let listData = response.data
                    if (listData.length > 0) {
                        $.each(listData, function (key, data) {
                            let selected = selectedValue !== null && selectedValue === data.text ? "selected" : ""
                            let option = `<option value="${data.id}" ${selected}>`
                                + data.text +
                            `</option>`
                            selectorModelId.append(option)
                        })
                    }
                }, 1000)
            })
        }
    })
    selectorModelId.select2()
}

function getAccountList(counterParam, selectedValue = null) {
    let request = exceptAccountId.length > 0 ?
        { 'company_id': companyIdValue, 'except_id': exceptAccountId } : { 'company_id': companyIdValue }
    $.ajax({
        url: routeFetchAccount,
        type: 'GET',
        headers: { 'X-CSRF-TOKEN': token },
        data: request,
        beforeSend: () => {
            flushSelectOption($("#account-" + counterParam), "Choose Account ...")
        },
        success: function (response) {
            return new Promise(() => {
                setTimeout(() => {
                    let listData = response.data
                    if (listData.length > 0) {
                        $.each(listData, function (key, data) {
                            let selected = selectedValue !== null && selectedValue === data.id ? "selected" : ""
                            let option = `<option value="${data.id}" ${selected}>`
                                + ucFirst(data.account_naming) +
                            `</option>`
                            $("#account-" + counterParam).append(option)
                            $("#account-" + counterParam).select2()
                        })
                    }
                }, 500)
            })
        }
    })
}

function getTransactionDetails(transactionId) {
    let rows = ``
    $.ajax({
        url: routeFetchDetails,
        type: 'POST',
        headers: { 'X-CSRF-TOKEN': token },
        data: { 'transaction_id': transactionId },
        beforeSend: () => {
            rows = ``
            selectorTransactionDetailsTable.empty()
            selectorTransactionDetailsTable.append(`<tr><td colspan=4 class="text-center">Processing ...</td></tr>`)
        },
        success: function (response) {
            return new Promise(() => {
                setTimeout(() => {
                    let listData = response.data.details
                    if (listData.length > 0) {
                        selectorTransactionDetailsTable.empty()
                        listData.forEach((data, index) => {
                            counter = index
                            rows += detailsRowHtml(index, data, true)
                            getAccountList(index, data.account.id)
                            counter++
                        })
                        selectorTransactionDetailsTable.append(rows)

                        calculateAll()
                        rowButtonTodo(counter)
                        showCheckingBalance(false)
                        $("#submitForm").prop("disabled", false)
                    }
                }, 2000)
            })
        }
    })
}

function actionForm(objectForm) {
    $.ajax({
        type: 'POST',
        url: routeFetchStore,
        data: objectForm,
        beforeSend: () => {
            $('#submitForm').prop("disabled", true)
            $('#submitForm').html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Loading...')
        },
        success: function (response) {
            alertNotifySucceess(response.data.message)
            setTimeout(function(){
                window.location = response.data.url;
                $('#submitForm').prop("disabled", false)
                $('#submitForm').html('Edit Transaction')
            }, 2000)
        },
        error: function (xhr) {
            let err = eval("(" + xhr.responseText + ")")
            Swal.fire({ html: '<strong>Oops!</strong> ' + err.message })

            $('#submitForm').prop("disabled", false)
            $('#submitForm').html('Edit Transaction')
        }
    })
}

function delRow(e) {
    let lastAccountId = $(e).closest("tr").find(".selectorAccount").val()
    removeItemAllWithValue(exceptAccountId, lastAccountId)

    $(e).closest("tr").remove()
    rowButtonTodo(counter)
    calculateAll()
}

function addRow() {
    let cols = ``
    let row = $("<tr>")
    pushFilterExceptAccountId()
    cols += detailsRowHtml(counter)

    row.append(cols).hide().show("slow")
    selectorTransactionDetailsTable.append(row)
    rowButtonTodo(counter)
    getAccountList(counter)
    manipulateButtonSave()
    counter++
}

function resetRow() {
    selectorTransactionDetailsTable.empty()
    let cols = ``
    let row = $("<tr>")
    cols += detailsRowHtml()

    row.append(cols).hide().show("slow")
    selectorTransactionDetailsTable.append(row)
    rowButtonTodo(0)
    calculateAll()
}

function detailsRowHtml(counterParam = 0, objectValue = null, withRow = false) {
    let cols = ``
    let startRow = withRow ? `<tr><td>` : `<td>`
    let endRow = withRow ? `</td></tr>` : `</td>`
    let defaultAmountDebit = objectValue !== null && objectValue.debit_amount !== null ?
        formatCurrency(objectValue.debit_amount, "Rp. ") : 0
    let defaultAmountCredit = objectValue !== null && objectValue.credit_amount !== null ?
        formatCurrency(objectValue.credit_amount, "Rp. ") : 0

    cols += `${startRow}
        <select class="select2 form-control selectorAccount"
            required
            name="account[]"
            ids="${counterParam}"
            style="width: 100%;"
            id="account-${counterParam}"
        >
            <option value=""> Choose Account ... </option>
        </select>
    </td>`
    cols += `<td>
        <input type="text"
            name="debit_amount[]"
            style="text-align: right;"
            value="${defaultAmountDebit}"
            id="debit_amount-${counterParam}"
            class="form-control debit_amount currencyInput currencyInputDebit"
        >
    </td>`
    cols += `<td>
        <input type="text"
            name="credit_amount[]"
            style="text-align: right;"
            value="${defaultAmountCredit}"
            id="credit_amount-${counterParam}"
            class="form-control credit_amount currencyInput currencyInputCredit"
        >
    </td>`
    cols += `<td class="text-center">
        <div class="dropdown d-inline-block">
            <button type="button"
                id="btn-add-${counterParam}"
                style="margin: 10px;"
                title="Click for add row"
                onclick="addRow()"
                class="btn btn-sm btn-gradient-primary waves-effect waves-light my-button-add"
            >
                <i class="fas fa-plus"></i>
            </button>
            <button type="button"
                id="btn-del-${counterParam}"
                style="margin: 10px;"
                onclick="delRow(this)"
                title="Click for remove row"
                class="btn btn-sm btn-gradient-danger waves-effect waves-light my-button-delete"
            >
                <i class="far fa-trash-alt"></i>
            </button>
        </div>
    ${endRow}`

    return cols
}
