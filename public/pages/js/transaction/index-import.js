$(function () {
    const token = $('meta[name="csrf-token"]').attr('content')
    const routeFetch = $("#routeFetch").val()
    const routeDelete = $("#routeDelete").val()
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

    $("#transaction-datatable tbody").on("click", ".btn-delete", function () {
        let id = $(this).data("id")
        let name = $(this).data("name")
        softDelete(id, name, routeDelete)
    })

    $("#filter-company").on("change", function (e) {
        e.preventDefault()
        companyId = $(this).val()
        if (companyId === "") {
            $("#show-all").show()
            $("#show-active").hide()
        }
        $("#transaction-datatable").dataTable().fnDestroy()
        fetchDatatable(null, companyId)
    })

    function actionReinitilize(e, selectorClass, unselectorClass, companyId) {
        e.preventDefault()
        if (isAdmin) {
            $(selectorClass).hide()
            $(unselectorClass).show()
        }
        $("#transaction-datatable").dataTable().fnDestroy()
        fetchDatatable($(selectorClass).data("id"), companyId)
    }

    function fetchDatatable(isActive = null, companyId = null) {
        let request = {
            "company_id": companyId
        }
        $.ajaxSetup({ headers: { "X-CSRF-TOKEN": token } })
        let pageTable = $("#transaction-datatable").DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                "url": routeFetch,
                "data": request
            },
            createdRow: function (row, data) {
                $(row).attr('data-id', data.id)
            },
            columns: [
                { data: "file_name" },
                { data: "status" },
                { data: "company.company_name" },
                {
                    data: "error",
                    "render": function (data) {
                        return data !== null ? data : "-"
                    }
                },
                {
                    data: 'action',
                    orderable: false,
                    searchable: false
                }
            ],
            "order": [[0, "desc"]]
        })

        $('.filter-input').on('keyup', function () {
            pageTable.column($(this).data('column'))
                .search($(this).val())
                .draw()
        })
    }

    function initTableDetails(tableId, data) {
        $('#' + tableId).DataTable({
            processing: true,
            serverSide: true,
            ajax: data.details_url,
            columns: [
                { data: 'account_name', name: 'account_name' },
                { data: 'debit_amount', name: 'debit_amount' },
                { data: 'credit_amount', name: 'credit_amount' }
            ]
        })
    }

    function softDelete(id, name, route_name) {
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
                $("tr[data-id=" + id + "]").fadeOut('slow');
                $.ajax({
                    url: route_name,
                    type: "POST",
                    headers: {"Authorization" : `Bearer ${token}`},
                    data: {
                        "id": id,
                        "user_id": userId
                    }
                }).then((response) => {
                    notifAlert("Success, transaction"+ " " + response.data.reference_number +" has been deleted", timeSince(new Date(Date.now())))
                        $("tr[data-id=" + id + "]").fadeOut('slow');
                })
            }
        })
    }
})
