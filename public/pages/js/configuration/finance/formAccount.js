const token = $('meta[name="csrf-token"]').attr('content')

const selectorIdVal = $("#idConfiguration").val()
const selectorConfigCodeValue = $("#configCodeValue").val()
const routeFetchAccount = $("#routeFetchAccount").val()
const routeFetchConfig = $("#routeFetchConfig").val()
const routeFetchStore = $("#routeFetchStore").val()
const routeFetchLoad = $("#routeFetchLoad").val()

const selectorCompany = $("#companyId")
const selectorConfigCode = $("#configCode")
const selectorForm = $("#jq-validation-form-create")
const selectorAccountDetailsTable = $("#accounts_table")

let counter = 1
let exceptAccountId = []
let companyIdValue = selectorCompany.val()

$(function () {
    if (selectorIdVal === undefined) rowButtonTodo(0)
    if (companyIdValue !== "" && selectorIdVal === undefined) {
        getAccountList(0)
        getAvailableConfigList()
    }
    if (selectorIdVal !== undefined && selectorConfigCodeValue !== undefined) {
        getAvailableConfigList(selectorConfigCodeValue)
        getConfigurationDetails(selectorIdVal)
    }
})

$(document).on("change", ".selectorAccount", function () { manipulateButtonSave() })

$(document).on("change", "#companyId", function () {
    resetRow()
    companyIdValue = $(this).val()
    flushSelectOption(selectorConfigCode, "Choose Configuration ...")
    if (companyIdValue !== "") {
        getAccountList(0)
        getAvailableConfigList()
    }
})

selectorForm.on("submit", function (e) {
    e.preventDefault()
    let formObjectRequest = $(this).serializeObject()
    let checkBefore = checkBeforeSave(formObjectRequest)
    if (checkBefore) actionForm(formObjectRequest)
})

function checkBeforeSave(objectForm) {
    return objectForm.company_id !== "" &&
        objectForm.config_code !== "" &&
        objectForm.config_status !== undefined
}

function rowButtonTodo() {
    let totalAdd = $('.my-button-add').length
    let totalDel = $('.my-button-delete').length
    $('.my-button-add').each(function (index) {
        totalAdd !== (index + 1) ? $('#' + $(this).attr('id')).hide() : $('#' + $(this).attr('id')).show()
    })
    totalDel <= 1 ? $('.my-button-delete').hide() : $('.my-button-delete').show()
}

function pushFilterExceptAccountId() {
    let accountId = []
    $(".selectorAccount").each(function () {
        if ($(this).val() !== "") accountId.push($(this).val())
    })
    exceptAccountId = accountId.filter((v, i, a) => a.indexOf(v) === i)
}

function manipulateButtonSave() {
    $(".selectorAccount").each(function () {
        let accountVal = $(this).val()
        if (accountVal !== "") $("#submitForm").prop("disabled", false)
        else $("#submitForm").prop("disabled", true)
    })
}

function actionForm(objectForm) {
    Swal.fire({
        title: 'Are you sure?',
        text: 'Please confirm of saving data',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4d56',
        cancelButtonColor: '#50b380',
        confirmButtonText: 'Yes, Save it!',
        html: false,
        preConfirm: (e) => {
            return new Promise((resolve) => {
                setTimeout(() => {
                    resolve();
                }, 50);
            });
        }
    }).then((result) => {
        if (result.value) {
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
                    setTimeout(function() {
                        window.location = `/configuration/finance/accounts`
                        $('#submitForm').prop("disabled", false)
                        $('#submitForm').html(response.data.text_button)
                    }, 2000)
                },
                error: function (xhr) {
                    let err = eval("(" + xhr.responseText + ")")
                    Swal.fire({ html: '<strong>Oops!</strong> ' + err.message })

                    $('#submitForm').prop("disabled", false)
                    $('#submitForm').html('Save')
                }
            })
        }
    })
}

function delRow(e) {
    let lastAccountId = $(e).closest("tr").find(".selectorAccount").val()
    removeItemAllWithValue(exceptAccountId, lastAccountId)

    $(e).closest("tr").remove()
    rowButtonTodo(counter)
    manipulateButtonSave()
}

function addRow() {
    let cols = ``
    let row = $("<tr>")
    pushFilterExceptAccountId()
    cols += detailsRowHtml(counter)

    row.append(cols).hide().show("slow")
    selectorAccountDetailsTable.append(row)
    rowButtonTodo(counter)
    getAccountList(counter)
    manipulateButtonSave()
    counter++
}

function resetRow() {
    selectorAccountDetailsTable.empty()
    let cols = ``
    let row = $("<tr>")
    cols += detailsRowHtml()

    row.append(cols).hide().show("slow")
    selectorAccountDetailsTable.append(row)
    rowButtonTodo(0)
    manipulateButtonSave()
}

function getAccountList(counterParam, selectedValue = null) {
    let request = exceptAccountId.length > 0 ?
        { 'company_id': companyIdValue, 'except_id': exceptAccountId } :
        { 'company_id': companyIdValue }

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

function getAvailableConfigList(selectedValue = null) {
    let request = selectedValue !== null ?
        { 'company_id': companyIdValue, 'selected_value': selectedValue } :
            { 'company_id': companyIdValue }

    $.ajax({
        url: routeFetchConfig,
        type: 'GET',
        headers: { 'X-CSRF-TOKEN': token },
        data: request,
        beforeSend: () => {
            flushSelectOption(selectorConfigCode, "Choose Configuration ...")
        },
        success: function (response) {
            return new Promise(() => {
                setTimeout(() => {
                    let listData = response.data
                    if (listData.length > 0) {
                        $.each(listData, function (key, data) {
                            let parseCode = data.code.toString()
                            let selected = selectedValue !== null && selectedValue === parseCode ? "selected" : ""
                            let option = `<option value="${parseCode}" ${selected}>`
                                + ucFirst(data.description) +
                            `</option>`
                            selectorConfigCode.append(option)
                        })
                    }
                }, 500)
            })
        }
    })
}

function getConfigurationDetails(configurationId) {
    let rows = ``
    $.ajax({
        url: routeFetchLoad,
        type: 'GET',
        headers: { 'X-CSRF-TOKEN': token },
        data: { 'configuration_id': configurationId },
        beforeSend: () => {
            rows = ``
            selectorAccountDetailsTable.empty()
            selectorAccountDetailsTable.append(`<tr><td colspan=2 class="text-center">Processing ...</td></tr>`)
        },
        success: function (response) {
            return new Promise(() => {
                setTimeout(() => {
                    let listData = response.data.details
                    if (listData.length > 0) {
                        selectorAccountDetailsTable.empty()
                        listData.forEach((data, index) => {
                            counter = index
                            rows += detailsRowHtml(index, true)
                            getAccountList(index, data.account_id)
                            counter++
                        })
                        selectorAccountDetailsTable.append(rows)

                        rowButtonTodo(counter)
                        $("#submitForm").prop("disabled", false)
                    }
                }, 2000)
            })
        }
    })
}

function detailsRowHtml(counterParam = 0, withRow = false) {
    let cols = ``
    let startRow = withRow ? `<tr><td>` : `<td>`
    let endRow = withRow ? `</td></tr>` : `</td>`

    cols += `${startRow}
        <select class="select2 form-control selectorAccount"
            required
            name="accounts[]"
            ids="${counterParam}"
            style="width: 100%;"
            id="account-${counterParam}"
        >
            <option value=""> Choose Account ... </option>
        </select>
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
