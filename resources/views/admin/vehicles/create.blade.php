@extends('layouts.admin')

@section('page_title', 'Add Safari Vehicle')

@section('admin_content')
<div class="container-fluid p-0" style="max-width: 800px;">
    <div class="d-flex align-items-center gap-3 mb-4">
        <a href="{{ route('admin.vehicles.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left"></i> Fleet
        </a>
        <div>
            <h2 class="h4 brand-font text-uwt-dark mb-0">Add Safari Vehicle</h2>
            <p class="text-muted small mb-0">Register a new 4x4 cruiser or transfer vehicle into the fleet.</p>
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
            <form action="{{ route('admin.vehicles.store') }}" method="POST">
                @csrf

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label for="name" class="form-label fw-semibold small">Vehicle Name / Unit <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="name" class="form-control" placeholder="e.g. Safari Cruiser 01 (Ngorongoro)" value="{{ old('name') }}" required>
                    </div>

                    <div class="col-md-6">
                        <label for="plate_number" class="form-label fw-semibold small">Plate Number <span class="text-danger">*</span></label>
                        <input type="text" name="plate_number" id="plate_number" class="form-control" placeholder="e.g. T 421 DRY" value="{{ old('plate_number') }}" required>
                    </div>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label for="type" class="form-label fw-semibold small">Vehicle Type <span class="text-danger">*</span></label>
                        <select name="type" id="type" class="form-select" required>
                            <option value="">Select type...</option>
                            <option value="Extended 4x4 Land Cruiser" {{ old('type') == 'Extended 4x4 Land Cruiser' ? 'selected' : '' }}>Extended 4x4 Land Cruiser (Pop-up roof)</option>
                            <option value="Standard 4x4 Land Cruiser" {{ old('type') == 'Standard 4x4 Land Cruiser' ? 'selected' : '' }}>Standard 4x4 Land Cruiser (Pop-up roof)</option>
                            <option value="Safari Minivan 4x4" {{ old('type') == 'Safari Minivan 4x4' ? 'selected' : '' }}>Safari Minivan 4x4</option>
                            <option value="Airport Transfer Van" {{ old('type') == 'Airport Transfer Van' ? 'selected' : '' }}>Airport Transfer Van</option>
                            <option value="Luxury VIP Cruiser" {{ old('type') == 'Luxury VIP Cruiser' ? 'selected' : '' }}>Luxury VIP Cruiser</option>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label for="seats" class="form-label fw-semibold small">Passenger Window Seats <span class="text-danger">*</span></label>
                        <input type="number" name="seats" id="seats" class="form-control" min="1" max="50" value="{{ old('seats', 7) }}" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="notes" class="form-label fw-semibold small">Vehicle Features & Notes</label>
                    <textarea name="notes" id="notes" class="form-control" rows="3" placeholder="e.g. Inverter 220V charging ports, fridge onboard, dual spare tires, radio VHF">{{ old('notes') }}</textarea>
                </div>

                <div class="form-check form-switch mb-4">
                    <input class="form-check-input" type="checkbox" name="active" id="active" value="1" {{ old('active', true) ? 'checked' : '' }}>
                    <label class="form-check-label fw-semibold small" for="active">Active & Available for Bookings</label>
                </div>

                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('admin.vehicles.index') }}" class="btn btn-outline-secondary">Cancel</a>
                    <button type="submit" class="btn btn-uwt-primary">
                        <i class="bi bi-check2-circle me-1"></i> Save Vehicle
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
