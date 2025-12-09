<script>
    @if (Session::has('success'))
        toastr.options = {
            "closeButton": true,
            "progressBar": true
        }
        toastr.success("{{ session('success') }}", 'Success!');
    @endif

    @if (Session::has('error'))
        toastr.options = {
            "closeButton": true,
            "progressBar": true
        };
        toastr.error(@json(session('error')), 'Error!');
    @endif

    @if (Session::has('info'))
        toastr.options = {
            "closeButton": true,
            "progressBar": true
        }
        toastr.info("{{ session('info') }}", 'Info!');
    @endif

    @if (Session::has('warning'))
        toastr.options = {
            "closeButton": true,
            "progressBar": true
        }
        toastr.warning("{{ session('warning') }}", 'Warning!');
    @endif
    @auth

    function loadNotifications() {
        $.ajax({
            url: '/notifications',
            method: 'GET',
            success: function(data) {
                const count = data.count || 0;
                if (count > 0) {
                    $('#notificationBadge').text(count).show();
                } else {
                    $('#notificationBadge').hide();
                }
                const container = $('#notificationList');
                container.empty();

                if (data.notifications.length > 0) {
                    data.notifications.forEach(notify => {
                        container.append(`
    <a href="${notify.url}" class="dropdown-item">
        <div class="media align-items-center">
            <div class="mr-3">
                <i class="fas fa-user text-success"></i>
            </div>
            <div class="media-body">
                <h3 class="dropdown-item-title mb-1 d-flex justify-content-between align-items-center">
                    ${notify.title}
                    <span class="float-right text-sm text-secondary">
                        <i class="fas fa-times mark-read" data-id="${notify.id}" style="cursor:pointer;"></i>
                    </span>
                </h3>
                <p class="text-sm mb-1">${notify.message ?? ''}</p>
                <p class="text-sm text-muted mb-0">
                    <i class="far fa-clock mr-1"></i> ${notify.created_at_formatted}
                </p>
            </div>
        </div>
    </a>
    <div class="dropdown-divider"></div>
    `);
                    });

                }
            }
        });
    }
    loadNotifications();
    showNotificationList()
    setInterval(loadNotifications, 60000);
    $('#notificationToggle').on('click', function() {
        loadNotifications();
    });

    $(document).on("click", ".filter-btn", function(e) {
        e.preventDefault();
        let module = $(this).data("type");
        $(".filter-btn").removeClass("active");
        $(this).addClass("active");
        showNotificationList(module, 1);
    });
    $(document).on("click", ".pagination a", function(e) {
        e.preventDefault();
        let page = $(this).attr("href").split("page=")[1];
        let module = $(".filter-btn.active").data("type") || "All";
        showNotificationList(module, page);
    });

    function showNotificationList(module = "All", page = 1) {
        $.ajax({
            url: "{{ route('notifications.list') }}",
            method: "GET",
            data: {
                module: module,
                page: page
            },
            beforeSend: function() {
                $('#notification-wrapper').html('<div>Loading...</div>');
            },
            success: function(response) {
                $('#notification-wrapper').html(response);
                $(".filter-btn").removeClass("active");
                $(".filter-btn[data-type='" + module + "']").addClass("active");
            },
            error: function() {
                $('#notification-wrapper').html('<div>Error loading notifications.</div>');
            }
        });
    }

    $(document).on("click", "#markAllRead", function() {
        $.ajax({
            url: '/notifications/mark-all-read',
            method: 'POST',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function(res) {
                if (res.success) {
                    loadNotifications();
                }
            }
        });
    });

    $(document).on('click', '#notification-wrapper .pagination a', function(e) {
        e.preventDefault();
        e.stopPropagation();
        const url = $(this).attr('href');
        $.get(url, function(data) {
            $('#notification-wrapper').html(data);
        });
    });
    $(document).on('click', '.mark-read', function(e) {
        e.preventDefault();
        const notificationId = $(this).data('id');

        $.ajax({
            url: "{{ route('notifications.mark') }}",
            method: 'POST',
            data: {
                notification_id: notificationId,
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.success) {
                    $(`.mark-read[data-id="${notificationId}"]`).closest('.dropdown-item').next(
                        '.dropdown-divider').remove();
                    $(`.mark-read[data-id="${notificationId}"]`).closest('.dropdown-item').remove();
                }
            },
            error: function(xhr) {
                toastr.error(xhr.responseText, "Error!");
            }
        });
    });
    @endauth
</script>
