var csrfToken = $('meta[name="csrf-token"]').attr("content");
$(".select2").select2();
function calculatePayment(target) {
    var total_price = $("#total_price").val();
    var quantity_log = $("#quantity_log").val();
    var cost_price = $("#cost_price").val();

    var total_price = cost_price * quantity_log;
    $("#total_price").val(total_price);
}
let table = $("#purchasetTable").DataTable({
    dom: "Bfrtip",
    responsive: false,
    autoWidth: false,
    scrollX: true,
    buttons: [
        {
            extend: "excelHtml5",
            title: "Purchase Products Sheet",
            text: "Export to Excel",
            className: "btn btn-secondary",
            exportOptions: {
                columns: ":not(.noExport)",
            },
        },
    ],
});
$("#cost_price, #sale_price").on("keyup change", function () {
    let cost = parseFloat($("#cost_price").val()) || 0;
    let sale = parseFloat($("#sale_price").val()) || 0;

    if (cost > sale || sale < 0) {
        $("#saveBtn").prop("disabled", true);
    } else {
        $("#saveBtn").prop("disabled", false);
    }
});

$("#purchase_date").flatpickr({
    dateFormat: "d-m-Y",
    allowInput: true,
    defaultDate: "today",
    disableMobile: true,
    maxDate: "today",
});
suppliers = suppliers.map((s) => ({
    value: s.id,
    text: s.name,
}));
$(".update").each(function () {
    var fieldName = $(this).data("name");
    var fieldType = $(this).data("type");

    var updateData = {
        url: "purchaseManagement",
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
    if (fieldType === "select") {
        updateData.source = fieldName === "account_id" ? suppliers : [];
    }

    $(this).editable(updateData);
});
$("#product_type").on("change", function () {
    var type = $(this).val();
    if (type) {
        $.ajax({
            url: "/getProducts/" + type,
            type: "GET",
            dataType: "json",
            success: function (data) {
                $("#product_id").empty();
                $("#product_id").append(
                    "<option disabled selected>Select Product</option>"
                );
                $.each(data, function (key, value) {
                    $("#product_id").append(
                        '<option value="' +
                            value.id +
                            '">' +
                            value.product_name +
                            "</option>"
                    );
                });
            },
        });
    } else {
        $("#product_id").empty();
        $("#product_id").append(
            "<option disabled selected>Select Product</option>"
        );
    }
});
