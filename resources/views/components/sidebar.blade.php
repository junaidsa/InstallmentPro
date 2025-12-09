<link rel="stylesheet" href="{{ asset('plugins/dragula/dragula.min.css') }}" />
<aside class="main-sidebar sidebar-dark-primary elevation-4">
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
</aside>
<!-- jQuery -->
<script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('plugins/dragula/dragula.min.js') }}"></script>
<script src="{{ asset('plugins/scripts/sidebar.js') }}"></script>
