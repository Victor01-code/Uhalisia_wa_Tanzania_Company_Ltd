@extends('layouts.public')

@section('title', 'Client Itinerary Access - Uhalisia wa Tanzania')

@section('public_content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-7 col-lg-5">

            <div class="card border-0 shadow-sm p-4 p-md-5">
                <div class="text-center mb-4">
                    <div class="d-inline-flex align-items-center justify-content-center rounded-circle bg-cream text-uwt-primary p-3 mb-2" style="width: 68px; height: 68px;">
                        <i class="bi bi-key-fill fs-2"></i>
                    </div>
                    <h1 class="h4 brand-font text-uwt-dark mb-1">Access Your Safari Itinerary</h1>
                    <p class="text-muted small">
                        Enter the email address you used when booking with us. We will send a secure, direct link to your live safari itinerary.
                    </p>
                </div>

                @if(session('status'))
                    <div class="alert alert-success alert-dismissible fade show small" role="alert">
                        <i class="bi bi-check-circle-fill me-1"></i> {{ session('status') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if($errors->any())
                    <div class="alert alert-danger small" role="alert">
                        @foreach($errors->all() as $error)
                            <div>{{ $error }}</div>
                        @endforeach
                    </div>
                @endif

                <form action="{{ route('client.request_link') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label for="email" class="form-label fw-semibold small">Your Booking Email Address</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light text-muted"><i class="bi bi-envelope"></i></span>
                            <input type="email" name="email" id="email" class="form-control"
                                   placeholder="e.g. yourname@example.com" value="{{ old('email') }}" required autofocus>
                        </div>
                        <div class="form-text small">No password needed. You will receive an encrypted private link valid for 7 days.</div>
                    </div>

                    <div class="d-grid mb-3">
                        <button type="submit" class="btn btn-uwt-primary py-2 fw-semibold">
                            <i class="bi bi-send me-1"></i> Email Me My Itinerary Link
                        </button>
                    </div>
                </form>

                <div class="text-center text-muted small mt-3 pt-3 border-top">
                    Having trouble? Contact our Arusha office directly on WhatsApp:<br>
                    <a href="https://wa.me/255784590722" class="text-success fw-bold text-decoration-none">
                        <i class="bi bi-whatsapp me-1"></i> +255 784 590 722
                    </a>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
