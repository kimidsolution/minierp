$(function () {
    const token = $('meta[name="csrf-token"]').attr('content')
    const routeFetch = $("#routeFetch").val()
    const routeDelete = $("#routeDelete").val()
    const userId = $("#userId").val()
    const isAdmin = $("#isAdmin").val()
    const selectorShowAll = $("#show-all")
    const selectorShowActive = $("#show-active")
    const selectorTable = $("#configuration-datatable")
    const selectorFilterCompany = $("#filter-company")

    let companyId = !isAdmin ? $("#companyId").val() : null

    fetchDatatable('true', companyId)

    if (isAdmin) {
        selectorShowAll.addClass("items-inline")
        selectorShowAll.removeClass("items-none")
    }

    selectorShowAll.on('click', function (e) {
        actionReinitilize(e, "#show-all", "#show-active", companyId)
    })

    selectorShowActive.on('click', function (e) {
        actionReinitilize(e, "#show-active", "#show-all", companyId)
    })

    $("#configuration-datatable tbody").on("click", ".btn-delete", function () {
        let id = $(this).data("id")
        let name = $(this).data("name")
        console.log(name)
        softDelete(id, name)
    })

    selectorFilterCompany.on("change", function (e) {
        e.preventDefault()
        companyId = $(this).val()
        if (companyId === "") {
            selectorShowAll.show()
            selectorShowActive.hide()
        }
        selectorTable.dataTable().fnDestroy()
        fetchDatatable('true', companyId)
    })

    function actionReinitilize(e, selectorClass, unselectorClass, companyId) {
        e.preventDefault()
        if (isAdmin) {
            $(selectorClass).hide()
            $(unselectorClass).show()
        }
        selectorTable.dataTable().fnDestroy()
        fetchDatatable($(selectorClass).data("id"), companyId)
    }

    function fetchDatatable(isActive = null, companyId = null) {
        let request = {
            "is_active": isActive,
            "company_id": companyId,
        }
        $.ajaxSetup({ headers: { "X-CSRF-TOKEN": token } })
        let pageTable = selectorTable.DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                "url": routeFetch,
                "data": request
            },
            createdRow: function (row, data) {
                $(row).attr('data-id', data.id)
            },
            "columnDefs": [
                {
                    targets: 0,
                    width: '15%',
                    className: 'text-center'
                },
                {
                    targets: 1,
                    width: '15%',
                    className: 'text-center'
                },
                {
                    targets: 2,
                    width: '10%',
                    className: 'text-center'
                },
                {
                    targets: 3,
                    width: '10%',
                    className: 'text-center'
                },
                {
                    targets: 4,
                    width: '10%',
                    className: 'text-center'
                }
            ],
            columns: [
                { data: "company_name" },
                { data: "configuration_description" },
                { data: "configuration_status" },
                { data: "last_updated" },
                {
                    data: 'action',
                    orderable: false,
                    searchable: false
                }
            ],
            "order": [[3, "desc"]]
        })

         // Add event listener for opening and closing details
        $("#configuration-datatable tbody").on('click', 'td .details-control', function () {
            let tr = $(this).closest('tr')
            let row = pageTable.row(tr)
            let rowData = row.data()
            if (rowData !== undefined) {
                let tableId = 'details-' + rowData.id
                let refNumber = rowData.reference_number !== "" && rowData.reference_number !== null ? rowData.reference_number : "-"

                if (row.child.isShown()) {
                    // This row is already open - close it
                    row.child.hide();
                    tr.removeClass('shown');
                } else {
                    // Open this row
                    let htmlTable = `
                    <div class="row">
                        <div class="col-md-6">
                            <h5 style="font-size: 0.9rem; margin-top: 35px; text-align:center;">
                                Configuration Detail ${rowData.configuration_description}
                            </h5>
                        </div>
                        <div class="col-md-6">
                            <div class="table-responsive-sm">
                                <table id="${tableId}" class="table table-sm" style="width: 100%; float:right;">
                                    <thead>
                                        <tr>
                                            <th width="40%;">Account</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                        </div>
                    </div>`

                    row.child(htmlTable).show()
                    initTableDetails(tableId, rowData)
                    tr.addClass('shown')
                    tr.next().find('td').addClass('no-padding pref-table-details')
                }
            }
        })

        $('.filter-input').on('keyup', function () {
            pageTable.column($(this).data('column'))
                .search($(this).val())
                .draw()
        })
    }

    function initTableDetails(tableId, data) {
        $.ajax({
            url: data.details_url,
            type: 'GET',
            headers: { 'X-CSRF-TOKEN': token },
            beforeSend: () => { $('#' + tableId + '  ' + 'tbody').empty() }
        }).then((response) => {
            if (response.length > 0) {
                let html = ``
                response.forEach((data) => {
                    html += `<tr><td>${data.account_naming}</td></tr>`
                })
                $('#' + tableId + '  ' + 'tbody').append(html)
            }
        })
    }

    function softDelete(id, name) {
        Swal.fire({
            title: 'Are you sure?',
            text: 'Please confirm deletion of transaction ' + name,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4d56',
            cancelButtonColor: '#50b380',
            confirmButtonText: 'Yes, delete it!',
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
                    url: routeDelete,
                    type: "POST",
                    headers: {"Authorization" : `Bearer ${token}`},
                    data: {
                        "id": id,
                        "user_id": userId
                    }
                }).then((response) => {
                    notifAlert("Success, configuration"+ " " + response.data.reference_number +" has been deleted", timeSince(new Date(Date.now())))
                    $("tr[data-id=" + id + "]").fadeOut('slow')
                })
            }
        })
    }
})
