function flushSelectOption(selector, message) {
    selector.empty()
    selector.append(`<option value="">`+ message +`</option>`)
}

function flushSelectorWithCondition(selector, value, condition) {
    selector.val("")
    value !== "" ? selector.prop(condition, false) :  selector.prop(condition, true)
}

function ucFirst(string) {
    return string.charAt(0).toUpperCase() + string.slice(1)
}

function formatCurrencyRupiah(num, prefix) {
    let number_string = num.replace(/[^,\d]/g, '').toString(),
        split = number_string.split(','),
        sisa = split[0].length % 3,
        rupiah = split[0].substr(0, sisa),
        ribuan = split[0].substr(sisa).match(/\d{3}/gi)

    if (ribuan) {
        separator = sisa ? '.' : '';
        rupiah += separator + ribuan.join('.')
    }

    rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah
    return prefix == undefined ? rupiah : rupiah !== "" ? prefix + rupiah : rupiah
}


/**
 *
 * Required you have to extends autoNumeric.min.js
 */
function formatInputCurrencyValueRupiah(nameID, value) {
    if (AutoNumeric.getAutoNumericElement(nameID) === null) {
        return new AutoNumeric(nameID, { currencySymbol :'Rp. ', decimalCharacter: ',', digitGroupSeparator: '.', decimalPlaces: 0}).set(value);
    }

    return AutoNumeric.getAutoNumericElement(nameID, { currencySymbol :'Rp. ', decimalCharacter: ',', digitGroupSeparator: '.', decimalPlaces: 0}).set(value);
}

/**
 *
 * Required you have to extends autoNumeric.min.js
 */
function formatCurrency(MyNum, currency = null) {
    if (currency !== null) {
        return accounting.formatMoney(MyNum, currency, 0, ".", ",")
    }
    return accounting.formatMoney(MyNum, '', 0, ".", ",")
}
function unformatCurrency(MyNum) { return accounting.unformat(MyNum, ",")}

function formatDateYmd(dateParam, withDate = true) {
    let date = getDateByMonthYear(dateParam, '-'),
        month = '' + (date.getMonth() + 1),
        day = '' + date.getDate(),
        year = date.getFullYear()

    if (month.length < 2) month = '0' + month
    if (day.length < 2) day = '0' + day

    return withDate ? [year, month, day].join('-') : [year, month].join('-')
}

function checkNegative(value) {
    if (typeof value === "string") {
        let negativeString = value.charAt(0)
        if (negativeString === "-") {
            return "(" + value.slice(1) + ")"
        }
    }
    return value
}

/**
 *
 * @param date param for get specific date
 */
function getDateByMonthYear(montYear, separator = ' ', date = 0) {
    let months = [
        'January',
        'February',
        'March',
        'April',
        'May',
        'June',
        'July',
        'August',
        'September',
        'October',
        'November',
        'December'
    ]

    let splitSeparator = montYear.split(separator)
    let month = (months.indexOf(splitSeparator[0]) + 1)
    if (month.toString().length == 1) {
        month = '0' + month
    }
    return new Date(parseInt(splitSeparator[1]), parseInt(month), date)
}

function manipulateKeyupCurrencyManually(selector) {
    selector.addEventListener("keyup", function () {
        if (this.value.which != 8 && this.value.which != 0 && (this.value.which < 48 || this.value.which > 57)) return false
        selector.value = formatCurrencyRupiah(this.value, "Rp. ")
    })
}

function removeItemAllWithValue(arr, value) {
    let i = 0
    while (i < arr.length) {
        arr[i] === value ? arr.splice(i, 1) : ++i
    }
    return arr
}
