$(".select2").select2();
function initFileInputPreview(selector = ".file-input-preview") {
    $(document).on("change", selector, function () {
        let fileName =
            this.files && this.files.length > 0
                ? this.files[0].name
                : "No file selected";
        $(this)
            .closest(".custom-file")
            .find(".custom-file-label")
            .text(fileName);
    });
}
$(document).ready(function () {
    initFileInputPreview();
    $("#cnic_front").change(function () {
        readURL(this, "#preview_front");
    });
    $("#cnic_back").change(function () {
        readURL(this, "#preview_back");
    });
    $("#accountType").on("change", function () {
        const selectedType = $(this).val();
        $(".userAccount").removeClass("d-none");
        $(".employeeFields").addClass("d-none");
        $("#guarantor").addClass("d-none");
        if (selectedType === customer) {
            $("#cnic").attr("required", true);
            $("#cnic_front").attr("required", true);
            $("#cnic_back").attr("required", true);
            $(".customerAccount").removeClass("d-none");
            $("#guarantor").removeClass("d-none");
        } else if (selectedType === expense) {
            $("#accountBalance").removeClass("d-none");
            $(".userAccount").addClass("d-none");
            $(".customerAccount").addClass("d-none");
            $("#guarantor").addClass("d-none");
            $("input, select")
                .not("#accountType, [name='name']")
                .removeAttr("required");
            $(".userAccount").addClass("d-none");
            $(".employeeFields").addClass("d-none");
            $("input[name^='guarantors[0]']").removeAttr("required");
        } else if (selectedType === employee) {
            $(".customerAccount").addClass("d-none");
            $(".employeeFields").removeClass("d-none");
            $("#designation").attr("required", true);
            $("#wage").attr("required", true);
            $("#wage_type").attr("required", true);
            $("#guarantor").addClass("d-none");
        } else {
            $(".customerAccount").addClass("d-none");
            $("#accountBalance").addClass("d-none");
            $("#guarantor").addClass("d-none");
        }
    });
    let table = $("#accountsTable").DataTable({
        dom: "Bfrtip",
        responsive: false,
        autoWidth: false,
        scrollX: true,
        buttons: [
            {
                extend: "excelHtml5",
                title: "Account Sheet",
                text: "Export to Excel",
                className: "btn btn-secondary",
                exportOptions: {
                    columns: ":not(.noExport)",
                },
            },
            {
                extend: "pdfHtml5",
                title: "Account Sheet",
                text: "Export to PDF",
                className: "btn btn-success",
                orientation: "landscape", // 🔥 make PDF landscape
                pageSize: "A4",
                exportOptions: {
                    columns: ":not(.noExport)",
                },
                customize: function (doc) {
                    // 🔹 Make table full width
                    doc.content[1].table.widths = Array(
                        doc.content[1].table.body[0].length
                    ).fill("*");

                    // 🔹 Smaller font size for fitting data
                    doc.defaultStyle.fontSize = 8;
                    doc.styles.tableHeader.fontSize = 9;

                    // 🔹 Add padding and alignment
                    doc.styles.tableHeader.alignment = "left";
                    doc.styles.tableBodyEven = { alignment: "left" };
                    doc.styles.tableBodyOdd = { alignment: "left" };
                    doc.pageMargins = [20, 20, 20, 20];
                    doc.content[0].text = "Account Sheet";
                    doc.content[0].alignment = "center";
                    doc.content[0].fontSize = 14;
                    doc.content[0].margin = [0, 0, 0, 10];
                },
            },
        ],
    });
});

var wageType = [
    {
        value: hourly,
        text: hourly,
    },
    {
        value: daily,
        text: daily,
    },
    {
        value: weekly,
        text: weekly,
    },
    {
        value: monthly,
        text: monthly,
    },
];
var designations = [
    {
        value: administrator,
        text: administrator,
    },
    {
        value: managerdirector,
        text: managerdirector,
    },
    {
        value: cleaner,
        text: cleaner,
    },
];

$.fn.editable.defaults.mode = "inline";
var csrfToken = $('meta[name="csrf-token"]').attr("content");

