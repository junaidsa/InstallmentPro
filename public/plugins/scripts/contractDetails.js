$(document).ready(function () {
    $(".select2").select2();
    if ($("#accountDD").val()) {
        $("#accountDD").trigger("change");
    }
    $(".print-sections-select").select2({
        placeholder: "Select sections to include",
        allowClear: true,
    });

    function loadBookings(customerId, selectedBookingId = null) {
        if (customerId) {
            $.ajax({
                url: "booking/" + customerId,
                type: "GET",
                success: function (data) {
                    let bookingSelect = $("#bookingDD");
                    bookingSelect.empty();
                    bookingSelect.append('<option value="">Select</option>');

                    $.each(data, function (_key, booked) {
                        bookingSelect.append(
                            `<option value="${booked.id}">${booked.property_type} (${booked.product.product_name})</option>`
                        );
                    });

                    if (selectedBookingId) {
                        bookingSelect.val(selectedBookingId).trigger("change");
                    }
                },
            });
        }
    }
    $("#accountDD").on("change", function () {
        loadBookings($(this).val());
    });
    let accountId = $("#accountDD").val();
    let bookingId = $("#bookingDD").data("selected");
    if (accountId) {
        loadBookings(accountId, bookingId);
    }
});

function printReceipt(bookingId) {
    var printContents = document.getElementById(
        "receipt-" + bookingId
    ).innerHTML;
    var originalContents = document.body.innerHTML;
    var tempDiv = document.createElement("div");
    tempDiv.innerHTML = printContents;

    var selectElement = document.querySelector(".print-sections-select");
    var selectedSections = Array.from(selectElement.selectedOptions).map(
        (option) => option.value
    );
    var sectionMapping = {
        "customer-info": ".customer-info-section",
        "guarantor-info": ".guarantor-info-section",
        "booking-info": ".booking-info-section",
        "payment-detail": ".payment-detail-section",
        signature: ".signature-section",
    };
    for (var sectionKey in sectionMapping) {
        if (selectedSections.indexOf(sectionKey) === -1) {
            var section = tempDiv.querySelector(sectionMapping[sectionKey]);
            if (section) section.style.setProperty("display", "none", "important");
        }
    }

    printContents = tempDiv.innerHTML;
    var printWindow = window.open("", "_blank");
    printWindow.document.write("<html><head><title>Contract Details</title>");
    printWindow.document.write(
        '<link rel="stylesheet" href="' +
            window.location.origin +
            '/dist/css/customerLedger.css">'
    );
    printWindow.document.write("</head><body>");
    printWindow.document.write(printContents);
    printWindow.document.write("</body></html>");
    printWindow.document.close();
    printWindow.onload = function () {
        setTimeout(function () {
            printWindow.print();
        }, 500);
    };
}
function printContainer() {
    const container = document.querySelector(".container.my-4");
    const originalBody = document.body.innerHTML;
    document.body.innerHTML = container.outerHTML;
    window.print();
    document.body.innerHTML = originalBody;
}
function thermalPrint(bookingId) {
    const url = `/contractDetailsPrint/${bookingId}`;
    const printWindow = window.open(url, "_blank", "width=400,height=600");
    if (printWindow) {
        printWindow.focus();
    }
}
function thermalPrintA5(bookingId) {
    const url = `/contractDetailsPrintA5/${bookingId}`;
    const printWindow = window.open(url, "_blank", "width=800,height=1000");
    if (printWindow) {
        printWindow.focus();
    }
}
