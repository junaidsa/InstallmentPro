$(".select2").select2();

$(document).ready(function () {

    
    $("#userTabsTable").DataTable({
        responsive: true,
        autoWidth: false,
    });

    $("#screenId").on("change", function () {
        const selectedOption = $(this).find(':selected');
        const screen = selectedOption.data('screen');
        const tabs = screen.screen_tabs;

        $('#tabId').empty();
        $("#tabId").append(`<option value="" disabled>Select</option>`);
        tabs.forEach(item => {
            $("#tabId").append(`<option value="${item.id}">${item.name}</option>`)
        });
    });

    let tomorrow = new Date();
    tomorrow.setDate(tomorrow.getDate() + 1);

    $(".datepicker")
        .datepicker({
            format: "yyyy-mm-dd",
            autoclose: true,
            todayHighlight: true,
            startDate: tomorrow,
        })
        .on("changeDate", function (e) {
            var newValue = e.format();
            var pk = $(this).closest(".update").data("pk");
            var name = $(this).closest(".update").data("name");
            updateValue(pk, name, newValue);
        });

    $("#roleNameForm").submit(function (e) {
        e.preventDefault();
        const name = $("#name").val();

        const regex = /^[A-Za-z\s]+$/;
        if (!regex.test(name)) {
            $("#roleMessage").html(
                "<span class='text-danger'>'The Role Name should not contain digits and only allow spaces as special characters.'</span>"
            );
        } else {
            this.submit();
        }
    });

    $("#userTable").DataTable({
        responsive: true,
        autoWidth: false,
    });
});

function updateValue(pk, name, value) {
    var csrfToken = $('meta[name="csrf-token"]').attr("content");
    $.ajax({
        url: "/roles",
        method: "PUT",
        headers: {
            "X-CSRF-TOKEN": csrfToken,
        },
        data: {
            pk: pk,
            name: name,
            value: value,
        },
        error: function (response) {
            if (response.responseJSON && response.responseJSON.status === "error") {
                toastr.options = {
                    closeButton: true,
                    progressBar: true,
                };
                var errorMessage = response.responseJSON.message;
                toastr.error(errorMessage, "Error!");
            }
        },
        success: function (response) {
            toastr.options = {
                closeButton: true,
                progressBar: true,
            };
            toastr.success(
                response.name + " to " + response.value + " updated successfully.",
                "Success!"
            );
        }
    });
}


$(document).on("click", ".toggle-icon", function () {
    var icon = $(this).find(".circle-icon");
    if (icon.text() === "+") {
        icon.text("_").css({
            "background-color": "red",
            "line-height": "5px",
        });
    } else {
        icon.text("+").css({
            "background-color": "#007bff",
            "line-height": "20px",
        });
    }
});

$(document).on('click', '#roleDelete', function () {
    const row = $(this).closest("tr");
    const roleId = $(this).data('id');

    if (confirm('Are you sure you want to delete this role?')) {
        $.ajax({
            url: '/roles/' + roleId,
            type: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function (response) {
                toastr.success(response.message, 'Success');
                row.hide();
            },
            error: function (xhr) {
                const res = xhr.responseJSON;
                toastr.error(res?.message || 'Something went wrong while deleting.', 'Error');
            }
        });
    }
});

$(document).on('click', '#permissionDelete', function () {
    const row = $(this).closest("tr");
    const userId = $(this).data('id');

    if (confirm('Are you sure you want to delete this Permission?')) {
        $.ajax({
            url: '/permission/' + userId,
            type: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function (response) {
                toastr.success(response.message, 'Success');
                row.hide();
            },
            error: function (xhr) {
                const res = xhr.responseJSON;
                toastr.error(res?.message || 'Something went wrong while deleting.', 'Error');
            }
        });
    }
});
