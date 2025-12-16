{{-- <link rel="stylesheet" href="{{ asset('plugins/dragula/dragula.min.css') }}" /> --}}
{{-- <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="{{ route('dashboard') }}" class="brand-link">
        <img src="../dist/img/logo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light">Onlease System</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="{{ asset(auth()->user()->profile_image ?? 'profile_pictures/noImg.png') }}"
                    class="img-circle elevation-2" alt="User Image">
            </div>

            <div class="info">
                <a href="#" class="d-block">
                    @php
                        $userName = $user?->user_name ?? 'Unknown User';
                    @endphp
                    {{ $userName }}
                </a>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul id="parent-screen-list" class="nav nav-pills nav-sidebar flex-column" data-widget="treeview"
                role="menu" data-accordion="false">
                <li class="nav-item" data-screen-id="dashboard">
                    <a href="{{ route('dashboard') }}" class="nav-link {{ Request::is('dashboard') ? 'active' : '' }}">
                        <i class="{{ config('menuIconConstants.Dashboard') }}"></i>
                        <p>Dashboard</p>
                    </a>
                </li>

                @foreach ($user->parentScreens as $parentScreen)
                    @php
                        $children = $user->childScreens[$parentScreen->id] ?? [];
                    @endphp

                    @if (count($children) === 1)
                        @php $child = $children[0]; @endphp
                        <li class="nav-item dragable-item" data-screen-id="{{ $parentScreen->id }}">
                            <a href="/{{ $child->directory }}"
                                class="nav-link {{ Request::is($child->directory) ? 'active' : '' }}">
                                <i class="{{ config('menuIconConstants.' . $parentScreen->screen_name) }}"></i>
                                <p>{{ $child->screen_name }}</p>
                            </a>
                        </li>
                    @else
                        <li class="nav-item dragable-item has-treeview" data-screen-id="{{ $parentScreen->id }}">
                            <a href="#" class="nav-link">
                                <i class="{{ config('menuIconConstants.' . $parentScreen->screen_name) }}"></i>
                                <p>
                                    {{ $parentScreen->screen_name }}
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                @foreach ($children as $childScreen)
                                    <li class="nav-item dragable-item">
                                        <a href="/{{ $childScreen->directory }}"
                                            class="nav-link {{ Request::is($childScreen->directory) ? 'active' : '' }}">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>{{ $childScreen->screen_name }}</p>
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </li>
                    @endif
                @endforeach
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
        <div class="os-scrollbar os-scrollbar-horizontal os-scrollbar-unusable os-scrollbar-auto-hidden">
            <div class="os-scrollbar-track">
                <div class="os-scrollbar-handle" style="width: 100%; transform: translate(0px, 0px);"></div>
            </div>
        </div>
        <div class="os-scrollbar os-scrollbar-vertical os-scrollbar-auto-hidden">
            <div class="os-scrollbar-track">
                <div class="os-scrollbar-handle" style="height: 56.4987%; transform: translate(0px, 0px);"></div>
            </div>
        </div>
        <div class="os-scrollbar-corner"></div>
    </div>
    <!-- /.sidebar -->
</aside> --}}

<aside class="left-sidebar">
    <div>
        <!-- ================= Brand ================= -->
        <div class="brand-logo d-flex align-items-center justify-content-between">
            <a href="{{ route('dashboard') }}" class="text-nowrap logo-img">
                <img src="{{ asset('dist/images/logos/dark-logo.svg') }}" class="dark-logo" width="180" alt="">
                <img src="{{ asset('dist/images/logos/light-logo.svg') }}" class="light-logo" width="180" alt="">
            </a>
            <div class="close-btn d-lg-none d-block sidebartoggler cursor-pointer">
                <i class="ti ti-x fs-8 text-muted"></i>
            </div>
        </div>

        <!-- ================= Sidebar ================= -->
        <nav class="sidebar-nav scroll-sidebar" data-simplebar>
            <ul id="sidebarnav">

                <!-- ================= Dashboard ================= -->
                <li class="sidebar-item">
                    <a href="{{ route('dashboard') }}"
                       class="sidebar-link {{ Request::is('dashboard') ? 'active' : '' }}">
                        <span><i class="ti ti-home"></i></span>
                        <span class="hide-menu">Dashboard</span>
                    </a>
                </li>

                <!-- ================= Dynamic Menus ================= -->
                @foreach ($user->parentScreens as $parentScreen)

                    @php
                        $children = $user->childScreens[$parentScreen->id] ?? [];
                        $icon = config('menuIconConstants.' . $parentScreen->screen_name, 'ti ti-circle');
                    @endphp

                    {{-- ===== SINGLE CHILD ===== --}}
                    @if (count($children) === 1)
                        @php $child = $children[0]; @endphp

                        <li class="sidebar-item">
                            <a href="/{{ $child->directory }}"
                               class="sidebar-link {{ Request::is($child->directory) ? 'active' : '' }}">
                                <span><i class="{{ $icon }}"></i></span>
                                <span class="hide-menu">{{ $child->screen_name }}</span>
                            </a>
                        </li>

                    {{-- ===== MULTI CHILD (TREEVIEW) ===== --}}
                    @elseif (count($children) > 1)
                        <li class="sidebar-item">
                            <a class="sidebar-link has-arrow" href="javascript:void(0)" aria-expanded="false">
                                <span><i class="{{ $icon }}"></i></span>
                                <span class="hide-menu">{{ $parentScreen->screen_name }}</span>
                            </a>

                            <ul class="collapse first-level">
                                @foreach ($children as $childScreen)
                                    <li class="sidebar-item">
                                        <a href="/{{ $childScreen->directory }}"
                                           class="sidebar-link {{ Request::is($childScreen->directory) ? 'active' : '' }}">
                                            <div class="round-16 d-flex align-items-center justify-content-center">
                                                <i class="ti ti-home"></i>
                                            </div>
                                            <span class="hide-menu">{{ $childScreen->screen_name }}</span>
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </li>
                    @endif

                @endforeach

            </ul>
        </nav>

        <!-- ================= User Profile ================= -->
        <div class="fixed-profile p-3 bg-light-secondary rounded sidebar-ad mt-3">
            <div class="hstack gap-3">
                <div class="john-img">
                    <img src="{{ asset(auth()->user()->profile_image ?? 'profile_pictures/noImg.png') }}"
                         class="rounded-circle" width="40" height="40" alt="">
                </div>
                <div class="john-title">
                    <h6 class="mb-0 fs-4 fw-semibold">{{ auth()->user()->user_name }}</h6>
                    <span class="fs-2 text-dark">User</span>
                </div>
                <form method="POST" action="{{ route('logout') }}" class="ms-auto">
                    @csrf
                    <button class="border-0 bg-transparent text-primary">
                        <i class="ti ti-power fs-6"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>
</aside>

<!-- jQuery -->
{{-- <script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('plugins/dragula/dragula.min.js') }}"></script>
<script src="{{ asset('plugins/scripts/sidebar.js') }}"></script> --}}
