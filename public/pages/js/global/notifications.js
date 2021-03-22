function createNotification(title, textfill) {
    let noti = document.createElement('div')
    let h3 = document.createElement('h3')
    let p = document.createElement('p')
    h3.innerHTML = title
    p.innerHTML = textfill
    noti.className = 'notification-manual-custom'
    noti.appendChild(h3)
    noti.appendChild(p)
    document.body.appendChild(noti)
    removeNotifications()
    setTimeout(() => {
        noti.classList.add('visible')
    }, 10)
    setTimeout(() => {
        noti.classList.add('remove')
        setTimeout(() => {
            noti.remove()
        }, 300)
    }, 2500)
}

function removeNotifications() {
    const notifications = document.querySelectorAll('.visible')
    if (notifications.length > 0) {
        notifications.forEach((noti) => {
            noti.classList.add('remove')
            setTimeout(() => {
                noti.remove()
            }, 300)
        })
    }
}

function notifAlert(message, time) {
    setTimeout(() => {
        $('.toast').toast('show')
        $('.fill-message').text(message)
        $('.current-time').text(time + ' ago')
    }, 10)
    setTimeout(() => {
        setTimeout(() => {
            $('.toast').toast('hide')
        }, 300)
    }, 2500)
}

function alertNotify(Msg) {
    Swal.fire({
        icon: 'error',
        title: 'Oops...',
        text: Msg,
    })

    return false;
}

function alertNotifyInfo(Msg) {
    Swal.fire({
        icon: 'info',
        title: 'Info',
        text: Msg,
    })

    return false;
}

function alertNotifySucceess(Msg) {
    Swal.fire({
        icon: 'success',
        title: 'success',
        text: Msg,
    })

    return false;
}

function displayError(inputSelector, divSelector, msg) {
    $(inputSelector).addClass("is-invalid")
    $(divSelector).css("display", "block")
    $(divSelector).text(msg)
}

function removeError(inputSelector, divSelector) {
    $(inputSelector).removeClass("is-invalid")
    $(divSelector).css("display", "none")
    $(divSelector).text("")
}
