@extends('layouts.admin')

@section('page_title', 'Edit Safari & Mountain Guide')

@section('admin_content')
<div class="container-fluid p-0" style="max-width: 800px;">
    <div class="d-flex align-items-center gap-3 mb-4">
        <a href="{{ route('admin.guides.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left"></i> Guides
        </a>
        <div>
            <h2 class="h4 brand-font text-uwt-dark mb-0">Edit Safari & Mountain Guide</h2>
            <p class="text-muted small mb-0">{{ $guide->name }}</p>
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
            <form action="{{ route('admin.guides.update', $guide->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label for="name" class="form-label fw-semibold small">Full Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $guide->name) }}" required>
                    </div>

                    <div class="col-md-6">
                        <label for="phone" class="form-label fw-semibold small">Phone / WhatsApp Number <span class="text-danger">*</span></label>
                        <input type="text" name="phone" id="phone" class="form-control" value="{{ old('phone', $guide->phone) }}" required>
                    </div>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label for="permit_number" class="form-label fw-semibold small">TANAPA / KINAPA Permit Number</label>
                        <input type="text" name="permit_number" id="permit_number" class="form-control" value="{{ old('permit_number', $guide->permit_number) }}">
                    </div>

                    <div class="col-md-6">
                        <label for="languages" class="form-label fw-semibold small">Spoken Languages</label>
                        <input type="text" name="languages" id="languages" class="form-control" value="{{ old('languages', $guide->languages) }}">
                    </div>
                </div>

                <div class="form-check form-switch mb-4">
                    <input class="form-check-input" type="checkbox" name="active" id="active" value="1" {{ old('active', $guide->active) ? 'checked' : '' }}>
                    <label class="form-check-label fw-semibold small" for="active">Active & Available for Assignments</label>
                </div>

                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('admin.guides.index') }}" class="btn btn-outline-secondary">Cancel</a>
                    <button type="submit" class="btn btn-uwt-primary">
                        <i class="bi bi-check2-circle me-1"></i> Update Guide
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
