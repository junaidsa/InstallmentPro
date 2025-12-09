$("#saleRecoveries").DataTable({
    responsive: false,
    autoWidth: false,
    scrollX: true,
    dom: "Bfrtip",
    stateSave: true,
    buttons: [
        {
            extend: "excelHtml5",
            title: "Sale Recovery Sheet",
            text: "Export to Excel",
            className: "btn btn-secondary",
        },
        {
            extend: "pdfHtml5",
            title: "Sale Recovery Sheet",
            text: "Export to PDF",
            className: "btn btn-success",
            orientation: "landscape",
            pageSize: "A4",
            exportOptions: { columns: ":visible" },
        },
    ],
});
$("#RemaingRecoveries").DataTable({
    responsive: false,
    autoWidth: false,
    scrollX: true,
    dom: "Bfrtip",
    stateSave: true,
    buttons: [
        {
            extend: "excelHtml5",
            title: "Not Approved Recoveries Sheet",
            text: "Export to Excel",
            className: "btn btn-secondary",
        },
        {
            extend: "pdfHtml5",
            title: "Not Approved Recoveries Sheet",
            text: "Export to PDF",
            className: "btn btn-success",
            orientation: "landscape",
            pageSize: "A4",
            exportOptions: { columns: ":visible" },
        },
    ],
});

