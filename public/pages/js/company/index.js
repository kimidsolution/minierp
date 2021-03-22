$(function () {
    const token = $('meta[name="csrf-token"]').attr('content')
    const routeFetch = $("#routeFetch").val()
    const routeDelete = $("#routeUpdateStatus").val()
    const userId = $("#userId").val()

    fetchDatatable()
    $("#show-active").hide()

    $("#show-all").on('click', function (e) {
        actionReinitilize(e, "#show-all", "#show-active")
    })

    $("#show-active").on('click', function (e) {
        actionReinitilize(e, "#show-active", "#show-all")
    })

    $("#company-datatable tbody").on("click", ".btn-delete", function () {
        let id = $(this).data("id")
        let name = $(this).data("value")
        softDelete(id, name, routeDelete)
    })

    function actionReinitilize(e, selectorClass, unselectorClass) {
        e.preventDefault()
        $(selectorClass).hide()
        $(unselectorClass).show()
        $("#company-datatable").dataTable().fnDestroy()
        fetchDatatable($(selectorClass).data("id"))
    }

    function fetchDatatable(isActive = null) {
        let request = { "is_active": isActive }

        $.ajaxSetup({
            headers: { "X-CSRF-TOKEN": token },
        });
        // datatable
        let companyDatatable = $("#company-datatable").DataTable({
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
                "targets": [ 9 ],
                "visible": false,
            }],
            columns: [
                { data: "company_name" },
                { data: "brand_name" },
                { data: "type" },
                { data: "city" },
                { data: "country" },
                { data: "email" },
                { data: "phone_number" },
                { data: "pic" },
                { data: "status" },
                { data: "created_at" },
                {
                    data: 'action',
                    orderable: false,
                    searchable: false
                }
            ],
            "order": [[9, "desc"]]
        });

        $('.filter-input').on('keyup', function () {
            companyDatatable.column($(this).data('column'))
                .search($(this).val())
                .draw();
        });
    }

    function softDelete(id, name, route_name) {
        Swal.fire({
            title: 'Please confirm',
            text: 'Deletion of Company ' + name,
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
                        "company_id": id,
                        "user_id": userId,
                        "status_id": 4 // to change status to deleted
                    },
                    success: function(response) {
                        alertNotifyInfo("Success, Company"+ " " + response.data.company_name +" has been deleted")
                        $("tr[data-id=" + id + "]").fadeOut('slow');
                    },
                    error: function() {
                        alertNotify("Something went wrong!")
                    }
                });
            }
        })
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
})
