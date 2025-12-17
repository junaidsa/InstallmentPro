@php
    $user = Session::get('user');
@endphp

<meta name="csrf-token" content="{{ csrf_token() }}">
<style>
    /* ===== NOTIFICATION DROPDOWN FIX ===== */

/* dropdown width safe */
.nav-item.dropdown .dropdown-menu.content-dd {
    width: 360px;
    max-width: calc(100vw - 20px);
}

/* prevent horizontal overflow */
#notificationList {
    overflow-x: hidden;
}

/* single notification item (backend generated) */
#notificationList > *,
#notificationList a,
#notificationList .dropdown-item {
    display: block;
    white-space: normal !important;
    word-break: break-word !important;
}

/* TITLE (force wrap to 2+ lines) */
#notificationList h6,
#notificationList .fw-semibold {
    white-space: normal !important;
    line-height: 1.3;
    max-width: calc(100% - 28px); /* close icon space */
}

/* DESCRIPTION */
#notificationList p,
#notificationList small,
#notificationList span {
    white-space: normal !important;
    word-break: break-word !important;
}

/* keep close / action icon on right */
#notificationList i,
#notificationList .ti-x,
#notificationList .close {
    float: right;
    margin-left: 6px;
}

/* MOBILE FIX */
@media (max-width: 576px) {
    .nav-item.dropdown .dropdown-menu.content-dd {
        position: fixed !important;
        left: 10px !important;
        right: 10px !important;
        width: auto !important;
    }
}

</style>
<header class="app-header">
    <nav class="navbar navbar-expand-lg navbar-light">

        {{-- LEFT : SIDEBAR TOGGLE --}}
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link sidebartoggler nav-icon-hover ms-n3" href="javascript:void(0)">
                    <i class="ti ti-menu-2"></i>
                </a>
            </li>
        </ul>

        {{-- RIGHT SIDE --}}
        <div class="collapse navbar-collapse justify-content-end">
            <ul class="navbar-nav flex-row align-items-center">

                {{-- ================= LANGUAGE SWITCH (MODERNIZE UI) ================= --}}
                <li class="nav-item dropdown">
                    <a class="nav-link nav-icon-hover" href="javascript:void(0)" data-bs-toggle="dropdown">
                        <img src="{{ asset('dist/images/svgs/icon-flag-' . (auth()->user()->lang ?? 'en') . '.svg') }}"
                            class="rounded-circle object-fit-cover round-20">
                    </a>

                    <div class="dropdown-menu dropdown-menu-end dropdown-menu-animate-up">
                        <form method="POST" action="{{ route('switchLang') }}">
                            @csrf

                            <button class="dropdown-item d-flex align-items-center gap-2"
                                name="locale" value="en">
                                <img src="{{ asset('dist/images/svgs/icon-flag-en.svg') }}" class="round-20">
                                English
                            </button>

                            <button class="dropdown-item d-flex align-items-center gap-2"
                                name="locale" value="ur">
                                🇵🇰 اردو
                            </button>

                            <button class="dropdown-item d-flex align-items-center gap-2"
                                name="locale" value="pa">
                                ਪੰਜਾਬੀ
                            </button>

                            <button class="dropdown-item d-flex align-items-center gap-2"
                                name="locale" value="bn">
                                বাংলা
                            </button>

                            <button class="dropdown-item d-flex align-items-center gap-2"
                                name="locale" value="hi">
                                हिंदी
                            </button>

                            <button class="dropdown-item d-flex align-items-center gap-2"
                                name="locale" value="ar">
                                العربية
                            </button>
                        </form>
                    </div>
                </li>

                {{-- ================= NOTIFICATIONS (MODERNIZE UI) ================= --}}
                <li class="nav-item dropdown ms-1">
                    <a class="nav-link nav-icon-hover" href="javascript:void(0)" data-bs-toggle="dropdown">
                        <i class="ti ti-bell-ringing"></i>
                        <div class="notification bg-primary rounded-circle"></div>
                    </a>

                    <div class="dropdown-menu content-dd dropdown-menu-end dropdown-menu-animate-up">
                        <div class="d-flex align-items-center justify-content-between py-3 px-4">
                            <h6 class="mb-0 fw-semibold">{{ __('lang.Notifications') }}</h6>
                            <span id="markAllRead" class="text-primary"
                                style="cursor:pointer">
                                {{ __('lang.Mark_ALL_AS_READ') }}
                            </span>
                        </div>

                        <div id="notificationList" class="message-body" data-simplebar>
                            {{-- Notifications AJAX se load hongi --}}
                        </div>

                        <div class="py-3 px-4">
                            <a href="{{ url('/') }}" class="btn btn-outline-primary w-100">
                                {{ __('lang.See_ALL_NOTIFICATIONS') }}
                            </a>
                        </div>
                    </div>
                </li>

                {{-- ================= USER PROFILE (MODERNIZE UI) ================= --}}
                <li class="nav-item dropdown ms-3">
                    <a class="nav-link pe-0" href="javascript:void(0)" data-bs-toggle="dropdown">
                        <img src="{{ asset(auth()->user()->profile_image ?? 'profile_pictures/noImg.png') }}"
                            class="rounded-circle" width="35" height="35" alt="User">
                    </a>

                    <div class="dropdown-menu content-dd dropdown-menu-end dropdown-menu-animate-up">
                        <div class="profile-dropdown p-4">

                            <div class="d-flex align-items-center mb-3">
                                <img src="{{ asset(auth()->user()->profile_image ?? 'profile_pictures/noImg.png') }}"
                                    class="rounded-circle" width="60" height="60">
                                <div class="ms-3">
                                    <h6 class="mb-0">{{ auth()->user()->account?->name }}</h6>
                                    <small class="text-muted">
                                        {{ auth()->user()->account?->designation }}
                                    </small>
                                </div>
                            </div>

                            <a href="{{ route('myProfile') }}" class="dropdown-item">
                                <i class="ti ti-user me-2"></i> {{ __('lang.PROFILE') }}
                            </a>

                            <a href="javascript:void(0)" class="dropdown-item"
                                data-bs-toggle="modal"
                                data-bs-target="#updatePasswordModal">
                                <i class="ti ti-lock me-2"></i> {{ __('lang.CHANGE_PASSWORD') }}
                            </a>

                            <a href="{{ route('setting') }}" class="dropdown-item">
                                <i class="ti ti-settings me-2"></i> {{ __('lang.SETTING') }}
                            </a>

                            <div class="dropdown-divider"></div>

                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit"
                                    onclick="return confirm('Are you sure?')"
                                    class="dropdown-item text-danger">
                                    <i class="ti ti-logout me-2"></i> {{ __('lang.LOGOUT') }}
                                </button>
                            </form>

                        </div>
                    </div>
                </li>

            </ul>
        </div>
    </nav>
</header>

