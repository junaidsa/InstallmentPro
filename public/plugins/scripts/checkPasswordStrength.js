$(document).ready(function () {
    $("#newPassword").on("input", function () {
        checkPasswordStrength(
            "newPassword",
            "strength2",
            "strength-text2",
            "passwordPolicy2",
            "submitPassword"
        );
    });
    $("#password").on("input", function () {
        checkPasswordStrength(
            "password",
            "strength",
            "strength-text",
            "passwordPolicy",
            "submit"
        );
    });
    $("#createPassword").on("input", function () {
        checkPasswordStrength(
            "createPassword",
            "strength3",
            "strength-text3",
            "passwordPolicy3",
            "changePassword"
        );
    });
    $(".showPassword")
        .off("click")
        .on("click", function () {
            var parentFormGroup = $(this).closest(".input-group");
            var password = parentFormGroup.find(".form-control");
            var icon = $(this).find("span");

            if (password.attr("type") === "password") {
                password.attr("type", "text");
                icon.removeClass("fa-eye").addClass("fa-eye-slash");
            } else {
                password.attr("type", "password");
                icon.removeClass("fa-eye-slash").addClass("fa-eye");
            }
        });
});

function checkPasswordStrength(
    passwordFieldId,
    strengthBarId,
    strengthTextId,
    passwordPolicyId,
    submitBtnId
) {
    const password = $("#" + passwordFieldId).val();
    const strengthBar = $("#" + strengthBarId);
    const strengthText = $("#" + strengthTextId);
    const passwordPolicy = $("#" + passwordPolicyId);
    const submitBtn = $("#" + submitBtnId);

    $(".strength-bar").show();

    // Check password strength criteria
    const hasUpperCase = /[A-Z]/.test(password);
    const hasLowerCase = /[a-z]/.test(password);
    const hasNumber = /[0-9]/.test(password);
    let strength = 0;

    if (password.length >= 8) {
        strength++;
    }
    if (hasUpperCase) {
        strength++;
    }
    if (hasLowerCase) {
        strength++;
    }
    if (hasNumber) {
        strength++;
    }
    const strengthPercentage = (strength / 4) * 100;
    strengthBar.css("width", strengthPercentage + "%");
    if (strength === 0) {
        strengthBar.removeClass();
        strengthText.text("");
        passwordPolicy.text("");
        submitBtn.prop("disabled", true);
    } else if (strength <= 2) {
        strengthBar.removeClass();
        strengthBar.addClass("weak");
        strengthText.text("Weak");
        passwordPolicy.text(
            "Password should contain atleast 8 characters including one uppercase and one numeric"
        );
        submitBtn.prop("disabled", true);
    } else if (strength === 3) {
        strengthBar.removeClass();
        strengthBar.addClass("good");
        strengthText.text("Good");
        passwordPolicy.text(
            "Password should contain atleast 8 characters including one uppercase and one numeric"
        );
        submitBtn.prop("disabled", false);
    } else {
        strengthBar.removeClass();
        strengthBar.addClass("strong");
        strengthText.text("Strong");
        passwordPolicy.text(
            "Password should contain atleast 8 characters including one uppercase and one numeric"
        );
        submitBtn.prop("disabled", false);
    }
}

function validateCurrentPassword(target) {
    const currentPassword = target.value;
    var submitBtn = $("#submitPassword");
    var csrfToken = $('meta[name="csrf-token"]').attr("content");
    $.ajax({
        url: "/validateCurrentPassword",
        method: "POST",
        headers: {
            "X-CSRF-TOKEN": csrfToken,
        },
        data: {
            password: currentPassword,
        },
        success: function (response) {
            if (!response.valid) {
                $("#currentPassword").addClass("is-invalid");
                submitBtn.prop("disabled", true);
            } else {
                $("#currentPassword").removeClass("is-invalid");
                submitBtn.prop("disabled", false);
            }
        },
    });
}
