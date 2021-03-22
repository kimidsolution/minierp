$(function () {
    const token = $('meta[name="csrf-token"]').attr('content')
    const routeFetch = $("#routeFetch").val()
    const routeDelete = $("#routeUpdateStatus").val()
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

    $("#product-datatable tbody").on("click", ".btn-delete", function () {
        let id = $(this).data("id");
        let name = $(this).data("value");
        let companyIdProduct = $(this).data("company");

        let storeData = {
            "product_id": id,
            "company_id": companyIdProduct,
            "user_id": userId,
            "status_id": 4 // to change status to deleted
        };

        softDelete(id, name, routeDelete, storeData)
    })

    $("#filter-company").on("change", function (e) {
        e.preventDefault()
        companyId = $(this).val()
        if (companyId === "") {
            $("#show-all").show()
            $("#show-active").hide()
        }
        $("#product-datatable").dataTable().fnDestroy()
        fetchDatatable(null, companyId)
    })

    function actionReinitilize(e, selectorClass, unselectorClass, companyId) {
        e.preventDefault()
        if (isAdmin) {
            $(selectorClass).hide()
            $(unselectorClass).show()
        }
        $("#product-datatable").dataTable().fnDestroy()
        fetchDatatable($(selectorClass).data("id"), companyId)
    }

    function fetchDatatable(isActive = null, companyId = null) {
        let request = {
            "is_active": isActive,
            "company_id": companyId
        }

        $.ajaxSetup({
            headers: { "X-CSRF-TOKEN": token },
        });

        // datatable
        let companyDatatable = $("#product-datatable").DataTable({
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
                "targets": [ 7 ],
                "visible": false,
            }],
            columns: [
                { data: "product_name" },
                { data: "type" },
                { data: "sku" },
                { data: "price" },
                { data: "product_category" },
                { data: "company" },
                { data: "status" },
                { data: "created_at" },
                {
                    data: 'action',
                    orderable: false,
                    searchable: false
                }
            ],
            "order": [[7, "desc"]]
        });

        $('.filter-input').on('keyup', function () {
            companyDatatable.column($(this).data('column'))
                .search($(this).val())
                .draw();
        });
    }

    function softDelete(id, name, route_name, storeData) {
        Swal.fire({
            title: 'Please confirm',
            text: 'Deletion of product ' + name,
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
                    data: storeData,
                    success: function(repsonse) {
                        alertNotify("Product"+ " " + repsonse.data.product_name +" has been deleted")
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
            icon: 'info',
            title: 'Info',
            text: Msg,
        })

        return true;
    }
})
