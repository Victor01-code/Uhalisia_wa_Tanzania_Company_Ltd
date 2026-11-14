@extends('layouts.public')

@section('title', 'Page Not Found (404) - Uhalisia wa Tanzania')

@section('public_content')
<div class="container py-5 text-center">
    <div class="row justify-content-center">
        <div class="col-md-7 col-lg-6">
            <div class="card border-0 shadow-sm p-5 bg-white">
                <div class="display-1 fw-bold text-uwt-primary mb-2 brand-font">404</div>
                <h2 class="h4 brand-font text-uwt-dark mb-3">Expedition Route Not Found</h2>
                <p class="text-muted mb-4">
                    The trail you are looking for seems to have wandered off the safari track. The page may have moved or the link may be outdated.
                </p>
                <div class="d-flex justify-content-center gap-2">
                    <a href="{{ url('/') }}" class="btn btn-uwt-primary">
                        <i class="bi bi-house me-1"></i> Return Home
                    </a>
                    <a href="{{ route('book.show') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-compass me-1"></i> Plan a Safari
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
