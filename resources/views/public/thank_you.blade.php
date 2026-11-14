@extends('layouts.public')

@section('title', 'Asante Sana! We Received Your Safari Inquiry')

@section('public_content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6 text-center">

            <div class="card border-0 shadow-sm p-4 p-md-5">
                <div class="mb-4">
                    <div class="d-inline-flex align-items-center justify-content-center rounded-circle bg-success-subtle text-success p-4 mb-3" style="width: 90px; height: 90px;">
                        <i class="bi bi-check-circle-fill display-4"></i>
                    </div>
                    <span class="badge bg-warning text-dark text-uppercase fw-bold d-block mx-auto mb-2" style="width: fit-content;">Asante Sana!</span>
                    <h1 class="h3 brand-font text-uwt-dark mb-2">Safari Inquiry Received!</h1>
                    <p class="text-muted">
                        Thank you{{ session('lead_name') ? ', ' . session('lead_name') : '' }}. Your inquiry has been dispatched directly to our Arusha safari planning office.
                    </p>
                </div>

                <div class="card bg-cream-light border-0 p-3 mb-4 text-start">
                    <h6 class="fw-bold text-uwt-primary mb-2"><i class="bi bi-clock-history me-1"></i> What happens next?</h6>
                    <ol class="small text-muted mb-0 ps-3">
                        <li class="mb-1">We have sent an automated confirmation to your email.</li>
                        <li class="mb-1">Our safari specialist is checking lodge availability and park permits for your dates.</li>
                        <li>Within <strong>24 hours</strong>, we will email you a complete day-by-day quote with USD pricing.</li>
                    </ol>
                </div>

                <p class="small text-muted mb-4">
                    Need immediate answers or want to chat via WhatsApp?<br>
                    <a href="https://wa.me/255784590722" target="_blank" class="fw-bold text-success text-decoration-none">
                        <i class="bi bi-whatsapp me-1"></i> Chat on WhatsApp (+255 784 590 722)
                    </a>
                </p>

                <div class="d-flex justify-content-center gap-2">
                    <a href="https://www.uhalisiawatanzania.com" class="btn btn-uwt-primary">
                        <i class="bi bi-globe me-1"></i> Return to Website
                    </a>
                    <a href="{{ route('book.show') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-plus-circle me-1"></i> Submit Another Request
                    </a>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
