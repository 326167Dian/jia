<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>@yield('title', 'Dashboard') - MySIFA Admin</title>
    <link rel="shortcut icon" href="{{ asset('mysifa-ecommerce-site/assets/mysifa-logo.jpg') }}">
    <link href="{{ asset('backend-assets/css/app.min.css') }}?v={{ @filemtime(public_path('backend-assets/css/app.min.css')) }}" rel="stylesheet">
    @stack('styles')
</head>
<body>
    <div class="layout">
        <div class="vertical-layout">
            <!-- Header -->
            <div class="header-text-dark header-nav layout-vertical">
                <div class="header-nav-wrap">
                    <div class="header-nav-left">
                        <div class="header-nav-item desktop-toggle">
                            <div class="header-nav-item-select cursor-pointer">
                                <i class="nav-icon feather icon-menu icon-arrow-right"></i>
                            </div>
                        </div>
                        <div class="header-nav-item mobile-toggle">
                            <div class="header-nav-item-select cursor-pointer">
                                <i class="nav-icon feather icon-menu icon-arrow-right"></i>
                            </div>
                        </div>
                    </div>
                    <div class="header-nav-right">
                        <div class="header-nav-item">
                            <div class="dropdown header-nav-item-select nav-profile">
                                <div class="toggle-wrapper" id="nav-profile-dropdown" data-bs-toggle="dropdown">
                                    <div class="avatar avatar-circle avatar-image" style="width: 35px; height: 35px; line-height: 35px;">
                                        <img src="{{ asset('backend-assets/images/avatars/thumb-1.jpg') }}" alt="">
                                    </div>
                                    <span class="fw-bold mx-1">{{ auth('admin')->user()->name }}</span>
                                    <i class="feather icon-chevron-down"></i>
                                </div>
                                <div class="dropdown-menu dropdown-menu-end">
                                    <div class="nav-profile-header">
                                        <div class="d-flex align-items-center">
                                            <div class="avatar avatar-circle avatar-image">
                                                <img src="{{ asset('backend-assets/images/avatars/thumb-1.jpg') }}" alt="">
                                            </div>
                                            <div class="d-flex flex-column ms-1">
                                                <span class="fw-bold text-dark">{{ auth('admin')->user()->name }}</span>
                                                <span class="font-size-sm">{{ auth('admin')->user()->email }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <form method="POST" action="{{ route('admin.logout') }}">
                                        @csrf
                                        <button type="submit" class="dropdown-item">
                                            <div class="d-flex align-items-center">
                                                <i class="font-size-lg me-2 feather icon-power"></i>
                                                <span>Keluar</span>
                                            </div>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Side Nav -->
            <div class="side-nav vertical-menu nav-menu-light scrollable">
                <div class="nav-logo">
                    <div class="w-100 d-flex justify-content-center">
                        <img src="{{ asset('mysifa-ecommerce-site/assets/mysifa-logo-full.png') }}" style="width:150px;max-width:100%;height:auto;" alt="logo">
                    </div>
                    <div class="mobile-close">
                        <i class="icon-arrow-left feather"></i>
                    </div>
                </div>
                <ul class="nav-menu">
                    <li class="nav-menu-item {{ request()->routeIs('admin.dashboard') ? 'router-link-active' : '' }}">
                        <a href="{{ route('admin.dashboard') }}">
                            <i class="feather icon-home"></i>
                            <span class="nav-menu-item-title">Dashboard</span>
                        </a>
                    </li>
                    <li class="nav-group-title">MANAJEMEN</li>
                    <li class="nav-menu-item {{ request()->routeIs('admin.orders.*') ? 'router-link-active' : '' }}">
                        <a href="{{ route('admin.orders.index') }}">
                            <i class="feather icon-clipboard"></i>
                            <span class="nav-menu-item-title">Pendaftaran Promo</span>
                        </a>
                    </li>
                    <li class="nav-menu-item {{ request()->routeIs('admin.admins.*') ? 'router-link-active' : '' }}">
                        <a href="{{ route('admin.admins.index') }}">
                            <i class="feather icon-users"></i>
                            <span class="nav-menu-item-title">Admin</span>
                        </a>
                    </li>
                    <li class="nav-menu-item {{ request()->routeIs('admin.vouchers.*') ? 'router-link-active' : '' }}">
                        <a href="{{ route('admin.vouchers.index') }}">
                            <i class="feather icon-gift"></i>
                            <span class="nav-menu-item-title">Promo</span>
                        </a>
                    </li>
                    <li class="nav-menu-item {{ request()->routeIs('admin.payment.*') ? 'router-link-active' : '' }}">
                        <a href="{{ route('admin.payment.edit') }}">
                            <i class="feather icon-credit-card"></i>
                            <span class="nav-menu-item-title">Pembayaran</span>
                        </a>
                    </li>
                    <li class="nav-menu-item {{ request()->routeIs('admin.customers.*') ? 'router-link-active' : '' }}">
                        <a href="{{ route('admin.customers.index') }}">
                            <i class="feather icon-user-check"></i>
                            <span class="nav-menu-item-title">Customer</span>
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Content -->
            <div class="content">
                <div class="main">
                    <div class="page-header">
                        <h4 class="page-title">@yield('title', 'Dashboard')</h4>
                    </div>

                    @if (session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif
                    @if (session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif

                    @yield('content')
                </div>

                <div class="footer">
                    <div class="footer-content">
                        <p class="mb-0">© {{ date('Y') }} MySIFA Admin</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('backend-assets/js/vendors.min.js') }}"></script>
    @stack('scripts')
    <script src="{{ asset('backend-assets/js/app.min.js') }}"></script>
</body>
</html>
