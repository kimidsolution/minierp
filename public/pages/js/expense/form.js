$(function () {
    const ASSETS = 1
    const EXPENSES = 6
    const OTHER_EXPENSES = 8

    const token = $('meta[name="csrf-token"]').attr('content')
    const routeFetchStore = $("#routeFetchStore").val()
    const routeFetchAccount = $("#routeFetchAccount").val()
    const selectorExpenseDate = $("#expenseDate")
    const selectorForm = $("#jq-validation-form-create")
    const selectorCompany = $("#companyId")
    const selectorEachButtonSubmit = $(".btn-submit")
    const selectorPaymentAccountId = $("#paymentAccountId")
    const selectorPaymentAccountIdVal = $("#paymentAccountIdValue").val()
    const selectorExpenseAccountId = $("#expenseAccountId")
    const selectorExpenseAccountIdVal = $("#expenseAccountIdValue").val()
    const selectorAmount = document.getElementById("amount")

    let companyIdValue = selectorCompany.val()

    selectorExpenseDate.datepicker({
        autoclose: true,
        todayHighlight: true,
        format: "dd-mm-yyyy"
    })

    formatInputCurrencyValueRupiah('#amount', selectorAmount.value)
    manipulateKeyupCurrencyManually(selectorAmount)

    if (companyIdValue !== "") {
        selectorExpenseAccountIdVal !== undefined ? getAccountExpense(selectorExpenseAccountIdVal) : getAccountExpense()
        selectorPaymentAccountIdVal !== undefined ? getAccountPayment(selectorPaymentAccountIdVal) : getAccountPayment()
    }

    selectorCompany.on("select2:select", (e) => {
        e.preventDefault()
        companyIdValue = e.target.value
        flushSelectOption(selectorExpenseAccountId, "Choose Account ...")
        flushSelectOption(selectorPaymentAccountId, "Choose Account ...")
        if (companyIdValue !== "") {
            getAccountExpense()
            getAccountPayment()
        }
    })

    selectorForm.validate({
        rules: {
            'company_id': {
                required: true
            },
            'expense_date': {
                required: true
            },
            'expense_number': {
                minlength: 3,
                required: true
            },
            'description': {
                required: true,
                minlength: 3
            },
            'expense_account_id': {
                required: true
            },
            'payment_account_id': {
                required: true
            },
            'amount': {
                required: true
            }
        },
        errorClass: 'invalid-feedback animated fadeIn',
        errorElement: 'div',
        errorPlacement: (error, el) => {
            jQuery(el).addClass('is-invalid')
            jQuery(el).parents('.form-group').append(error)
        },
        highlight: (el) => {
            jQuery(el).parents('.form-group').find('.is-invalid').removeClass('is-invalid').addClass('is-invalid')
        },
        success: (el) => {
            jQuery(el).parents('.form-group').find('.is-invalid').removeClass('is-invalid')
            jQuery(el).remove()
        }
    })

    selectorEachButtonSubmit.on("click", function (e) {
        e.preventDefault()
        if (selectorForm.valid()) {
            let formData = selectorForm.serializeObject()
            formData.is_posted = ($(this).attr('id') === "savePost") ? 1 : 0
            formData.amount = unformatCurrency(formData.amount)
            actionForm(formData)
        }
    })

    function actionForm(objectForm) {
        $.ajax({
            type: 'POST',
            headers: { 'X-CSRF-TOKEN': token },
            url: routeFetchStore,
            data: objectForm,
            beforeSend: () => {
                $('#submitForm').prop("disabled", true)
                $('#submitForm').html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Loading...')
            },
            success:function (response) {
                alertNotifySucceess(response.data.message)
                setTimeout(function(){
                    window.location = response.data.url;
                    $('#submitForm').prop("disabled", false)
                    $('#submitForm').html('Save Expense')
                }, 2000)
            },
            error: function (xhr) {
                let err = eval("(" + xhr.responseText + ")")
                Swal.fire({ html: '<strong>Oops!</strong> ' + err.message })

                $('#submitForm').prop("disabled", false)
                $('#submitForm').html('Save Expense')
            }
        })
    }

    function getAccountPayment(selectedValue = null) {
        let request = {
            'company_id': companyIdValue,
            'account_type': [ASSETS]
        }

        $.ajax({
            url: routeFetchAccount,
            type: 'GET',
            headers: { 'X-CSRF-TOKEN': token },
            data: request,
            beforeSend: () => {
                flushSelectOption(selectorPaymentAccountId, "Choose Account ...")
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
                                selectorPaymentAccountId.append(option)
                                selectorPaymentAccountId.select2()
                            })
                        }
                    }, 500)
                })
            }
        })
    }

    function getAccountExpense(selectedValue = null) {
        let request = { 'company_id': companyIdValue }
        request.account_type = [EXPENSES, OTHER_EXPENSES]

        $.ajax({
            url: routeFetchAccount,
            type: 'GET',
            headers: { 'X-CSRF-TOKEN': token },
            data: request,
            beforeSend: () => {
                flushSelectOption(selectorExpenseAccountId, "Choose Account ...")
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
                                selectorExpenseAccountId.append(option)
                                selectorExpenseAccountId.select2()
                            })
                        }
                    }, 500)
                })
            }
        })
    }
})
