$(document).ready(function () {
    const table = $("#InvestorIncomeTable").DataTable({
        dom: "Bfrtip",
        responsive: false,
        scrollX: true,
        autoWidth: false,
        columnDefs: [
            {
                targets: "_all",
                className: "text-center align-middle",
            },
            {
                orderable: false,
                targets: 0,
            },
        ],
        buttons: [
            {
                extend: "excelHtml5",
                title: "Investor Income Report",
                text: "Export to Excel",
                className: "btn btn-secondary mb-3",
                exportOptions: {
                    columns: ":not(.noExport)",
                },
            },
            {
                extend: "pdfHtml5",
                title: "Investor Income Report",
                text: "Export to PDF",
                className: "btn btn-success mb-3",
                orientation: "landscape",
                pageSize: "A4",
                exportOptions: {
                    columns: ":not(.noExport)",
                },
                customize: function (doc) {
                    doc.styles.tableHeader.alignment = "center";
                    doc.defaultStyle.alignment = "center";
                    doc.pageMargins = [20, 20, 20, 20];
                },
            },
        ],
    });

    table.columns.adjust().draw();

    const $actionButtons = $("#actionButtons");
    const $cashInForm = $("#cashInForm");
    const $reinvestForm = $("#reinvestForm");
    $(document).on("change", "#selectAll", function () {
        const isChecked = $(this).is(":checked");
        $("#InvestorIncomeTable")
            .find(".investor-checkbox")
            .prop("checked", isChecked);
        toggleButtons();
    });
    $(document).on("change", ".investor-checkbox", function () {
        const total = $("#InvestorIncomeTable").find(
            ".investor-checkbox"
        ).length;
        const checked = $("#InvestorIncomeTable").find(
            ".investor-checkbox:checked"
        ).length;
        $("#selectAll").prop("checked", total === checked);
        toggleButtons();
    });
    table.on("draw", function () {
        $("#selectAll").prop("checked", false);
        toggleButtons();
    });

    function toggleButtons() {
        const anyChecked =
            $("#InvestorIncomeTable").find(".investor-checkbox:checked")
                .length > 0;
        $actionButtons.toggle(anyChecked);
    }

    function getSelectedIds() {
        const ids = [];
        table.$(".investor-checkbox:checked").each(function () {
            ids.push($(this).val());
        });
        return ids;
    }
    $cashInForm.on("submit", function (e) {
        const ids = getSelectedIds();
        if (ids.length === 0) {
            e.preventDefault();
            toastr.error("Please select at least one investor.", "ERROR!");
            return false;
        }
        $("#cashInSelectedIds").val(JSON.stringify(ids));
    });
    $reinvestForm.on("submit", function (e) {
        const ids = getSelectedIds();
        if (ids.length === 0) {
            e.preventDefault();
            toastr.error("Please select at least one investor.", "ERROR!");
            return false;
        }
        $("#reinvestSelectedIds").val(JSON.stringify(ids));
    });

    const modal = new bootstrap.Modal($("#profitModal")[0]);

    $(".distribute-btn").on("click", function () {
        const id = $(this).data("id");
        const name = $(this).data("name");
        const profit = parseFloat($(this).data("profit"));
        $("#modalInvestorId").val(id);
        $("#modalInvestorName").text(name);
        $("#totalAvailableProfit").val(profit);
        $("#modalTotalProfit").text(profit.toLocaleString() + " PKR");
        $("#reinvestedAmount").val(profit);
        $("#cashOutAmount").val(0);
        updateAmountSummary();
        validateAmounts();
        
        modal.show();
    });

    function updateAmountSummary() {
        const totalProfit = parseFloat($("#totalAvailableProfit").val()) || 0;
        const reinvested = parseFloat($("#reinvestedAmount").val()) || 0;
        const cashOut = parseFloat($("#cashOutAmount").val()) || 0;
        const totalUsed = reinvested + cashOut;
        $("#summaryTotal").text(totalProfit.toLocaleString() + " PKR");
        $("#summaryReinvested").text(reinvested.toLocaleString() + " PKR");
        $("#summaryCashOut").text(cashOut.toLocaleString() + " PKR");
        if (totalUsed > 0) {
            $("#amountSummary").show();
        } else {
            $("#amountSummary").hide();
        }
        if (totalUsed > totalProfit) {
            const excess = totalUsed - totalProfit;
            $("#warningMessage").text(`Total amount exceeds available profit by PKR ${excess.toLocaleString()}. Please adjust your inputs.`);
            $("#amountWarning").removeClass('alert-warning').addClass('alert-danger').show();
        } else if (totalUsed < totalProfit) {
            const remaining = totalProfit - totalUsed;
            $("#warningMessage").text(`PKR ${remaining.toLocaleString()} will remain undistributed.`);
            $("#amountWarning").removeClass('alert-danger').addClass('alert-warning').show();
        } else {
            $("#amountWarning").hide();
        }
    }

    function validateAmounts() {
        const totalProfit = parseFloat($("#totalAvailableProfit").val()) || 0;
        const reinvested = parseFloat($("#reinvestedAmount").val()) || 0;
        const cashOut = parseFloat($("#cashOutAmount").val()) || 0;
        const totalUsed = reinvested + cashOut;
        const isValid = totalUsed > 0 && totalUsed <= totalProfit &&
                       reinvested >= 0 && cashOut >= 0 &&
                       (reinvested > 0 || cashOut > 0);
        
        $("#proceedBtn").prop('disabled', !isValid);
        if (cashOut > totalProfit) {
            $("#cashOutAmount").addClass('is-invalid');
        } else {
            $("#cashOutAmount").removeClass('is-invalid');
        }
    }

    function recalculateReinvested() {
        const totalProfit = parseFloat($("#totalAvailableProfit").val()) || 0;
        const cashOut = parseFloat($("#cashOutAmount").val()) || 0;
        let safeCashOut = Math.max(0, cashOut);
        if (safeCashOut > totalProfit) safeCashOut = totalProfit;
        if (safeCashOut !== cashOut) {
            $("#cashOutAmount").val(safeCashOut);
        }
        const reinvested = Math.max(0, totalProfit - safeCashOut);
        $("#reinvestedAmount").val(reinvested);
        updateAmountSummary();
        validateAmounts();
    }
    $("#cashOutAmount").on("input", function () {
        recalculateReinvested();
    });
    $("#profitForm").on("submit", function (e) {
        const totalProfit = parseFloat($("#totalAvailableProfit").val()) || 0;
        const reinvested = parseFloat($("#reinvestedAmount").val()) || 0;
        const cashOut = parseFloat($("#cashOutAmount").val()) || 0;
        const totalUsed = reinvested + cashOut;
        
        if (totalUsed <= 0) {
            e.preventDefault();
            toastr.error("Please enter at least one amount to distribute.", "ERROR!");
            return false;
        }
        
        if (totalUsed > totalProfit) {
            e.preventDefault();
            toastr.error("Total amount cannot exceed available profit.", "ERROR!");
            return false;
        }
        
        if (reinvested < 0 || cashOut < 0) {
            e.preventDefault();
            toastr.error("Amounts cannot be negative.", "ERROR!");
            return false;
        }
        $("#proceedBtn").html('<i class="fas fa-spinner fa-spin mr-1"></i>Processing...').prop('disabled', true);
    });
});
