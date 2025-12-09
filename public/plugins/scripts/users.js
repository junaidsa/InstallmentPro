$(document).ready(function () {

    $("#employeeId").change(function () {
        const email = $(this).find("option:selected").data("email");
        $("#email").val(email);
    });

    $("#user_id").change(function () {
        var selectedUserId = $(this).val();
        $.ajax({
            type: "GET",
            url: "/roleManagement-data",
            data: {
                id: selectedUserId,
                dataOnly: true,
            },
            success: function (response) {
                const assignedRoles = JSON.parse(response);
                const rolesDD = $("#role_id");
                rolesDD.empty();
                const ul = $(".select2-selection__rendered:last-child");
                ul.empty();
                let selectedRoles = [];

                allRoles.forEach((role) => {
                    let selected = "";
                    assignedRoles.forEach((assignedRole) => {
                        role.id === assignedRole.id
                            ? (selected = true)
                            : (selected = false);
                        if (selected) {
                            const li = `<li class="select2-selection__choice" title="${role.name}" data-select2-id="${role.id}">
                                <!--<span class="select2-selection_choice_remove" role="presentation">×</span> -->
                                ${role.name}
                                </li>`;
                            ul.append(li);
                            selectedRoles.push(role.id);
                        }
                    });
                    const optionElement = $("<option>", {
                        value: role.id,
                        text: role.name + selected,
                        selected: selected, // Mark as selected if the condition is true
                    });
                    rolesDD.append(optionElement);
                    rolesDD.select2("val", selectedRoles);
                    rolesDD.val(selectedRoles);
                    rolesDD.trigger("change");
                });
            },
        });
    });

    $("#changePasswordModal").on("show.bs.modal", function (event) {
        const button = $(event.relatedTarget);
        const userId = button.data("userid");
        $(this).data("userid", userId);
    });

    $(document).on("click", "#changePassword", function (e) {
        e.preventDefault();
        const userId = $("#changePasswordModal").data("userid");
        const newPassword = $("#createPassword").val();
        const confirmPassword = $("#confirmPassword").val();
        if (newPassword != confirmPassword) {
            alert("Passwords Do not Match");
            return;
        }
        var csrfToken = $('meta[name="csrf-token"]').attr("content");

        $.ajax({
            url: "/changePassword/" + userId,
            type: "POST",
            headers: {
                "X-CSRF-TOKEN": csrfToken,
            },
            data: {
                password: newPassword,
                confirm_password: confirmPassword,
            },
            success: function (response) {
                $("#changePasswordModal form")[0].reset();
                $("#changePasswordModal").find(".strength-bar").hide();
                $("#changePasswordModal").modal("hide");
                toastr.options = {
                    closeButton: true,
                    progressBar: true,
                };
                toastr.success("Password Changed Successfully", "Success!");
            },
            error: function (error) {
                toastr.error("Password Could Not Change", "Error!");
            },
        });
    });

    $("#userTable").DataTable({
        responsive: false,
        autoWidth: false,
        scrollX: true,
    });
});

$.fn.editable.defaults.mode = "inline";

var csrfToken = $('meta[name="csrf-token"]').attr("content");
$(".update").editable({
    url: "/userManagement/1",
    ajaxOptions: {
        method: "POST",
        headers: {
            "X-CSRF-TOKEN": csrfToken,
        },
    },
    error: function (response) {
        if (response.responseJSON.status === "error") {
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
            response.name + " to " + response.value + " Updated successfully.",
            "Success!"
        );
    },
    pk: 1,
    name: "name",
    title: "Enter name",
});

$(".select2").select2();
