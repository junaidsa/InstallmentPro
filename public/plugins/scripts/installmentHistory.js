$("#transactionHistoryDG").DataTable({
    responsive: true,
    autoWidth: false,
    scrollX: false,
    dom: "Bfrtip",
    stateSave: true,
    buttons: [
        {
            extend: "excelHtml5",
            title: "Installment History Sheet",
            text: "Export to Excel",
            className: "btn btn-secondary",
        },
        {
            extend: "pdfHtml5",
            title: "Installment History Sheet",
            text: "Export to PDF",
            className: "btn btn-success",
            orientation: "landscape",
            pageSize: "A4",
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
            },
        },
    ],
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

function getHistory(bookingId) {
    if (bookingId) {
        $.ajax({
            url: "instalment/history/" + bookingId,
            type: "GET",
            success: function (data) {
                let rows = "";
                let sr = 1;
                if (data.length > 0) {
                    data.forEach(function (item) {
                        let paid = item.paid_amount
                            ? Number(item.paid_amount).toLocaleString()
                            : "-";

                        let remaining = item.remaining_amount
                            ? Number(item.remaining_amount).toLocaleString()
                            : "-";
                        let dueDate = item.due_date
                            ? new Date(item.due_date).toLocaleDateString()
                            : "-";
                        let statusClass = "badge bg-success";
                        if (
                            item.status === "Paid" ||
                            item.status === "FULL_PAY"
                        )
                            statusClass = "badge bg-success";
                        else if (item.status === "Pending")
                            statusClass = "badge bg-warning text-dark";
                        else if (item.status === "Overdue")
                            statusClass = "badge bg-danger";

                        rows += `
                            <tr>
                                <td class="text-center">${sr++}</td>
                                <td>${item.account?.name ?? ""}</td>
                                <td>${item.installment_title ?? ""}</td>
                                <td>${item.month ?? ""} - ${
                            item.year ?? ""
                        }</td>
                                <td class="text-end">${paid}</td>
                                <td class="text-end">${remaining}</td>
                                <td class="text-center">${dueDate}</td>
                                <td class="text-center"><span class="${statusClass}">${
                            item.status ?? ""
                        }</span></td>
                                <td>${item.remarks ?? ""}</td>
                            </tr>
                        `;
                    });
                } else {
                    rows = `<tr><td colspan="9" class="text-center">No records found</td></tr>`;
                }
                $("#tb-history").html(rows);
            },
        });
    } else {
        $("#tb-history").html(
            `<tr><td colspan="9" class="text-center">Select a booking</td></tr>`
        );
    }
}
