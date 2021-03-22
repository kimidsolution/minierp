$(function () {
    const token = $('meta[name="csrf-token"]').attr('content')
    const routeFetch = $("#routeFetch").val()
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

    $("#filter-company").on("change", function (e) {
        e.preventDefault()
        companyId = $(this).val()
        if (companyId === "") {
            $("#show-all").show()
            $("#show-active").hide()
        }
        $("#voucher-datatable").dataTable().fnDestroy()
        fetchDatatable(null, companyId)
    })

    function actionReinitilize(e, selectorClass, unselectorClass, companyId) {
        e.preventDefault()
        if (isAdmin) {
            $(selectorClass).hide()
            $(unselectorClass).show()
        }
        $("#voucher-datatable").dataTable().fnDestroy()
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
        let voucherDatatable = $("#voucher-datatable").DataTable({
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
                { data: "rekan" },
                { data: "voucher_number" },
                { data: "voucher_date" },
                { data: "is_posted" },
                { data: "total_amount" },
                {
                    data: 'action',
                    orderable: false,
                    searchable: false
                }
            ]
        });

        $('.filter-input').on('keyup', function () {
            voucherDatatable.column($(this).data('column'))
                .search($(this).val())
                .draw();
        });
    }
})
