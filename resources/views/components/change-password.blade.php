<!-- Password Strength -->
<link rel="stylesheet" href="{{ asset('plugins/passwordStrength/passwordStrength.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/toastr/toastr.min.css') }}">

<div class="modal fade" id="updatePasswordModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">{{ __('lang.CHANGE_PASSWORD') }}</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="">
                    <div class="form-group">
                        <label class="required">{{ __('lang.CURRENT_PASSWORD') }}</label>
                        <div class="input-group">
                            <input type="password" id="currentPassword" name="current_password"
                                oninput="validateCurrentPassword(this)" class="form-control"
                                placeholder="Current Password" required>
                            <div class="input-group-append">
                                <a class="input-group-text showPassword"><span class="fas fa-eye"></span></a>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="required">{{ __('lang.NEW_PASSWORD') }}</label>
                        <div class="input-group">
                            <input type="password" id="newPassword" name="password" class="newPassword form-control"
                                placeholder="Enter New Password" required>
                            <div class="input-group-append">
                                <a class="input-group-text showPassword"><span class="fas fa-eye"></span></a>
                            </div>
                        </div>
                        <p id="passwordPolicy2"></p>
                        <div class="strength-bar">
                            <div id="strength2">
                                <p id="strength-text2"></p>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="required">{{ __('lang.CONFIRM_PASSWORD') }}</label>
                        <div class="input-group">
                            <input type="password" id="confirmNewPassword" name="confirm_password"
                                class="confirmNewPassword form-control" placeholder="Confirm Password" required>
                            <div class="input-group-append">
                                <a class="input-group-text showPassword"><span class="fas fa-eye"></span></a>
                            </div>
                        </div>
                    </div>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="reset" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="submit" id="submitPassword" class="submit btn btn-primary">Save
                    changes</button>
            </div>
        </div>
        </form>
    </div>
    <!-- /.modal-content -->
</div>
<!-- /.modal-dialog -->
<!-- jQuery -->
<script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('plugins/toastr/toastr.min.js') }}"></script>
<script src="{{ asset('plugins/scripts/checkPasswordStrength.js?v=' . config('miscConstant.JS_VERSION')) }}"></script>
<script>
    $(document).ready(function() {
        $('#updatePasswordModal').on('show.bs.modal', function(event) {
            const button = $(event.relatedTarget);
            const userId = button.data('userid');
            $(this).data('userid', userId);
        });

        $(document).on("click", "#submitPassword", function(e) {
            e.preventDefault();
            const userId = $('#updatePasswordModal').data('userid');
            console.log(userId);
            const currentPassword = $('#currentPassword').val();
            const newPassword = $('#newPassword').val();
            const confirmNewPassword = $('#confirmNewPassword').val();
            if (currentPassword === '' || newPassword === '' || confirmNewPassword === '') {
                alert('Password fields can not be empty');
                return;
            }
            if (newPassword != confirmNewPassword) {
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
                    confirm_password: confirmNewPassword
                },
                success: function(response) {
                    $("#updatePasswordModal form")[0].reset();
                    $("#updatePasswordModal").find(".strength-bar").hide();
                    $("#updatePasswordModal").modal("hide");
                    toastr.options = {
                        closeButton: true,
                        progressBar: true,
                    };
                    toastr.success("Password Changed Successfully",
                        "Success!");
                },
                error: function(error) {
                    toastr.error("Password Could Not Change", "Error!");
                },
            });
        });
    });
</script>
