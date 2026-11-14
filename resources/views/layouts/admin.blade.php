@extends('layouts.app')

@section('body_class', 'bg-cream-bg')

@section('content')
<div class="d-flex" id="wrapper">

    <!-- Admin Sidebar (Offcanvas on Mobile/Tablet < 992px, Static on Desktop >= 992px) -->
    <aside class="admin-sidebar offcanvas-lg offcanvas-start d-flex flex-column flex-shrink-0" id="adminSidebar" tabindex="-1" aria-labelledby="adminSidebarLabel" style="background-color: #2B1B12 !important; width: 265px;">
        <!-- Brand Header -->
        <div class="p-3 border-bottom border-secondary d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center gap-2 overflow-hidden">
                <img src="{{ asset('images/logo.png') }}" alt="UWT Logo" style="height: 40px;" class="rounded bg-white p-1 flex-shrink-0">
                <div class="overflow-hidden">
                    <div class="brand-font fw-bold text-white text-truncate" id="adminSidebarLabel" style="font-size: 0.95rem;">Uhalisia wa Tanzania</div>
                    <small class="text-uwt-gold text-uppercase" style="font-size: 0.7rem; letter-spacing: 0.08em;">Portal Staff</small>
                </div>
            </div>
            <!-- Mobile Close Button (visible only < 992px) -->
            <button type="button" class="btn-close btn-close-white d-lg-none ms-2" data-bs-dismiss="offcanvas" data-bs-target="#adminSidebar" aria-label="Close"></button>
        </div>

        <!-- User Quick Profile -->
        <div class="px-3 py-3 border-bottom border-secondary bg-black bg-opacity-25 d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center gap-2 overflow-hidden">
                <div class="rounded-circle bg-uwt-primary text-white d-flex align-items-center justify-content-center fw-bold flex-shrink-0" style="width: 34px; height: 34px; font-size: 0.85rem;">
                    {{ strtoupper(substr(Auth::user()->name ?? 'U', 0, 1)) }}
                </div>
                <div class="overflow-hidden">
                    <div class="text-white small fw-semibold text-truncate">{{ Auth::user()->name }}</div>
                    <span class="badge bg-warning text-dark text-uppercase" style="font-size: 0.65rem;">
                        {{ Auth::user()->role }}
                    </span>
                </div>
            </div>
            <form action="{{ route('admin.logout') }}" method="POST" class="m-0">
                @csrf
                <button type="submit" class="btn btn-sm btn-link text-white-50 p-1" title="Sign Out">
                    <i class="bi bi-box-arrow-right fs-5"></i>
                </button>
            </form>
        </div>

        <!-- Navigation Menu -->
        <nav class="nav flex-column py-3 flex-grow-1 overflow-y-auto">
            <div class="sidebar-heading">Core Workflow</div>

            <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="bi bi-speedometer2"></i> Dashboard
            </a>

            @if(Auth::user()->hasRole(['admin', 'agent']))
                <a href="{{ route('admin.leads.index') }}" class="nav-link {{ request()->routeIs('admin.leads.*') ? 'active' : '' }}">
                    <i class="bi bi-inbox-fill"></i> Leads & Inquiries
                </a>

                <a href="{{ route('admin.clients.index') }}" class="nav-link {{ request()->routeIs('admin.clients.*') ? 'active' : '' }}">
                    <i class="bi bi-people-fill"></i> Clients
                </a>
            @endif

            <a href="{{ route('admin.bookings.index') }}" class="nav-link {{ request()->routeIs('admin.bookings.*') ? 'active' : '' }}">
                <i class="bi bi-calendar-check-fill"></i> Bookings & Trips
            </a>

            @if(Auth::user()->isAdmin())
                <div class="sidebar-heading">Catalog & Templates</div>

                <a href="{{ route('admin.tours.index') }}" class="nav-link {{ request()->routeIs('admin.tours.*') ? 'active' : '' }}">
                    <i class="bi bi-map-fill"></i> Tour Templates
                </a>
            @endif

            @if(Auth::user()->hasRole(['admin', 'agent']))
                <div class="sidebar-heading">Operations & Fleet</div>

                <a href="{{ route('admin.vehicles.index') }}" class="nav-link {{ request()->routeIs('admin.vehicles.*') ? 'active' : '' }}">
                    <i class="bi bi-truck-front-fill"></i> Vehicles (Cruisers)
                </a>

                <a href="{{ route('admin.drivers.index') }}" class="nav-link {{ request()->routeIs('admin.drivers.*') ? 'active' : '' }}">
                    <i class="bi bi-person-badge-fill"></i> Drivers
                </a>

                <a href="{{ route('admin.guides.index') }}" class="nav-link {{ request()->routeIs('admin.guides.*') ? 'active' : '' }}">
                    <i class="bi bi-binoculars-fill"></i> Safari & Trek Guides
                </a>

                <a href="{{ route('admin.resources.calendar') }}" class="nav-link {{ request()->routeIs('admin.resources.*') ? 'active' : '' }}">
                    <i class="bi bi-calendar-range"></i> Resource Calendar
                </a>
            @endif

            @if(Auth::user()->hasRole(['admin', 'accountant']))
                <div class="sidebar-heading">Accounting & Finance</div>

                <a href="{{ route('admin.payments.index') }}" class="nav-link {{ request()->routeIs('admin.payments.*') ? 'active' : '' }}">
                    <i class="bi bi-cash-stack"></i> Payments
                </a>

                <a href="{{ route('admin.expenses.index') }}" class="nav-link {{ request()->routeIs('admin.expenses.*') ? 'active' : '' }}">
                    <i class="bi bi-receipt"></i> Expenses
                </a>

                <a href="{{ route('admin.exchange-rates.index') }}" class="nav-link {{ request()->routeIs('admin.exchange-rates.*') ? 'active' : '' }}">
                    <i class="bi bi-currency-exchange"></i> Exchange Rates (TZS/USD)
                </a>

                <a href="{{ route('admin.reports.index') }}" class="nav-link {{ request()->routeIs('admin.reports.*') ? 'active' : '' }}">
                    <i class="bi bi-bar-chart-line-fill"></i> Reports & Excel Export
                </a>
            @endif

            @if(Auth::user()->isAdmin())
                <div class="sidebar-heading">Administration</div>

                <a href="{{ route('admin.users.index') }}" class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                    <i class="bi bi-shield-shaded"></i> Staff Accounts
                </a>

                <a href="{{ route('admin.settings.index') }}" class="nav-link {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
                    <i class="bi bi-gear-fill"></i> Portal Settings
                </a>

                <a href="{{ route('admin.reports.audit_logs') }}" class="nav-link {{ request()->routeIs('admin.reports.audit_logs') ? 'active' : '' }}">
                    <i class="bi bi-shield-check"></i> Audit Logs
                </a>
            @endif

            <div class="mt-auto pt-3 border-top border-secondary">
                <a href="{{ url('/') }}" target="_blank" class="nav-link text-white-50">
                    <i class="bi bi-box-arrow-up-right"></i> Open Public Site
                </a>
            </div>
        </nav>
    </aside>

    <!-- Main Content Area -->
    <div class="flex-grow-1 d-flex flex-column min-vh-100 overflow-x-hidden">

        <!-- Top Header Bar -->
        <header class="bg-white border-bottom py-2 px-3 px-md-4 d-flex justify-content-between align-items-center shadow-sm sticky-top">
            <div class="d-flex align-items-center gap-2 gap-md-3 overflow-hidden">
                <!-- Hamburger Toggle Button for Smartphone & Tablet (< 992px) -->
                <button class="btn btn-sm btn-outline-secondary d-lg-none flex-shrink-0" type="button" data-bs-toggle="offcanvas" data-bs-target="#adminSidebar" aria-controls="adminSidebar" aria-label="Toggle navigation">
                    <i class="bi bi-list fs-5"></i>
                </button>
                <h1 class="h6 mb-0 brand-font text-uwt-dark text-truncate" style="font-size: clamp(0.95rem, 2vw, 1.25rem);">@yield('page_title', 'Staff Dashboard')</h1>
                <span class="text-muted d-none d-xl-inline">|</span>
                <span class="text-muted small d-none d-xl-inline">
                    <i class="bi bi-clock me-1"></i> {{ now()->timezone('Africa/Dar_es_Salaam')->format('D, d M Y - H:i') }} (EAT)
                </span>
            </div>

            <div class="d-flex align-items-center gap-2 gap-sm-3 flex-shrink-0">
                <span class="badge bg-cream text-uwt-dark px-2 px-sm-3 py-2 border d-none d-sm-inline-flex align-items-center">
                    <i class="bi bi-currency-exchange text-uwt-primary me-1"></i>
                    1 USD = {{ number_format(\App\Models\ExchangeRate::getRateFor((int)date('Y'), (int)date('n')), 0) }} TZS
                </span>
                <span class="badge bg-cream text-uwt-dark px-2 py-1 border d-inline-flex d-sm-none align-items-center" title="1 USD = {{ number_format(\App\Models\ExchangeRate::getRateFor((int)date('Y'), (int)date('n')), 0) }} TZS">
                    <i class="bi bi-currency-exchange text-uwt-primary me-1"></i> ${{ number_format(\App\Models\ExchangeRate::getRateFor((int)date('Y'), (int)date('n')), 0) }}
                </span>

                <div class="dropdown">
                    <button class="btn btn-sm btn-outline-secondary dropdown-toggle d-flex align-items-center gap-1" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-person-circle"></i>
                        <span class="d-none d-md-inline">{{ Auth::user()->name }}</span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                        <li><span class="dropdown-item-text text-muted small">Signed in: <strong>{{ Auth::user()->name }}</strong></span></li>
                        <li><span class="dropdown-item-text text-muted small">Role: <strong>{{ ucfirst(Auth::user()->role) }}</strong></span></li>
                        <li><hr class="dropdown-divider"></li>
                        @if(Auth::user()->isAdmin())
                            <li><a class="dropdown-item" href="{{ route('admin.settings.index') }}"><i class="bi bi-gear me-2"></i> Settings</a></li>
                        @endif
                        <li>
                            <form action="{{ route('admin.logout') }}" method="POST" class="m-0">
                                @csrf
                                <button type="submit" class="dropdown-item text-danger">
                                    <i class="bi bi-box-arrow-right me-2"></i> Sign Out
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </header>

        <!-- Flash Messages -->
        <div class="px-3 px-md-4 pt-3">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-octagon-fill me-2"></i> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(session('warning'))
                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('warning') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong class="d-block mb-1"><i class="bi bi-exclamation-triangle me-1"></i> Please correct the following errors:</strong>
                    <ul class="mb-0 small ps-3">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
        </div>

        <!-- Main Body -->
        <main class="flex-grow-1 p-3 p-md-4">
            @yield('admin_content')
        </main>

        <!-- Footer -->
        <footer class="bg-white border-top py-3 px-3 px-md-4 text-muted small d-flex flex-column flex-sm-row justify-content-between align-items-center gap-2 text-center text-sm-start">
            <div>
                &copy; {{ date('Y') }} Uhalisia wa Tanzania Company Ltd &bull; Portal v1.0
            </div>
            <div>
                Built for Safari Operations &bull; Arusha, Tanzania
            </div>
        </footer>

    </div>

</div>
@endsection
