$("#saleReport").DataTable({
    responsive: false,
    autoWidth: false,
    scrollX: true,
    dom: "Bfrtip",
    stateSave: true,
    buttons: [
        {
            extend: "excelHtml5",
            title: "Date-wise Sale Report",
            text: "Export to Excel",
            className: "btn btn-secondary",
            footer: true,
            exportOptions: {
                columns: ":visible",
            },
            customizeData: function (data) {
                var api = this.api();
                var total = api
                    .column(12, {
                        page: "current",
                    })
                    .data()
                    .reduce(function (a, b) {
                        return (
                            (parseFloat(a) || 0) +
                            (parseFloat(b.toString().replace(/,/g, "")) || 0)
                        );
                    }, 0);
                data.body.push([
                    "",
                    "",
                    "",
                    "",
                    "",
                    "",
                    "",
                    "",
                    "",
                    "",
                    "",
                    "Total Sales:",
                    total.toLocaleString(),
                ]);
            },
        },
        {
            extend: "pdfHtml5",
            title: "Date-wise Sale Report",
            text: "Export to PDF",
            className: "btn btn-success",
            orientation: "landscape",
            pageSize: "A4",
            footer: true,
            exportOptions: {
                columns: ":visible",
            },
            customize: function (doc) {
                doc.styles.title = {
                    fontSize: 14,
                    bold: true,
                    alignment: "center",
                };
                doc.defaultStyle.fontSize = 10;
                doc.pageMargins = [20, 20, 20, 20];
                var total = 0;
                doc.content[1].table.body.forEach(function (row, i) {
                    if (i > 0) {
                        var value = row[12].text.replace(/,/g, "");
                        total += parseFloat(value) || 0;
                    }
                });
                doc.content[1].table.body.push([
                    {
                        text: "",
                        colSpan: 12,
                        border: [false, true, false, false],
                    },
                    {},
                    {},
                    {},
                    {},
                    {},
                    {},
                    {},
                    {},
                    {},
                    {},
                    {},
                    {
                        text: "Total Sales: " + total.toLocaleString(),
                        bold: true,
                        alignment: "right",
                        border: [false, true, false, false],
                    },
                ]);
            },
        },
    ],
    language: {
        search: "_INPUT_",
        searchPlaceholder: "Search Sale Report...",
    },
});

$(".clear-date").on("click", function () {
    $(this).siblings("input").val("");
});

$(document).ready(function () {
    const datePicker = flatpickr(".datepicker", {
        altInput: true,
        altFormat: "d/m/Y",
        dateFormat: "Y-m-d",
        defaultDate: "today",
    });
    $("#product_type").on("change", function () {
        let productType = $(this).val();
        if (productType) {
            $.ajax({
                url: "/getProducts/" + productType,
                type: "GET",
                success: function (data) {
                    let productSelect = $("#product_id");
                    productSelect.empty();
                    productSelect.append('<option value="">All</option>');
                    $.each(data, function (_key, product) {
                        productSelect.append(
                            `<option value="${product.id}">${product.product_name}</option>`
                        );
                    });
                    productSelect.trigger("change.select2");
                },
            });
        } else {
            $("#product_id").empty().append('<option value="">All</option>');
        }
    });
});