$(document).ready(function () {
    $(".select2").select2();
    $("#selectAllInstallments").change(function () {
        $(".installment-checkbox").prop("checked", $(this).prop("checked"));
        updateSelectionSummary();
    });

    $(document).on("change", ".installment-checkbox", function () {
        updateSelectionSummary();
    });
    function updateSelectionSummary() {
        let selectedCount = 0;
        let totalAmount = 0;
        let groupedByRecoveryMan = {};

        $(".installment-checkbox:checked").each(function () {
            const checkbox = $(this);
            const row = checkbox.closest("tr");

            const installmentId = checkbox.val();
            const paidAmount = parseFloat(checkbox.data("paid")) || 0;
            const recoveryManId = row.data("recovery-man");
            const customer = row.find("td:eq(3)").text();
            const title = row.find("td:eq(4)").text();

            selectedCount++;
            totalAmount += paidAmount;

            if (!groupedByRecoveryMan[recoveryManId]) {
                groupedByRecoveryMan[recoveryManId] = {
                    installments: [],
                    total_amount: 0,
                };
            }

            groupedByRecoveryMan[recoveryManId].installments.push({
                id: installmentId,
                paid_amount: paidAmount,
                customer,
                title,
            });

            groupedByRecoveryMan[recoveryManId].total_amount += paidAmount;
        });
        window.groupedInstallments = groupedByRecoveryMan;
        window.totalRecoveryAmount = totalAmount;
    }
    $("#approveModal").on("shown.bs.modal", function () {
        const netTotal = parseFloat($("#netTotal").val()) || 0;
        $("#givenAmount").val(netTotal);
        let newRemaining = netTotal - netTotal;

        $("#newRemainingAmount").val(newRemaining.toFixed(2));
    });
    $("#approveBtn").on("click", function () {
        if (
            !window.groupedInstallments ||
            Object.keys(window.groupedInstallments).length === 0
        ) {
            toastr.error("Please select at least one installment.");
            return;
        }

        $("#installmentsSummary").html(`
        <div class="d-flex justify-content-between font-weight-bold text-primary">
            <span>Total Recovery Amount</span>
            <span>PKR ${window.totalRecoveryAmount}</span>
        </div>
    `);

        $("#netTotal").val(window.totalRecoveryAmount);
        $("#givenAmount").val("");
        $("#newRemainingAmount").val("");
        $("#approveModal").modal("show");
    });

    $(document).on("input", "#givenAmount", function () {
        const netTotal = parseFloat($("#netTotal").val()) || 0;

        let givenAmount = parseFloat($(this).val()) || 0;

        if (givenAmount < 0) {
            toastr.error("Given amount cannot be negative!");
            $(this).val(0);
            givenAmount = 0;
        }

        if (givenAmount > netTotal) {
            toastr.error("Given amount cannot exceed total amount!");
            $(this).val(netTotal);
            givenAmount = netTotal;
        }
        let newRemaining = netTotal - givenAmount;

        if (newRemaining < 0) newRemaining = 0;

        $("#newRemainingAmount").val(newRemaining.toFixed(2));
    });

    $("#approveForm").on("submit", function (e) {
        e.preventDefault();

        if (
            !window.groupedInstallments ||
            Object.keys(window.groupedInstallments).length === 0
        ) {
            toastr.error("No installments selected.", "Error!");
            return;
        }

        const givenAmount = parseFloat($("#givenAmount").val()) || 0;
        const remainingAmount = parseFloat($("#newRemainingAmount").val()) || 0;
        const netTotal = parseFloat($("#netTotal").val()) || 0;

        if (givenAmount < 0 || remainingAmount < 0 || givenAmount > netTotal) {
            toastr.error("Invalid amounts entered. Given amount cannot exceed total amount.", "Error!");
            return;
        }

        const formData = new FormData(this);
        formData.append(
            "grouped_installments",
            JSON.stringify(window.groupedInstallments)
        );
        const recovery_man_id = $("#recovery_man_id").val();
        formData.append("recovery_man_id", recovery_man_id);
        formData.append("given_amount", givenAmount);
        formData.append("total_amount", window.totalRecoveryAmount);

        $.ajax({
            url: "/saleRecovery",
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            success: function (response) {
                if (response.success) {
                    toastr.success(response.message, "Success!");
                    $("#approveModal").modal("hide");
                    location.reload();
                } else {
                    toastr.error(response.message, "Error!");
                }
            },
            error: function (xhr) {
                const errors = xhr.responseJSON?.errors;
                if (errors) {
                    Object.keys(errors).forEach((key) => {
                        toastr.error(errors[key][0], "Validation Error!");
                    });
                } else {
                    toastr.error("Something went wrong.", "Error!");
                }
            },
        });
    });

    $(document).on("click", ".approve-btn", function () {
        const id = $(this).data("id");
        if (confirm("Are you sure you want to approve this recovery?")) {
            $.ajax({
                url: recveryApprove,
                type: "POST",
                data: { id: id },
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                        "content"
                    ),
                },
                success: function (response) {
                    if (response.success) {
                        toastr.success(response.message, "Success!");
                        location.reload();
                    } else {
                        toastr.error(response.message, "Error!");
                    }
                },
                error: function (xhr) {
                    const errors = xhr.responseJSON?.errors;
                    if (errors) {
                        Object.keys(errors).forEach((key) => {
                            toastr.error(errors[key][0], "Validation Error!");
                        });
                    } else {
                        toastr.error("Something went wrong.", "Error!");
                    }
                },
            });
        }
    });
    $(document).on("click", "#approveAllBtn", function () {
        const recoveryManId = $("#recovery_man_id").val();
        const recoveryDate = $("#recovery_date").val();
        if (recoveryManId === allRecoveryMen) {
            Swal.fire({
                title: "Approve All Recoveries",
                text: "This will approve ALL recoveries for all recovery men. Are you sure?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#28a745",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, approve all!",
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: approveAllUrl,
                        type: "POST",
                        data: {
                            recovery_man_id: recoveryManId,
                            recovery_date: recoveryDate,
                            _token: $('meta[name="csrf-token"]').attr(
                                "content"
                            ),
                        },
                        success: function (response) {
                            if (response.success) {
                                toastr.success(response.message, "Success!");
                                location.reload();
                            } else {
                                toastr.error(response.message, "Error!");
                            }
                        },
                        error: function (xhr) {
                            const errors = xhr.responseJSON?.errors;
                            if (errors) {
                                Object.keys(errors).forEach((key) => {
                                    toastr.error(
                                        errors[key][0],
                                        "Validation Error!"
                                    );
                                });
                            } else {
                                toastr.error("Something went wrong.", "Error!");
                            }
                        },
                    });
                }
            });
        }
    });

    flatpickr("#recovery_date", {
        altInput: true,
        altFormat: "d/m/Y",
        dateFormat: "Y-m-d",
        maxDate: "today",
    });
});
