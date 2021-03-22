$(function () {
    const token = $('meta[name="csrf-token"]').attr('content')
    const routeFetch = $("#routeFetch").val()
    const userId = $("#userId").val()
    const isAdmin = $("#isAdmin").val()
    const partnerId = $("#partnerId").val()
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
        $("#invoice-datatable").dataTable().fnDestroy()
        fetchDatatable(null, companyId)
    })

    function actionReinitilize(e, selectorClass, unselectorClass, companyId) {
        e.preventDefault()
        if (isAdmin) {
            $(selectorClass).hide()
            $(unselectorClass).show()
        }
        $("#invoice-datatable").dataTable().fnDestroy()
        fetchDatatable($(selectorClass).data("id"), companyId)
    }

    function fetchDatatable(isActive = null, companyId = null) {
        let request = {
            "is_active": isActive,
            "company_id": companyId,
            "partner_id": partnerId,
        }

        $.ajaxSetup({
            headers: { "X-CSRF-TOKEN": token },
        });

        // datatable
        let invoiceDatatable = $("#invoice-datatable").DataTable({
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
                { data: "partner_name" },
                { data: "invoice_number" },
                { data: "datehuman" },
                { data: "duedatehuman" },
                { data: "payment_status" },
                { data: "is_posted" },
                { data: "total_amount" },
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
            invoiceDatatable.column($(this).data('column'))
                .search($(this).val())
                .draw();
        });
    }
})
