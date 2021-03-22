const urlCheckConfig = `/api/configuration/finance/check-usage-company`
const tokenHeaders = $('meta[name="csrf-token"]').attr('content')

function checkFinanceConfigAccountCompany(companyId, configCode = []) {
    let configCodeArray = JSON.parse(configCode)
    let request = { company_id: companyId }
    if (configCodeArray.length > 0) request.config_code = configCodeArray

    try {
        $.ajax({
            url: urlCheckConfig,
            type: 'GET',
            headers: { 'X-CSRF-TOKEN': tokenHeaders },
            datatype: "JSON",
            data: request,
            success: function (response) {
                let messages = response.messages
                let statusConfig = response.status_config
                let unCompleteConfig = response.uncomplete_config

                if (statusConfig === false) {
                    let option = `
                        <p class="lead">Make sure to setup the following account configurations</p>
                        <ul style="margin: 0 auto; width: 80%;">`

                    unCompleteConfig.forEach((data) => {
                        option += `<li class="list-unstyled"
                            style="font-size: 0.8rem; padding: 3px; text-align: justify;"
                        >
                            <i class="fas fa-window-close font-10 text-danger mr-1"></i> ${data}
                        </li>`
                    })

                    option += `</ul>`
                    Swal.fire({
                        icon: 'info',
                        title: `<strong>${messages}</strong>`,
                        html: option,
                        allowOutsideClick: false,
                        confirmButtonText: `
                            Setup Finance Configuration Account
                            <i style="padding: 2px;" class="fas fa-location-arrow"></i>
                        `
                    }).then((result) => {
                        if (result.value) {
                            window.location = `/configuration/finance/accounts`
                        }
                    })
                }
            }
        })
    } catch (error) {
        console.log(error)
    }
}
