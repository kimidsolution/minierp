$(function () {
    const token = $('meta[name="csrf-token"]').attr('content')
    const routeFetch = $("#routeFetch").val()
    const isAdmin = $("#isAdmin").val()
    let companyId = !isAdmin ? $("#companyId").val() : null

    fetchDatatable(null, companyId)

    $("#filter-company").on("change", function (e) {
        e.preventDefault()
        companyId = $(this).val()
        if (companyId === "") {
            $("#show-all").show()
            $("#show-active").hide()
        }
        $("#account-datatable").dataTable().fnDestroy()
        fetchDatatable(null, companyId)
    })

    function fetchDatatable(isActive = null, companyId = null) {
        let request = {
            "is_active": isActive,
            "company_id": companyId
        }

        $.ajaxSetup({
            headers: { "X-CSRF-TOKEN": token },
        });
        // datatable
        let dataTable = $("#account-datatable").DataTable({
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
                { data: "account_code" },
                { data: "account_name" },
                { data: "level" },
                { data: "balance" }
            ],
            "order": [[2, "asc"]]
        });

        $('.filter-input').on('keyup', function () {
            dataTable.column($(this).data('column'))
                .search($(this).val())
                .draw();
        });
    }
})
