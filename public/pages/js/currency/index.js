$(function () {
    const token = $('meta[name="csrf-token"]').attr('content')
    const routeFetch = $("#routeFetch").val()
    const routeDelete = $("#routeDelete").val()
    const userId = $("#userId").val()

    fetchDatatable()
    $("#show-all").removeClass("items-none")
    $("#show-all").addClass("items-inline")

    $("#show-all").on('click', function (e) {
        actionReinitilize(e, "#show-all", "#show-active")
    })

    $("#show-active").on('click', function (e) {
        actionReinitilize(e, "#show-active", "#show-all")
    })

    $("#currencies-datatable tbody").on("click", ".btn-delete", function () {
        let id = $(this).data("id")
        let name = $(this).data("name")
        softDelete(id, name, routeDelete)
    })

    function actionReinitilize(e, selectorClass, unselectorClass) {
        e.preventDefault()
        $(selectorClass).hide()
        $(unselectorClass).show()
        $("#currencies-datatable").dataTable().fnDestroy()
        fetchDatatable($(selectorClass).data("id"))
    }

    function fetchDatatable(isActive = null) {
        let request = { "is_active": isActive }

        $.ajaxSetup({
            headers: { "X-CSRF-TOKEN": token },
        });
        // datatable
        let dataTable = $("#currencies-datatable").DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                "url": routeFetch,
                "data": request
            },
            createdRow: function (row, data) {
                $(row).attr('data-id', data.id)
            },
            "columnDefs": [{
                "targets": [ 4 ],
                "visible": false,
            }],
            columns: [
                { data: "iso_code" },
                { data: "name" },
                { data: "currency_code" },
                { data: "symbol" },
                { data: "created_at" },
                {
                    data: 'action',
                    orderable: false,
                    searchable: false
                }
            ],
            "order": [[4, "desc"]]
        });

        $('.filter-input').on('keyup', function () {
            dataTable.column($(this).data('column'))
                .search($(this).val())
                .draw();
        });
    }

    function softDelete(id, name, route_name) {
        Swal.fire({
            title: 'Are you sure?',
            text: 'Please confirm deletion of currency ' + name,
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
                    },
                    success: function(response) {
                        notifAlert("Success, Currency"+ " " + response.data.currency_name +" has been deleted", timeSince(new Date(Date.now())))
                        $("tr[data-id=" + id + "]").fadeOut('slow');
                    },
                    error: function() {
                        alertNotify("Something went wrong!")
                    }
                });
            }
        })
    }
})
