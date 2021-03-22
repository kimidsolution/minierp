$(function () {
    const token = $('meta[name="csrf-token"]').attr('content')
    const routeFetch = $("#routeFetch").val()
    const routeDelete = $("#routeDelete").val()
    const routePosted = $("#routePosted").val()
    const userId = $("#userId").val()
    const isAdmin = $("#isAdmin").val()
    let companyId = !isAdmin ? $("#companyId").val() : null

    fetchDatatable(null, companyId)
    if (isAdmin) {
        $("#show-all").removeClass("items-none")
        $("#show-all").addClass("items-inline")
    }

    $("#show-all").on('click', function (e) {
        actionReinitilize(e, "#show-all", "#show-active", companyId)
    })

    $("#show-active").on('click', function (e) {
        actionReinitilize(e, "#show-active", "#show-all", companyId)
    })

    $("#expense-datatable tbody").on("click", ".btn-delete", function () {
        let id = $(this).data("id")
        let name = $(this).data("name")
        softDelete(id, name)
    })

    $("#expense-datatable tbody").on("click", ".btn-posted", function () {
        let id = $(this).data("id")
        let name = $(this).data("name")
        flagPosted(id, name)
    })

    $("#filter-company").on("change", function (e) {
        e.preventDefault()
        companyId = $(this).val()
        if (companyId === "") {
            $("#show-all").show()
            $("#show-active").hide()
        }
        $("#expense-datatable").dataTable().fnDestroy()
        fetchDatatable(null, companyId)
    })

    function actionReinitilize(e, selectorClass, unselectorClass, companyId) {
        e.preventDefault()
        if (isAdmin) {
            $(selectorClass).hide()
            $(unselectorClass).show()
        }
        $("#expense-datatable").dataTable().fnDestroy()
        fetchDatatable($(selectorClass).data("id"), companyId)
    }

    function fetchDatatable(isActive = null, companyId = null) {
        let request = {
            "is_active": isActive,
            "company_id": companyId
        }
        $.ajaxSetup({ headers: { "X-CSRF-TOKEN": token } })
        let pageTable = $("#expense-datatable").DataTable({
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
                    width: '10%',
                    className: 'text-center'
                },
                {
                    targets: 1,
                    width: '15%',
                    className: 'text-center'
                },
                {
                    targets: 2,
                    width: '15%',
                },
                {
                    targets: 3,
                    width: '15%',
                    className: 'text-right'
                },
                {
                    targets: 4,
                    width: '25%',
                    className: 'iffyTip hideText2'
                },
                {
                    targets: 5,
                    width: '5%',
                    className: 'text-center'
                },
                {
                    targets: 6,
                    width: '5%',
                    className: 'text-center'
                },
            ],
            columns: [
                {
                    data: "expense_date",
                    "render": function (data) {
                        let date = data.split('-')
                        return [date[2], date[1], date[0]].join('-')
                    }
                },
                { data: "reference_number" },
                { data: "account_naming" },
                { data: "amount" },
                { data: "description" },
                { data: "status_transaction" },
                {
                    data: 'action',
                    orderable: false,
                    searchable: false
                }
            ]
        });

        $('#expense-datatable tbody').on('click', 'td .details-control', function () {
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
                                Expense Detail ${refNumber}
                            </h5>
                        </div>
                        <div class="col-md-6">
                            <div class="table-responsive-sm">
                                <table id="${tableId}" class="table table-sm" style="width: 100%; float:right;">
                                    <thead>
                                        <tr>
                                            <th width="40%;">Account Name</th>
                                            <th class="text-right" width="30%;">Debit Amount</th>
                                            <th class="text-right" width="30%;">Credit Amount</th>
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
                .draw();
        });
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
                    html += `
                        <tr>
                            <td>` + data.account_name + `</td>
                            <td class="text-right">
                                Rp. ${checkNegative(formatCurrency(data.debit_amount))}
                            </td>
                            <td class="text-right">
                                Rp. ${checkNegative(formatCurrency(data.credit_amount))}
                            </td>
                        </tr>
                    `
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
                    notifAlert("Success, transaction"+ " " + response.data.reference_number +" has been deleted", timeSince(new Date(Date.now())))
                    $("tr[data-id=" + id + "]").fadeOut('slow')
                })
            }
        })
    }

    function flagPosted(id, name) {
        Swal.fire({
            title: 'Are you sure?',
            text: 'Please confirm post of transaction ' + name,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4d56',
            cancelButtonColor: '#50b380',
            confirmButtonText: 'Yes, Post it!',
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
                    url: routePosted,
                    type: "POST",
                    headers: {"Authorization" : `Bearer ${token}`},
                    data: { "id": id, "user_id": userId }
                }).then((response) => {
                    notifAlert("Success, expense"+ " " + response.data.reference_number +" has been posted", timeSince(new Date(Date.now())))
                    $("tr[data-id=" + id + "] td").eq(5).html(`<span class="badge badge-soft-success">Posted</span>`)
                    $("tr[data-id=" + id + "] td").eq(6).find(".btn-posted").fadeOut("slow")
                    $("tr[data-id=" + id + "] td").eq(6).find(".btn-edit").fadeOut("slow")
                    $("tr[data-id=" + id + "] td").eq(6).find(".btn-delete").fadeOut("slow")
                })
            }
        })
    }
})
