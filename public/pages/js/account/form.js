$(function () {
    const token = $('meta[name="csrf-token"]').attr('content')
    const selectorForm = jQuery('#jq-validation-form-create')
    const routeFetchParent = $("#routeFetchParent").val()
    const routeCheckCode = $("#routeCheckCode").val()
    const selectorId = $("#idAccount")
    const selectorCompany = $("#companyId")
    const selectorAccountType = $("#accountType")
    const selectorAccountParent = $("#accountParent")
    const selectorBalance = $("#balance")
    const selectorBalanceDate = $("#balanceDate")
    const selectorLevel = $("#level")
    const selectorAccountCode = $("#accountCode")
    const selectorprefixType = $("#prefixType")
    const selectorBalanceNominal = document.getElementById("balance_nominal")

    selectorBalanceDate.datepicker({
        autoclose:true,
        todayHighlight:true,
        format:'dd-mm-yyyy'
    })

    formatInputCurrencyValueRupiah('#balance_nominal', selectorBalanceNominal.value)
    manipulateKeyupCurrencyManually(selectorBalanceNominal)

    selectorCompany.on("change", (e) => {
        e.preventDefault()
        selectorBalance.val("")
        selectorLevel.val("")
        selectorprefixType.text("")
        selectorAccountType.val("").trigger('change')

        flushSelectorWithCondition(selectorAccountType, e.target.value, "disabled")
        flushSelectorWithCondition(selectorAccountCode, e.target.value, "readonly")
        flushSelectOption(selectorAccountParent, "Choose Parent Account ...")
    })

    selectorAccountType.on("change", function(e) {
        e.preventDefault()
        selectorLevel.val("")
        selectorBalance.val($(this).find(":selected").data("id"))

        flushSelectorWithCondition(selectorAccountParent, e.target.value, "disabled")
        flushSelectorWithCondition(selectorAccountCode, e.target.value, "readonly")
        flushSelectOption(selectorAccountParent, "Choose Parent Account ...")
        selectorprefixType.text(e.target.value)
        if (selectorId.val() !== undefined) {
            if (selectorCompany.val() !== "" && e.target.value !== "" && selectorId.val() !== "") {
                fetchDataParent(selectorCompany.val(), e.target.value, selectorId.val())
            }
        } else {
            if (selectorCompany.val() !== "" && e.target.value !== "") {
                fetchDataParent(selectorCompany.val(), e.target.value)
            }
        }
    })

    selectorAccountParent.on("select2:select", (e) => {
        e.preventDefault()
        selectorLevel.val($(e.params.data.element).data("id") + 1)
    })

    jQuery.validator.addMethod("uniqueCode", function (value, element, params) {
        let request = {}
        let checkCode = {}
        let thisValue = selectorprefixType.text() !== "" ? selectorprefixType.text() + "-" + $(params[0]).val() : $(params[0]).val()
        if (selectorId.val() !== undefined) {
            if (thisValue !== "" && selectorCompany.val() !== "" && selectorId.val() !== "") {
                request = { 'company_id': selectorCompany.val(), 'code': thisValue, 'except_id': selectorId.val() }
            }
        } else {
            if (thisValue !== "" && selectorCompany.val() !== "") {
                request = { 'company_id': selectorCompany.val(), 'code': thisValue }
            }
        }
        if (thisValue !== "") {
            checkCode = checkUniqueCode(request)
            if (checkCode.status !== undefined) return checkCode.status === true
        }
        return true
    }, '{1} has been registered.')

    selectorForm.on('keyup keypress', (e) => {
        let code = e.keyCode || e.which;
        if (code === 13) {
            e.preventDefault()
            return false
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
            'company_id': {
                required: true
            },
            'account_type': {
                required: true
            },
            'parent_account_id': {
                required: true
            },
            'account_name': {
                required: true,
                minlength: 2,
            },
            'account_code': {
                required: true,
                uniqueCode: ["#accountCode", "Account number"],
                number: true
            },
            'level': {
                required: true
            },
            'balance_date': {
                required: true
            },
            'balance_nominal': {
                required: true
            },
        }
    })

    function fetchDataParent(companyId, accountType, accountId = null) {
        try {
            $.ajax({
                url: routeFetchParent,
                type: 'GET',
                headers: { 'X-CSRF-TOKEN': token },
                data: { 'company_id': companyId, 'account_type': accountType, 'except_id': accountId },
            }).then((response) => {
                let listData = response.data
                if (listData.length > 0) {
                    listData.forEach((data) => {
                        let option = new Option(ucFirst(data.account_code + ' - ' + data.account_name), data.id, false, false)
                        option.setAttribute("data-id", data.level)
                        selectorAccountParent.append(option)
                    })
                }
            })
        } catch (e) {
            console.log(e)
        }
    }

    function checkUniqueCode(request) {
        let checkCode = function () {
            let result = null;
            $.ajax({
                'async': false,
                'type': "POST",
                'global': false,
                'dataType': 'json',
                'url': routeCheckCode,
                'data': request,
                'success': function (data) {
                    result = data
                }
            })
            return result
        }()
        return checkCode
    }
})
