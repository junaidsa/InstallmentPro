<aside class="left-sidebar">
    <div>
        <!-- ================= Brand ================= -->
        <div class="brand-logo d-flex align-items-center justify-content-between">
            <a href="{{ route('dashboard') }}" class="text-nowrap logo-img">
                <img src="{{ asset('dist/images/logos/dark-logo.svg') }}" class="dark-logo" width="180" alt="">
                {{-- <img src="{{ asset('dist/images/logos/light-logo.svg') }}" class="light-logo" width="180" alt=""> --}}
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
                                                <i class="ti ti-circleg"></i>
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
