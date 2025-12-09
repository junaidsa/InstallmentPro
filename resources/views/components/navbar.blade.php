@php
    $user = Session::get('user');
@endphp

<meta name="csrf-token" content="{{ csrf_token() }}">
<nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
        <li class="nav-item d-none d-sm-inline-block">
            <a href="../../dashboard" class="nav-link">Home</a>
        </li>
    </ul>
    <style>
        .notification-list {
            max-height: 300px;
            overflow-y: auto;
            box-sizing: border-box;
            background-color: #fff;
            border-radius: 6px;
        }

        .navbar-badge {
            position: absolute;
            top: 5px;
            right: 10px;
            font-size: 0.7rem;
            padding: 2px 6px;
            border-radius: 50%;
        }

        #notificationDropdown {
            position: absolute;
            z-index: 1050;
        }

        .notification-card {
            position: relative;
            z-index: 1;
        }

        #markAllRead {
            cursor: pointer;
        }
    </style>
    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
        <li class="nav-item dropdown">
            <form id="language-form" method="POST" action="{{ route('switchLang') }}">
                @csrf
                <select name="locale" class="custom-select"
                    onchange="document.getElementById('language-form').submit()">
                    <option value="en" {{ Auth::user() && Auth::user()->lang == 'en' ? 'selected' : '' }}>English
                    </option>
                    <option value="ur" {{ Auth::user() && Auth::user()->lang == 'ur' ? 'selected' : '' }}>اردو
                    </option>
                    <option value="pa" {{ Auth::user() && Auth::user()->lang == 'pa' ? 'selected' : '' }}>ਪੰਜਾਬੀ
                    </option>
                    <option value="bn" {{ Auth::user() && Auth::user()->lang == 'bn' ? 'selected' : '' }}>বাংলা
                    </option>
                    <option value="hi" {{ Auth::user() && Auth::user()->lang == 'hi' ? 'selected' : '' }}>हिंदी
                    </option>
                    <option value="so" {{ Auth::user() && Auth::user()->lang == 'so' ? 'selected' : '' }}>Somali
                    </option>
                    <option value="ro" {{ Auth::user() && Auth::user()->lang == 'ro' ? 'selected' : '' }}>Română
                    </option>
                    <option value="fr" {{ Auth::user() && Auth::user()->lang == 'fr' ? 'selected' : '' }}>Français
                    </option>
                    <option value="it" {{ Auth::user() && Auth::user()->lang == 'it' ? 'selected' : '' }}>Italiano
                    </option>
                    <option value="es" {{ Auth::user() && Auth::user()->lang == 'es' ? 'selected' : '' }}>Español
                    </option>
                    <option value="ar" {{ Auth::user() && Auth::user()->lang == 'ar' ? 'selected' : '' }}>العربية
                    </option>
                </select>
            </form>
        </li>
        </li>
        <li class="nav-item dropdown">
            <a class="nav-link" data-toggle="dropdown" href="#" id="notificationToggle">
                <i class="fas fa-bell bell-icon-pulse fa-2x"></i>
                <span class="badge badge-warning navbar-badge" id="notificationBadge">0</span>
            </a>
            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right notification-card">
                <span class="dropdown-item dropdown-header" id="markAllRead">
                    {{ __('lang.Mark_ALL_AS_READ') }}</span>
                <div class="dropdown-divider"></div>
                <div id="notificationList" class="notification-list"></div>
                <div class="dropdown-divider"></div>
                <a href="{{ url('/') }}"
                    class="dropdown-item dropdown-footer">{{ __('lang.See_ALL_NOTIFICATIONS') }}</a>
            </div>
        </li>
        <li class="ml-4 nav-item dropdown">
            <a class="nav-link p-0 pr-3" data-toggle="dropdown" href="#" aria-expanded="false">
                <img src="{{ asset(auth()->user()->profile_image ?? 'profile_pictures/noImg.png') }}"
                    class="img-circle elevation-2" width="40" height="40" alt="">
            </a>
            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right p-3" style="left: inherit; right: 0px;">
                <h4 class="h4 mb-0"><strong>{{ auth()->user()->account?->name }}</strong></h4>
                <div class="mb-3">{{ auth()->user()->account?->designation }}</div>
                <div class="dropdown-divider"></div>
                <a href="{{ route('myProfile') }}" class="dropdown-item">
                    <i class="fas fa-user mr-2"></i> {{ __('lang.PROFILE') }}
                </a>
                <div class="dropdown-divider"></div>
                <a href="javascript:void(0)" class="dropdown-item" data-toggle="modal"
                    data-target="#updatePasswordModal">
                    <i class="fas fa-lock mr-2"></i> {{ __('lang.CHANGE_PASSWORD') }}
                </a>
                <div class="dropdown-divider"></div>
                <a href="{{ route('setting') }}" class="dropdown-item">
                    <i class="fas fa-cog mr-2"></i> {{ __('lang.SETTING') }}
                </a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <div class="dropdown-divider"></div>
                    <a href="javascript:void(0)"
                        onclick="event.preventDefault(); if(confirm('Are you sure?')) this.closest('form').submit();"
                        class="dropdown-item text-danger">
                        <i class="fas fa-sign-out-alt mr-2"></i> {{ __('lang.LOGOUT') }}
                    </a>
                </form>
            </div>
        </li>

    </ul>
</nav>
<!-- /.navbar -->
