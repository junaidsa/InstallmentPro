$(document).ready(function () {
    $(".select2").select2();
    if ($("#profitTable").length) {
        initializeProfitTable();
    }
});
$("#profitTable").DataTable({
    responsive: false,
    autoWidth: false,
    scrollX: true,
    dom: "Bfrtip",
    buttons: [
        { extend: "excelHtml5", text: excelText, footer: true },
        {
            extend: "pdfHtml5",
            text: pdfText,
            footer: true,
            orientation: "landscape",
            pageSize: "A4",
            className: "btn btn-success",
        },
    ],
});

function parseNumericValue(value) {
    if (typeof value === "string") {
        return parseFloat(value.replace(/[\$,]/g, "")) || 0;
    }
    return typeof value === "number" ? value : 0;
}