$(".update").each(function () {
    var fieldName = $(this).data("name");
    var fieldType = $(this).data("type");

    var updateData = {
        url: "accountManagement",
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
        updateData.source =
            fieldName === "account_type"
                ? accountType
                : fieldName === "designation"
                ? designations
                : wageType;
    }

    $(this).editable(updateData);
});
function mobileFormat(element) {
    let val = element.value.replace(/[^0-9]/g, "");
    if (val.length > 4) {
        element.value = val.slice(0, 4) + "-" + val.slice(4, 11);
    } else {
        element.value = val;
    }
    if (element.value.length > 12) {
        element.value = element.value.slice(0, 12);
    }
}

function cnicFormat(element) {
    let val = element.value.replace(/[^0-9]/g, "");
    if (val.length > 5 && val.length <= 12) {
        element.value = val.slice(0, 5) + "-" + val.slice(5);
    } else if (val.length > 12) {
        element.value =
            val.slice(0, 5) + "-" + val.slice(5, 12) + "-" + val.slice(12, 13);
    } else {
        element.value = val;
    }
}
$("#cnic").on("keyup", function () {
    let cnic = $(this).val().trim();
    let accountType = $("#accountType").val();
    let $saveBtn = $("#saveAccountBtn");
    if (accountType === customer && cnic.length >= 13) {
        $.ajax({
            url: "/accounts/check-cnic",
            type: "POST",
            data: {
                cnic: cnic,
                _token: csrfToken,
            },
            success: function (response) {
                if (response.exists) {
                    toastr.options = {
                        closeButton: true,
                        progressBar: true,
                        timeOut: 3000,
                    };
                    toastr.error("This CNIC already exists!", "ERROR!");
                    $saveBtn.prop("disabled", true);
                } else {
                    $saveBtn.prop("disabled", false);
                }
            },
        });
    } else {
        $saveBtn.prop("disabled", false);
    }
});
$(document).on("click", ".guarantorBtn", function () {
    const accountId = $(this).data("account-id");
    $("#guarantorModal").modal("show");

    $.ajax({
        url: "/guarantor/" + accountId,
        type: "GET",
        success: function (response) {
            if (response.guarantors) {
                response.guarantors.forEach(function (g, index) {
                    $(`[name="guarantors[${index}][id]"]`).val(g.id);
                    $(`[name="guarantors[${index}][name]"]`).val(g.name);
                    $(`[name="guarantors[${index}][father_name]"]`).val(
                        g.father_name
                    );
                    $(`[name="guarantors[${index}][address]"]`).val(g.address);
                    $(`[name="guarantors[${index}][phone]"]`).val(g.phone);
                    $(`[name="guarantors[${index}][cnic]"]`).val(g.cnic);
                    if (g.cnic_media) {
                        // console.log(g.cnic_media);

                        try {
                            const media = JSON.parse(g.cnic_media);
                            const frontUrl = media.front
                                ? `/guarantors/${media.front.split("/").pop()}`
                                : "";
                            const backUrl = media.back
                                ? `/guarantors/${media.back.split("/").pop()}`
                                : "";

                            const frontPreview = $(
                                `#guarantor${index + 1} .img-preview`
                            ).eq(0);
                            const backPreview = $(
                                `#guarantor${index + 1} .img-preview`
                            ).eq(1);

                            if (frontUrl) {
                                frontPreview.attr("src", frontUrl).show();
                            } else {
                                frontPreview.hide();
                            }

                            if (backUrl) {
                                backPreview.attr("src", backUrl).show();
                            } else {
                                backPreview.hide();
                            }
                        } catch (e) {
                            console.error("Invalid JSON for cnic_media:", e);
                        }
                    }
                });
            }
        },
    });
});

