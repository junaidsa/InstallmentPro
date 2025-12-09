$(document).ready(function () {
    const datePicker = flatpickr(".datepicker", {
        altInput: true,
        altFormat: "d/m/Y",
        dateFormat: "Y-m-d",
        defaultDate: new Date(),
    });
    let table = $("#transactionTable").DataTable({
        dom: "Bfrtip",
        responsive: true,
        autoWidth: false,
        scrollX: true,
        columnDefs: [
            {
                targets: [0, 1, 2, 3, 4, 5],
                className: "text-center align-middle",
            },
        ],
        buttons: [
            {
                extend: "excelHtml5",
                title: "Purchase Products Sheet",
                text: "Export to Excel",
                className: "btn btn-secondary mb-3",
                exportOptions: {
                    columns: ":not(.noExport)",
                },
            },
            {
                extend: "pdfHtml5",
                title: "Purchase Products Sheet",
                text: "Export to PDF",
                className: "btn btn-success mb-3",
                exportOptions: {
                    columns: ":not(.noExport)",
                },
                orientation: "portrait",
                pageSize: "A4",
            },
        ],
    });
});
