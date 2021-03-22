$(function () {
    const token = $('meta[name="csrf-token"]').attr("content");
    const routeFetch = $("#routeFetch").val();
    const routeDelete = $("#routeDelete").val()
    const isAdmin = $("#isAdmin").val();
    const userId = $("#userId").val();
    let companyId = null;
    let companyIdSelect = $("#filter-company").val();
    if (typeof companyIdSelect === 'undefined') {
        companyId = $("#companyId").val();
    } else {
        $("#companyId").val(companyIdSelect);
        companyId = $("#companyId").val();
    }

    // fecth datatable
    fetchDatatable(isAdmin, companyId);

    // hide button
    if (isAdmin) {
        $("#show-all").removeClass("items-none")
        $("#show-all").addClass("items-inline")
    }

    // event click show button
    $("#show-all").on("click", function (e) {
        let companyId = $("#companyId").val();
        actionReinitilize(e, "#show-all", "#show-active", isAdmin, companyId, 0);
    });

    $("#show-active").on("click", function (e) {
        let companyId = $("#companyId").val();
        actionReinitilize(e, "#show-active", "#show-all", isAdmin, companyId, 1);
    });

    $("#user-datatable tbody").on("click", ".btn-delete", function () {
        let id = $(this).data("id")
        let name = $(this).data("name")
        softDelete(id, name, routeDelete)
    })

    function actionReinitilize(e, selectorClass, unselectorClass, isAdmin, companyId, onlyActive) {
        e.preventDefault();
        $(selectorClass).hide();
        $(unselectorClass).show();
        $("#user-datatable").dataTable().fnDestroy();
        fetchDatatable(isAdmin, companyId, onlyActive);
    }

    $("#filter-company").on("change", function (e) {
        e.preventDefault();
        let companyId = $("#filter-company").val();
        let onlyActive = $("#show-all").is(":visible") ? 1 : 0;
        $("#companyId").val(companyId);
        $("#user-datatable").dataTable().fnDestroy();
        fetchDatatable(isAdmin, companyId, onlyActive);
    });

    function fetchDatatable(isAdmin, companyId, onlyActive = 1) {
        let request = {
            only_active: onlyActive,
            company_id: companyId,
            is_admin: isAdmin,
            user_id: userId
        };

        $.ajaxSetup({
            headers: { "X-CSRF-TOKEN": token },
        });

        // datatable
        let companyDatatable = $("#user-datatable").DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: routeFetch,
                data: request,
            },
            createdRow: function (row, data) {
                $(row).attr("data-id", data.id);
            },
            columns: [
                {
                    data: "namecolour",
                },
                {
                    data: "title",
                },
                {
                    data: "job",
                },
                {
                    data: "email",
                },
                {
                    data: "phone_number",
                },
                {
                    data: "role",
                },
                {
                    data: 'action',
                    orderable: false,
                    searchable: false
                },
            ]
        });
    }

    function softDelete(id, name, route_name) {
        Swal.fire({
            title: 'Are you sure?',
            text: 'Please confirm deletion of user ' + name,
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
                    success: function(repsonse) {
                        notifAlert("Success, User"+ " " + repsonse.data.name +" has been deleted", timeSince(new Date(Date.now())))
                        $("tr[data-id=" + id + "]").fadeOut('slow');
                    },
                    error: function(response) {
                        alertNotify("Something went wrong!")
                    }
                });
            }
        })
    }
});