function updateGuarantor() {
    const formData = new FormData();
    $("div#guarantor .guarantor-box").each(function (index) {
        $(this)
            .find('input[type="text"]')
            .each(function () {
                const name = $(this).attr("name");
                formData.append(name, $(this).val());
            });
        $(this)
            .find('input[type="file"]')
            .each(function () {
                const fileInput = this.files[0];
                if (fileInput) {
                    formData.append($(this).attr("name"), fileInput);
                }
            });
    });

    $.ajax({
        url: "/guarantor",
        method: "POST",
        headers: { "X-CSRF-TOKEN": csrfToken },
        data: formData,
        processData: false,
        contentType: false,
        success: function (response) {
            toastr.success(response.message, "Success!");
            $("#guarantorModal").modal("hide");
            location.reload();
        },
        error: function (xhr) {
            toastr.error(
                xhr.responseJSON?.error || "Error updating guarantor.",
                "Error!"
            );
        },
    });
}

$(document).on("change", ".custom-file-input", function () {
    const file = this.files[0];
    const previewImg = $(this).closest(".mb-3").find(".img-preview");
    if (file) {
        const reader = new FileReader();
        reader.onload = function (e) {
            previewImg.attr("src", e.target.result).show();
        };
        reader.readAsDataURL(file);
    } else {
        previewImg.hide();
    }
});
$(document).on("change", ".custom-file-input", function () {
    const fileName = $(this).val().split("\\").pop();
    $(this).siblings(".custom-file-label").text(fileName);
});

function updateCustomerDocuments() {
    const formData = new FormData(
        document.getElementById("updateDocumentsForm")
    );
    formData.append("_token", csrfToken);

    $.ajax({
        url: "/customer-documents",
        method: "POST",
        data: formData,
        processData: false,
        contentType: false,
        success: function (response) {
            toastr.success(response.message, "Success!");
            $("#documentsModal").modal("hide");
            location.reload();
        },
        error: function (xhr) {
            const errorMessage =
                xhr.responseJSON?.error || "Error updating documents.";
            toastr.error(errorMessage, "Error!");
        },
    });
}

$(document).on("click", ".viewDocumentsBtn", function () {
    const accountId = $(this).data("account-id");
    $("#updateCustomerId").val(accountId);
    $("#documentsModal").modal("show");

    $.ajax({
        url: "/customer-documents/" + accountId,
        type: "GET",
        success: function (response) {
            let content = '<div class="row">';
            const documentTypes = [
                DOCUMENT_TYPES.CNIC_FRONT,
                DOCUMENT_TYPES.CNIC_BACK,
                DOCUMENT_TYPES.IMAGE,
                DOCUMENT_TYPES.DOCUMENT,
            ];

            documentTypes.forEach(function (type) {
                const displayName =
                    DOCUMENT_DISPLAY_NAMES[type] ||
                    type.replace("_", " ").toUpperCase();

                const existingDoc = response.documents
                    ? response.documents.find(
                          (doc) => doc.document_type === type
                      )
                    : null;

                content += `<div class="col-md-3">
                    <div class="card">
                        <div class="card-header">
                            <h6 class="card-title">${displayName}</h6>
                        </div>
                        <div class="card-body">`;

                if (existingDoc) {
                    if (type === DOCUMENT_TYPES.DOCUMENT) {
                        content += `
                            <div class="text-center mb-2">
                                <a href="/${existingDoc.document_path}" target="_blank" class="btn btn-primary mt-2"><i class="fas fa-file-pdf fa-3x text-danger"></i></a>
                            </div>`;
                    } else {
                        content += `
                            <div class="text-center mb-2">
                                <img src="/${existingDoc.document_path}" alt="${displayName}" class="img-fluid" style="max-height: 70px;">
                            </div>`;
                    }
                } else {
                    content += `<div class="text-center mb-2">
                        <p class="text-muted">No ${displayName.toLowerCase()} uploaded</p>
                    </div>`;
                }
                const acceptType = FILE_ACCEPT_TYPES[type] || "image/*";
                content += `
                    <div class="form-group">
                        <label>Update ${displayName}</label>
                        <div class="input-group">
                            <div class="custom-file">
                                <input type="file" class="custom-file-input file-input-preview" name="${type}" accept="${acceptType}">
                                <label class="custom-file-label">${displayName}</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>`;
            });

            content += "</div>";
            $("#documentsContent").html(content);
            initFileInputPreview();
        },
        error: function (xhr) {
            toastr.error("Error loading documents.", "Error!");
        },
    });
});
