@extends('layouts.app')

@section('title', 'Staff Portal Login - Uhalisia wa Tanzania')

@section('content')
<div class="min-vh-100 d-flex flex-column justify-content-center py-5 bg-cream-bg">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-7 col-lg-5 col-xl-4">

                <!-- Header / Logo -->
                <div class="text-center mb-4">
                    <img src="{{ asset('images/logo.png') }}" alt="Uhalisia Logo" style="height: 64px;" class="rounded bg-white p-1 shadow-sm mb-2">
                    <h2 class="h4 brand-font text-uwt-dark mb-1">Uhalisia wa Tanzania</h2>
                    <p class="text-muted small text-uppercase tracking-wider">Staff Management Portal</p>
                </div>

                <!-- Login Card -->
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4 p-md-4">

                        <h5 class="fw-bold text-uwt-primary mb-3">
                            <i class="bi bi-shield-lock me-1"></i> Sign In to Portal
                        </h5>

                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show small" role="alert">
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        @if($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show small" role="alert">
                                @foreach($errors->all() as $error)
                                    <div>{{ $error }}</div>
                                @endforeach
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        <form action="{{ route('admin.login.submit') }}" method="POST">
                            @csrf

                            <div class="mb-3">
                                <label for="email" class="form-label fw-semibold small">Staff Email Address</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light text-muted"><i class="bi bi-envelope"></i></span>
                                    <input type="email" name="email" id="email" class="form-control"
                                           value="{{ old('email') }}" placeholder="staff@uhalisiawatanzania.com" required autofocus>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label fw-semibold small">Password</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light text-muted"><i class="bi bi-key"></i></span>
                                    <input type="password" name="password" id="password" class="form-control"
                                           placeholder="••••••••" required>
                                </div>
                            </div>

                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <div class="form-check">
                                    <input type="checkbox" name="remember" id="remember" class="form-check-input" {{ old('remember') ? 'checked' : '' }}>
                                    <label for="remember" class="form-check-label small text-muted">Keep me signed in</label>
                                </div>
                                <span class="badge bg-cream text-uwt-dark small">Session: 2 Hours</span>
                            </div>

                            <div class="d-grid mb-3">
                                <button type="submit" class="btn btn-uwt-primary py-2 fw-semibold">
                                    <i class="bi bi-box-arrow-in-right me-1"></i> Sign In to Portal
                                </button>
                            </div>
                        </form>

                    </div>
                </div>

                <!-- Footer note -->
                <div class="text-center mt-4 text-muted small">
                    <p class="mb-1">&copy; {{ date('Y') }} Uhalisia wa Tanzania Company Ltd</p>
                    <a href="{{ url('/') }}" class="text-uwt-primary text-decoration-none">
                        <i class="bi bi-arrow-left me-1"></i> Return to Public Site
                    </a>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection
