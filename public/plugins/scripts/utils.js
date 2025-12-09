$(document).ready(function () {
    var userName = $("#userName");
    userName.blur(function () {
        var username = $(this).val();
        var csrfToken = $('meta[name="csrf-token"]').attr("content");
        const submitBtn = $("button[type='submit']");
        $.ajax({
            url: "/checkUsername",
            method: "POST",
            headers: {
                "X-CSRF-TOKEN": csrfToken,
            },
            data: {
                user_name: username,
            },
            success: function (data) {
                if (data.exists) {
                    submitBtn.prop("disabled", true);
                    userName.addClass("error");
                    $("#message").html(
                        '<span class="text-danger">Username Already exists</span>'
                    );
                    submitBtn.disabled = true;
                } else {
                    submitBtn.prop("disabled", false);
                    userName.addClass("success");

                    $("#message").html(
                        '<span class="text-success">Username Available</span>'
                    );
                }
            },
        });
    });
});
function disableAutocomplete(elements) {
    elements.forEach(function (selector) {
        $(selector).attr("autocomplete", "off");
    });
}

function containsEmptyField(requiredFields) {
    const isEmptyField = Object.values(requiredFields).some((field) => !field);

    if (isEmptyField) {
        return true;
    }
    return false;
}

function formatNumber(value, decimalNumbers = 2) {
    return value ? parseFloat(value).toFixed(decimalNumbers) : "0.00";
}

$(document).ready(function () {
    $(document).ajaxStart(function () {
        $("#ajaxLoader").show();
    });

    $(document).ajaxStop(function () {
        $("#ajaxLoader").hide();
    });
    $("#propertyType").on("change", function () {
        var type = $(this).val();
        $("#productDD")
            .empty()
            .append("<option disabled selected>Select Product</option>");

        if (type) {
            $.ajax({
                url: "/purchaseProducts/" + type,
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
                    alert("AJAX Error: ", status, error);
                },
            });
        }
    });
});
