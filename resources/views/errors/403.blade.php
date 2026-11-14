@extends('layouts.public')

@section('title', 'Access Forbidden (403) - Uhalisia wa Tanzania')

@section('public_content')
<div class="container py-5 text-center">
    <div class="row justify-content-center">
        <div class="col-md-7 col-lg-6">
            <div class="card border-0 shadow-sm p-5 bg-white">
                <div class="display-1 fw-bold text-danger mb-2 brand-font">403</div>
                <h2 class="h4 brand-font text-uwt-dark mb-3">Restricted Safari Zone</h2>
                <p class="text-muted mb-4">
                    {{ $exception->getMessage() ?: 'You do not have administrative permission to access this restricted section of the safari portal.' }}
                </p>
                <div class="d-flex justify-content-center gap-2">
                    <a href="{{ route('admin.dashboard') }}" class="btn btn-uwt-primary">
                        <i class="bi bi-speedometer2 me-1"></i> Staff Dashboard
                    </a>
                    <a href="{{ url('/') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-house me-1"></i> Public Site
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
