@extends('layouts.admin')

@section('page_title', 'Edit Tour: ' . $tour->name)

@section('admin_content')
<div class="container-fluid p-0">
    <div class="d-flex align-items-center gap-2 mb-4">
        <a href="{{ route('admin.tours.show', $tour->id) }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left"></i> Back to Tour
        </a>
        <h2 class="h4 brand-font text-uwt-dark mb-0">Edit Tour: {{ $tour->name }}</h2>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body p-4">
                    <form action="{{ route('admin.tours.update', $tour->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row g-3 mb-3">
                            <div class="col-md-8">
                                <label for="name" class="form-label fw-semibold">Tour Package Name <span class="text-danger">*</span></label>
                                <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $tour->name) }}" required>
                            </div>

                            <div class="col-md-4">
                                <label for="slug" class="form-label fw-semibold">URL Slug <span class="text-danger">*</span></label>
                                <input type="text" name="slug" id="slug" class="form-control" value="{{ old('slug', $tour->slug) }}" required>
                            </div>

                            <div class="col-md-4">
                                <label for="type" class="form-label fw-semibold">Tour Category <span class="text-danger">*</span></label>
                                <select name="type" id="type" class="form-select" required>
                                    <option value="safari" {{ old('type', $tour->type) === 'safari' ? 'selected' : '' }}>Wildlife Safari</option>
                                    <option value="climb" {{ old('type', $tour->type) === 'climb' ? 'selected' : '' }}>Mountain Climb</option>
                                    <option value="beach" {{ old('type', $tour->type) === 'beach' ? 'selected' : '' }}>Zanzibar Beach</option>
                                    <option value="cultural" {{ old('type', $tour->type) === 'cultural' ? 'selected' : '' }}>Cultural Tour</option>
                                    <option value="combo" {{ old('type', $tour->type) === 'combo' ? 'selected' : '' }}>Bush & Beach Combo</option>
                                </select>
                            </div>

                            <div class="col-md-4">
                                <label for="days" class="form-label fw-semibold">Total Days <span class="text-danger">*</span></label>
                                <input type="number" name="days" id="days" class="form-control" value="{{ old('days', $tour->days) }}" min="1" max="60" required>
                            </div>

                            <div class="col-md-4">
                                <label for="base_price_usd" class="form-label fw-semibold">Base Price (USD / Person) <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text">$</span>
                                    <input type="number" step="0.01" name="base_price_usd" id="base_price_usd" class="form-control" value="{{ old('base_price_usd', $tour->base_price_usd) }}" min="0" required>
                                </div>
                            </div>

                            <div class="col-12">
                                <label for="summary" class="form-label fw-semibold">Package Summary</label>
                                <textarea name="summary" id="summary" rows="4" class="form-control">{{ old('summary', $tour->summary) }}</textarea>
                            </div>

                            <div class="col-12">
                                <div class="form-check form-switch">
                                    <input type="checkbox" name="active" id="active" class="form-check-input" value="1" {{ old('active', $tour->active) ? 'checked' : '' }}>
                                    <label for="active" class="form-check-label fw-semibold">Active & Available for Booking</label>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between align-items-center pt-3 border-top">
                            <button type="button" class="btn btn-outline-danger" onclick="if(confirm('Delete this tour template?')) document.getElementById('deleteTourForm').submit();">
                                <i class="bi bi-trash me-1"></i> Delete Template
                            </button>
                            <div class="d-flex gap-2">
                                <a href="{{ route('admin.tours.show', $tour->id) }}" class="btn btn-outline-secondary">Cancel</a>
                                <button type="submit" class="btn btn-uwt-primary">
                                    <i class="bi bi-save me-1"></i> Update Tour Package
                                </button>
                            </div>
                        </div>
                    </form>

                    <form id="deleteTourForm" action="{{ route('admin.tours.destroy', $tour->id) }}" method="POST" class="d-none">
                        @csrf
                        @method('DELETE')
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
