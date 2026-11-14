@extends('layouts.public')

@section('title', 'Page Expired (419) - Uhalisia wa Tanzania')

@section('public_content')
<div class="container py-5 text-center">
    <div class="row justify-content-center">
        <div class="col-md-7 col-lg-6">
            <div class="card border-0 shadow-sm p-5 bg-white">
                <div class="display-1 fw-bold text-warning mb-2 brand-font">419</div>
                <h2 class="h4 brand-font text-uwt-dark mb-3">Session Expired</h2>
                <p class="text-muted mb-4">
                    Your portal session or security token has timed out due to inactivity. Please refresh the page and try your submission again.
                </p>
                <div class="d-flex justify-content-center gap-2">
                    <button onclick="window.location.reload();" class="btn btn-uwt-primary">
                        <i class="bi bi-arrow-clockwise me-1"></i> Refresh Page
                    </button>
                    <a href="{{ route('admin.login') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-box-arrow-in-right me-1"></i> Staff Login
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
