$(document).ready(function () {
    $("#installmentTable").DataTable({
        responsive: false,
        autoWidth: false,
        scrollX: true,
    });
    $(".select2").select2({});
    $(".custom-file-input").on("change", function () {
        let fileName = $(this).val().split("\\").pop();
        $(this).next(".custom-file-label").addClass("selected").html(fileName);
    });

    $("#accountDD").on("change", function () {
        let customerId = $(this).val();
        if (customerId) {
            $.ajax({
                url: "booking/" + customerId,
                type: "GET",
                success: function (data) {
                    let bookingSelect = $("#bookingDD");
                    bookingSelect.empty();
                    bookingSelect.append(
                        '<option value="" disabled selected>Select</option>'
                    );

                    $.each(data, function (_key, booked) {
                        bookingSelect.append(
                            `<option value="${booked.id}">
                                ${booked.property_type} (${booked.product.product_name})
                            </option>`
                        );
                    });
                    bookingSelect.trigger("change.select2");
                },
            });
        }
    });
    $(document).on("click", ".pay-btn", function () {
        let id = $(this).data("id");
        let title = $(this).data("title");
        let due = $(this).data("due");
        console.log();

        let amount = parseFloat($(this).data("amount")) || 0;
        let late_payment_penalty = parseFloat($(this).data("late")) || 0;
        let paid_amount = parseFloat($(this).data("paid")) || 0;
        let remaining_amount = parseFloat($(this).data("remaining")) || 0;

        let dueDate = new Date(due);
        dueDate.setHours(0, 0, 0, 0);
        let today = new Date();
        today.setHours(0, 0, 0, 0);
        let penalty = 0;
        if (
            today > dueDate &&
            paid_amount === 0 &&
            remaining_amount === amount
        ) {
            penalty = late_payment_penalty;
        }

        let baseAmount = remaining_amount > 0 ? remaining_amount : amount;
        let totalInstallmentPay = baseAmount + penalty;
        $("#installmentId").val(id);
        $("#installmentTitle").val(title);
        $("#dueDate").val(due);
        $("#amount").val(baseAmount);
        $("#late_payment_penalty").val(penalty);
        $("#late_fine").val(late_payment_penalty);
        $("#totalAmount_pay").val(totalInstallmentPay);
        $("#paidAmount").val(paid_amount);
        $("#payAmount").val(baseAmount);
        $("#payModal").modal("show");
    });

    $(document).on("click", "#confirm_payment", function (e) {
        e.preventDefault();

        let formData = new FormData($("#payForm")[0]);
        $.ajax({
            url: "/installments/pay",
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            success: function (response) {
                $("#payModal").modal("hide");
                $("#payForm")[0].reset();
                $(".custom-file-label").text("Choose File");
                toastr.success("Payment confirmed successfully!", "Success!");
                insallmentUnpaind($("#bookingDD").val());
                window.open("/receipt/" + response.transaction.id, "_blank");
            },
            error: function (xhr) {
                let res = JSON.parse(xhr.responseText);
                if (res.errors) {
                    $.each(res.errors, function (field, messages) {
                        toastr.error(messages[0], "Error");
                    });
                } else {
                    toastr.error("Something went wrong!", "Error");
                }
            },
        });
    });
    $(document).on("input", "#late_payment_penalty", function () {
        let installmentAmount = parseFloat($("#amount").val()) || 0;
        let adminPenalty = parseFloat($(this).val()) || 0;

        let newTotal = installmentAmount + adminPenalty;

        $("#totalAmount_pay").val(newTotal);
        $("#late_payment_penalty").val(adminPenalty);
    });

    $("#cancelBtn").on("click", function () {
        var modal = bootstrap.Modal.getInstance(
            document.getElementById("payModal")
        );
        modal.hide();
    });
});
function insallmentUnpaind(bookingId = null) {
    if (!bookingId) {
        toastr.warning("Please select a booking first!", "Warning");
        return;
    }

    $.ajax({
        url: "instalment/remaining/" + bookingId,
        type: "GET",
        success: function (data) {
            let rows = "";
            let sr = 1;

            if (data.length > 0) {
                data.forEach(function (item) {
                    rows += `
                        <tr>
                            <td>${sr++}</td>
                            <td>${item.installment_title ?? ""}</td>
                            <td>${item.month ?? ""} - ${item.year ?? ""}</td>
                            <td>${
                                item.amount
                                    ? Number(item.amount).toLocaleString()
                                    : ""
                            }</td>
                            <td>${
                                item.paid_amount
                                    ? Number(item.paid_amount).toLocaleString()
                                    : "0"
                            }</td>
                            <td>${
                                item.remaining_amount
                                    ? Number(
                                          item.remaining_amount
                                      ).toLocaleString()
                                    : "0"
                            }</td>
                            <td>${item.due_date ?? ""}</td>
                            <td>${item.status ?? ""}</td>
                            <td>
                                <button 
                                    class="btn btn-success pay-btn" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#payModal"
                                    data-title="${item.installment_title ?? ""}"
                                    data-status="${item.status ?? ""}"
                                    data-due="${item.due_date ?? ""}"
                                    data-id="${item.id}"
                                    data-amount="${item.amount ?? 0}"
                                    data-paid="${item.paid_amount ?? 0}"
                                    data-remaining="${
                                        item.remaining_amount ?? 0
                                    }"
                                    data-late="${
                                        item.booking.late_payment_penalty ?? 0
                                    }">
                                    Pay Amount
                                </button>
                            </td>
                        </tr>
                    `;
                });
            } else {
                rows = `<tr><td colspan="9" class="text-center">No records found</td></tr>`;
            }

            $("#remainingInstalment").html(rows);
        },
    });
}
