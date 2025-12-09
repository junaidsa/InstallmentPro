$(".select2").select2({
    theme: "bootstrap4",
    width: "100%",
    placeholder: "Select",
    allowClear: true,
});
$(document).ready(function () {
    toastr.options = {
        closeButton: true,
        progressBar: true,
    };
    $("#productDD").change(function () {
        var selectedProduct = $("#productDD option:selected");
        var salePrice = selectedProduct.data("sale");
        var purchase_id = selectedProduct.data("purchase_id");
        $("#totalPayment").attr("min", salePrice);
        $("#totalPayment").val(salePrice);
        $("#purchase_id").val(purchase_id);
    });
});
var csrfToken = $('meta[name="csrf-token"]').attr("content");
$(".select2").select2();
const table = $("#bookingTable").DataTable({
    responsive: false,
    autoWidth: false,
    scrollX: true,
    dom: "Bfrtip",
    buttons: [
        {
            extend: "excelHtml5",
            title: "Available Booking Sheet",
            text: "Export to Excel",
            className: "btn btn-secondary",
            exportOptions: {
                columns: ":not(.noExport)",
            },
        },
    ],
});
$(".dayPicker")
    .datepicker({
        format: "dd",
        minViewMode: "days",
        maxViewMode: "days",
        autoclose: true,
        todayHighlight: true,
    })
    .on("changeDate", function (e) {
        $(this).val(e.date.getDate());
    });

const dealDatePicker = flatpickr("#deal_date", {
    altInput: true,
    altFormat: "d/m/Y",
    dateFormat: "Y-m-d",
    defaultDate: "today",
    minDate: "2025-01-01",
    maxDate: "today",
});

$("#startDate").on("change", function () {
    let monthVal = $(this).val();
    if (monthVal) {
        let parts = monthVal.split("-");
        let year = parseInt(parts[0]);
        let month = parseInt(parts[1]) - 1;
        let newDate = new Date(year, month, 1);
        $(".datePicker").datepicker("update", newDate);
    }
});
table.on("draw", function () {
    initializeEditable();
});

function calculatePayment() {
    var totalPayment = parseFloat($("#totalPayment").val()) || 0;
    var discountPayment = parseFloat($("#discountPayment").val()) || 0;
    var downPayment = parseFloat($("#downPayment").val()) || 0;
    var months = parseInt($("#installmentMonths").val()) || 1;

    var netPayment = totalPayment - discountPayment;
    var remainingAmount = netPayment - downPayment;

    if (netPayment < 0 || remainingAmount < 0) {
        toastr.info("Amount cannot be negative");
        return;
    }

    var monthlyInstallment =
        months > 0 ? Math.round(remainingAmount / months) : 0;

    $("#netPayment").val(netPayment);
    $("#remainingAmount").val(remainingAmount);
    $("#monthlyInstallment").val(monthlyInstallment);
}

$("#productType").on("change", function () {
    var type = $(this).val();
    $("#productDD")
        .empty()
        .append("<option disabled selected>Select Product</option>");

    if (type) {
        $.ajax({
            url: "/bookingProducts/" + type,
            type: "GET",
            dataType: "json",
            success: function (data) {
                if (data.length) {
                    $.each(data, function (key, value) {
                        $("#productDD").append(
                            ` <option value="${value.product_id}"    data-sale="${value.sale_price}"
                                                data-purchase_id="${value.id}">
                                ${value.product.product_name}
                              
                              </option>`
                        );
                    });
                } else {
                    $("#productDD").append(
                        "<option disabled>No Products Found</option>"
                    );
                }
            },
            error: function (xhr, status, error) {
                toastr.error("Some thing went to wor", "ERROR");
            },
        });
    }
});
$(
    "#productDD, #installmentMonths, #totalPayment, #discountPayment, #downPayment"
).on("change keyup", function () {
    calculatePayment();
});
