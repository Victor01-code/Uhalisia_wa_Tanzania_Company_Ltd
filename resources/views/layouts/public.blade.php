@extends('layouts.app')

@section('body_class', 'bg-cream-bg')

@section('content')
<!-- Top Announcement / Contact Bar -->
<div class="bg-safari-dark py-2 px-3 text-white small border-bottom border-warning">
    <div class="container d-flex flex-wrap justify-content-between align-items-center gap-2">
        <div class="d-flex align-items-center gap-2 gap-md-3">
            <span><i class="bi bi-geo-alt text-uwt-gold me-1"></i> Arusha & Dar es Salaam</span>
            <span class="d-none d-md-inline"><i class="bi bi-telephone text-uwt-gold me-1"></i> +255 784 590 722</span>
            <span class="d-none d-lg-inline"><i class="bi bi-envelope text-uwt-gold me-1"></i> info@uhalisiawatanzania.com</span>
        </div>
        <div class="d-flex align-items-center gap-2 gap-sm-3 ms-auto ms-sm-0">
            <span class="badge bg-warning text-dark fw-bold d-none d-sm-inline-block">Safaris and Tours</span>
            <a href="https://www.uhalisiawatanzania.com" target="_blank" class="text-white text-decoration-none d-none d-sm-inline-block">
                <i class="bi bi-globe me-1"></i> Main Site <i class="bi bi-arrow-up-right small"></i>
            </a>
            <a href="{{ route('client.login') }}" class="text-uwt-gold text-decoration-none fw-semibold">
                <i class="bi bi-person-circle me-1"></i> Client Portal
            </a>
        </div>
    </div>
</div>

<!-- Header with Logo -->
<header class="bg-white shadow-sm py-2 py-sm-3 mb-3 mb-sm-4">
    <div class="container d-flex justify-content-between align-items-center gap-2">
        <a href="{{ url('/') }}" class="d-flex align-items-center gap-2 gap-sm-3 text-decoration-none text-dark overflow-hidden">
            <img src="{{ asset('images/logo.png') }}" alt="Uhalisia wa Tanzania" style="height: clamp(38px, 6vw, 54px); width: auto;" class="rounded flex-shrink-0">
            <div class="overflow-hidden">
                <h1 class="h5 h4-sm mb-0 brand-font text-uwt-dark text-truncate" style="font-size: clamp(1rem, 2.5vw, 1.4rem);">Uhalisia wa Tanzania</h1>
                <small class="text-muted text-uppercase tracking-wider d-none d-sm-block" style="font-size: 0.72rem;">Safaris and Tours &bull; Booking Portal</small>
            </div>
        </a>

        <div class="d-flex align-items-center gap-1 gap-sm-2 flex-shrink-0">
            <a href="{{ route('book.show') }}" class="btn btn-uwt-primary btn-sm px-2 px-sm-3">
                <i class="bi bi-compass me-1"></i> <span class="d-none d-sm-inline">Plan a Safari</span><span class="d-sm-none">Plan</span>
            </a>
            <a href="{{ route('admin.login') }}" class="btn btn-outline-secondary btn-sm d-none d-md-inline-flex" title="Staff Operations Login">
                <i class="bi bi-shield-lock me-1"></i> Staff Login
            </a>
        </div>
    </div>
</header>

<!-- Main Page Content -->
<main class="py-2">
    @yield('public_content')
</main>

<!-- Footer -->
<footer class="bg-safari-dark text-white py-5 mt-5 border-top border-warning">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-5">
                <div class="d-flex align-items-center gap-2 mb-3">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo" style="height: 48px;" class="rounded bg-white p-1">
                    <h5 class="mb-0 text-white brand-font">Uhalisia wa Tanzania</h5>
                </div>
                <p class="text-white-50 small mb-3">
                    Specialist bespoke wildlife safaris, Mount Kilimanjaro expeditions, cultural encounters, and Zanzibar beach holidays. Locally owned and operated with offices in Arusha and Dar es Salaam.
                </p>
                <div class="text-uwt-gold small fw-bold">"Safaris and Tours"</div>
            </div>

            <div class="col-lg-3 col-md-6">
                <h6 class="text-uppercase text-uwt-gold fw-bold mb-3">Contact & Offices</h6>
                <ul class="list-unstyled text-white-50 small mb-0 space-y-2">
                    <li class="mb-2"><i class="bi bi-geo-alt text-uwt-primary me-2"></i> Arusha & Dar es Salaam, Tanzania</li>
                    <li class="mb-2"><i class="bi bi-telephone text-uwt-primary me-2"></i> +255 784 590 722</li>
                    <li class="mb-2"><i class="bi bi-whatsapp text-success me-2"></i> +255 784 590 722</li>
                    <li class="mb-2"><i class="bi bi-envelope text-uwt-primary me-2"></i> info@uhalisiawatanzania.com</li>
                    <li><i class="bi bi-globe text-uwt-primary me-2"></i> www.uhalisiawatanzania.com</li>
                </ul>
            </div>

            <div class="col-lg-4 col-md-6">
                <h6 class="text-uppercase text-uwt-gold fw-bold mb-3">Client & Staff Access</h6>
                <p class="text-white-50 small mb-3">
                    Already booked a trip? Access your personalized day-by-day itinerary, assigned driver/guide contacts, and documents through our private client link.
                </p>
                <div class="d-flex gap-2">
                    <a href="{{ route('client.login') }}" class="btn btn-outline-light btn-sm">
                        <i class="bi bi-key me-1"></i> Client Itinerary Access
                    </a>
                    <a href="{{ route('admin.login') }}" class="btn btn-outline-secondary btn-sm text-white">
                        <i class="bi bi-person-gear me-1"></i> Staff Portal
                    </a>
                </div>
            </div>
        </div>

        <hr class="border-secondary my-4">

        <div class="d-flex flex-wrap justify-content-between align-items-center small text-white-50">
            <div>
                &copy; {{ date('Y') }} Uhalisia wa Tanzania Company Ltd. All rights reserved.
            </div>
            <div>
                Prices quoted in USD &bull; Certified Tanzanian Tour Operator
            </div>
        </div>
    </div>
</footer>
@endsection
