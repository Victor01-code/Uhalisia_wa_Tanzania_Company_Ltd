@extends('layouts.admin')

@section('page_title', 'Add Safari Driver')

@section('admin_content')
<div class="container-fluid p-0" style="max-width: 800px;">
    <div class="d-flex align-items-center gap-3 mb-4">
        <a href="{{ route('admin.drivers.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left"></i> Drivers
        </a>
        <div>
            <h2 class="h4 brand-font text-uwt-dark mb-0">Add Safari Driver</h2>
            <p class="text-muted small mb-0">Register a qualified driver for northern/southern safari circuits and transfers.</p>
        </div>
    </div>

    @if($errors->any())
        <div class="alert alert-danger small mb-4">
            <ul class="mb-0 ps-3">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card shadow-sm border-0">
        <div class="card-body p-4">
            <form action="{{ route('admin.drivers.store') }}" method="POST">
                @csrf

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label for="name" class="form-label fw-semibold small">Full Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="name" class="form-control" placeholder="e.g. Joseph Mushi" value="{{ old('name') }}" required>
                    </div>

                    <div class="col-md-6">
                        <label for="phone" class="form-label fw-semibold small">Phone / WhatsApp Number <span class="text-danger">*</span></label>
                        <input type="text" name="phone" id="phone" class="form-control" placeholder="e.g. +255 754 112 233" value="{{ old('phone') }}" required>
                    </div>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label for="licence_number" class="form-label fw-semibold small">Driving Licence Number</label>
                        <input type="text" name="licence_number" id="licence_number" class="form-control" placeholder="e.g. DL-TZ-98421" value="{{ old('licence_number') }}">
                    </div>

                    <div class="col-md-6">
                        <label for="languages" class="form-label fw-semibold small">Spoken Languages</label>
                        <input type="text" name="languages" id="languages" class="form-control" placeholder="e.g. English, Swahili, French" value="{{ old('languages', 'English, Swahili') }}">
                    </div>
                </div>

                <div class="form-check form-switch mb-4">
                    <input class="form-check-input" type="checkbox" name="active" id="active" value="1" {{ old('active', true) ? 'checked' : '' }}>
                    <label class="form-check-label fw-semibold small" for="active">Active & Available for Assignments</label>
                </div>

                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('admin.drivers.index') }}" class="btn btn-outline-secondary">Cancel</a>
                    <button type="submit" class="btn btn-uwt-primary">
                        <i class="bi bi-check2-circle me-1"></i> Save Driver
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
