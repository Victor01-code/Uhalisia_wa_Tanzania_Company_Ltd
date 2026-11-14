@extends('layouts.public')

@section('title', 'System Issue (500) - Uhalisia wa Tanzania')

@section('public_content')
<div class="container py-5 text-center">
    <div class="row justify-content-center">
        <div class="col-md-7 col-lg-6">
            <div class="card border-0 shadow-sm p-5 bg-white">
                <div class="display-1 fw-bold text-danger mb-2 brand-font">500</div>
                <h2 class="h4 brand-font text-uwt-dark mb-3">Safari Operations Interrupted</h2>
                <p class="text-muted mb-4">
                    Our technical systems encountered an unexpected internal error. The operations team has been notified.
                </p>
                <div class="d-flex justify-content-center gap-2">
                    <a href="{{ url('/') }}" class="btn btn-uwt-primary">
                        <i class="bi bi-house me-1"></i> Return Home
                    </a>
                    <a href="https://wa.me/255784590722" class="btn btn-outline-success">
                        <i class="bi bi-whatsapp me-1"></i> Contact Support
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
