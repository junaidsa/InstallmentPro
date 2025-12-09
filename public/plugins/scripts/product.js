var csrfToken = $('meta[name="csrf-token"]').attr("content");
$(".select2").select2();
let table = $("#productTable").DataTable({
    responsive: false,
    autoWidth: false,
    scrollX: false,
    dom: "Bfrtip",
    statusSave: true,
    buttons: [
        {
            extend: "excelHtml5",
            title: "Products Sheet",
            text: "Export to Excel",
            className: "btn btn-secondary",
            exportOptions: {
                columns: ":not(.noExport)",
            },
        },
    ],
});
$("#product_name").on("keyup", function () {
    let $input = $(this);
    let name = $input.val().trim();
    let company = $('input[name="product_company"]').val().trim();
    let $saveBtn = $("#saveProduct");
    if (name && company) {
        $.ajax({
            url: "/products/check-name",
            type: "POST",
            data: {
                name: name,
                company: company,
                _token: csrfToken,
            },
            success: function (response) {
                if (response.exists) {
                    toastr.options = {
                        closeButton: true,
                        progressBar: true,
                        timeOut: 3000,
                    };
                    toastr.error(
                        "This product already exists for this company!",
                        "ERROR!"
                    );
                    $saveBtn.prop("disabled", true);
                } else {
                    $saveBtn.prop("disabled", false);
                }
            },
        });
    } else if (!company) {
        toastr.warning("Please enter company name first!", "Warning");
        $('input[name="product_company"]').focus();
    }
});

$.fn.editable.defaults.mode = "inline";

$(".update").each(function () {
    var fieldName = $(this).data("name");
    var fieldType = $(this).data("type");
    var pk = $(this).data("pk");

    var updateData = {
        url: "/productManagement",
        ajaxOptions: {
            type: "PUT",
            headers: {
                "X-CSRF-TOKEN": csrfToken,
            },
        },
        name: fieldName,
        pk: pk,
        validate: function (value) {
            if (fieldName === "product_name") {
                var company = $(this)
                    .closest("tr")
                    .find("td[data-name='product_company']")
                    .text()
                    .trim();

                if (!company) {
                    return "Please enter company first.";
                }

                // Run synchronous AJAX check
                var exists = false;
                $.ajax({
                    url: "/products/check-name",
                    type: "POST",
                    async: false,
                    data: {
                        name: value.trim(),
                        company: company,
                        _token: csrfToken,
                    },
                    success: function (response) {
                        exists = response.exists;
                    },
                });

                if (exists) {
                    return "This product already exists for this company!";
                }
            }
        },
        success: function (response, newValue) {
            toastr.options = {
                closeButton: true,
                progressBar: true,
            };
            toastr.success(
                response.name + " updated to " + response.value,
                "Success!"
            );

            $("#productTable")
                .DataTable()
                .row($(this).closest("tr"))
                .invalidate()
                .draw(false);
        },
        error: function (xhr) {
            toastr.error("Update failed: " + xhr.responseText);
        },
    };

    if (fieldType === "select") {
        updateData.source = fieldName === "product_type" ? productType : [];
    }

    $(this).editable(updateData);
});
