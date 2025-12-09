$(document).ready(function () {
    let table = $("#investorTable").DataTable({
        dom: "Bfrtip",
        responsive: false,
        autoWidth: false,
        scrollX: true,

        buttons: [
            {
                extend: "excelHtml5",
                title: "Account Sheet",
                text: "Export to Excel",
                className: "btn btn-secondary",
                exportOptions: {
                    columns: ":not(.noExport)",
                },
            },
            {
                extend: "pdfHtml5",
                text: "Export to PDF",
                footer: true,
                orientation: "landscape",
                pageSize: "A4",
                className: "btn btn-success",
                exportOptions: {
                    columns: ":not(.noExport)",
                },
            },
        ],
    });

    $.fn.editable.defaults.mode = "inline";
    var csrfToken = $('meta[name="csrf-token"]').attr("content");
    $(".update").each(function () {
        var fieldName = $(this).data("name");
        var fieldType = $(this).data("type");

        var updateData = {
            url: "accountManagement",
            ajaxOptions: {
                method: "PUT",
                headers: {
                    "X-CSRF-TOKEN": csrfToken,
                },
            },
            success: function (response) {
                toastr.options = {
                    closeButton: true,
                    progressBar: true,
                };
                toastr.success(
                    response.name +
                        " updated to " +
                        response.value +
                        " successfully.",
                    "Success!"
                );
            },
        };
        $(this).editable(updateData);
    });
    const dealDatePicker = flatpickr("#investment_date", {
        altInput: true,
        altFormat: "d/m/Y",
        dateFormat: "Y-m-d",
        defaultDate: "today",
        minDate: "2025-01-01",
        maxDate: "today",
    });
});
