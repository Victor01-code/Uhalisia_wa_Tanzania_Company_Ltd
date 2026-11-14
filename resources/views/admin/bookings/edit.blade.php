@extends('layouts.admin')

@section('page_title', 'Edit Booking ' . $booking->reference)

@section('admin_content')
<div class="container-fluid p-0">
    <div class="d-flex align-items-center gap-2 mb-4">
        <a href="{{ route('admin.bookings.show', $booking->id) }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left"></i> Back to Booking
        </a>
        <h2 class="h4 brand-font text-uwt-dark mb-0">Edit Booking {{ $booking->reference }}</h2>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body p-4 p-md-5">
                    <form action="{{ route('admin.bookings.update', $booking->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row g-4">
                            <!-- Section 1 -->
                            <div class="col-12">
                                <h5 class="brand-font text-uwt-primary border-bottom pb-2">
                                    <i class="bi bi-person-check me-2"></i> 1. Client & Tour Selection
                                </h5>
                            </div>

                            <div class="col-md-6">
                                <label for="client_id" class="form-label fw-semibold">Client <span class="text-danger">*</span></label>
                                <select name="client_id" id="client_id" class="form-select @error('client_id') is-invalid @enderror" required>
                                    @foreach($clients as $c)
                                        <option value="{{ $c->id }}" {{ (old('client_id', $booking->client_id) == $c->id) ? 'selected' : '' }}>
                                            {{ $c->full_name }} ({{ $c->email }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label for="tour_id" class="form-label fw-semibold">Tour Template</label>
                                <select name="tour_id" id="tour_id" class="form-select @error('tour_id') is-invalid @enderror">
                                    <option value="">-- Custom Expedition --</option>
                                    @foreach($tours as $t)
                                        <option value="{{ $t->id }}" {{ (old('tour_id', $booking->tour_id) == $t->id) ? 'selected' : '' }}>
                                            {{ $t->name }} ({{ $t->days }} Days)
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Section 2 -->
                            <div class="col-12">
                                <h5 class="brand-font text-uwt-primary border-bottom pb-2 mt-2">
                                    <i class="bi bi-calendar-event me-2"></i> 2. Itinerary Details & Dates
                                </h5>
                            </div>

                            <div class="col-12">
                                <label for="title" class="form-label fw-semibold">Booking Title <span class="text-danger">*</span></label>
                                <input type="text" name="title" id="title" class="form-control @error('title') is-invalid @enderror"
                                       value="{{ old('title', $booking->title) }}" required>
                            </div>

                            <div class="col-md-4">
                                <label for="start_date" class="form-label fw-semibold">Start Date <span class="text-danger">*</span></label>
                                <input type="date" name="start_date" id="start_date" class="form-control @error('start_date') is-invalid @enderror"
                                       value="{{ old('start_date', $booking->start_date ? $booking->start_date->format('Y-m-d') : '') }}" required>
                            </div>

                            <div class="col-md-4">
                                <label for="end_date" class="form-label fw-semibold">End Date <span class="text-danger">*</span></label>
                                <input type="date" name="end_date" id="end_date" class="form-control @error('end_date') is-invalid @enderror"
                                       value="{{ old('end_date', $booking->end_date ? $booking->end_date->format('Y-m-d') : '') }}" required>
                            </div>

                            <div class="col-md-2 col-6">
                                <label for="adults" class="form-label fw-semibold">Adults <span class="text-danger">*</span></label>
                                <input type="number" name="adults" id="adults" class="form-control @error('adults') is-invalid @enderror"
                                       value="{{ old('adults', $booking->adults) }}" min="1" max="50" required>
                            </div>

                            <div class="col-md-2 col-6">
                                <label for="children" class="form-label fw-semibold">Children</label>
                                <input type="number" name="children" id="children" class="form-control @error('children') is-invalid @enderror"
                                       value="{{ old('children', $booking->children) }}" min="0" max="50">
                            </div>

                            <!-- Section 3 -->
                            <div class="col-12">
                                <h5 class="brand-font text-uwt-primary border-bottom pb-2 mt-2">
                                    <i class="bi bi-cash-stack me-2"></i> 3. Pricing & Mode
                                </h5>
                            </div>

                            <div class="col-md-4">
                                <label for="total_price_usd" class="form-label fw-semibold">Total Price (USD) <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text">$</span>
                                    <input type="number" step="0.01" name="total_price_usd" id="total_price_usd" class="form-control @error('total_price_usd') is-invalid @enderror"
                                           value="{{ old('total_price_usd', $booking->total_price_usd) }}" min="0" required>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <label for="discount_usd" class="form-label fw-semibold">Discount (USD)</label>
                                <div class="input-group">
                                    <span class="input-group-text">$</span>
                                    <input type="number" step="0.01" name="discount_usd" id="discount_usd" class="form-control @error('discount_usd') is-invalid @enderror"
                                           value="{{ old('discount_usd', $booking->discount_usd) }}" min="0">
                                </div>
                            </div>

                            <div class="col-md-4">
                                <label for="payment_mode" class="form-label fw-semibold">Payment Mode <span class="text-danger">*</span></label>
                                <select name="payment_mode" id="payment_mode" class="form-select @error('payment_mode') is-invalid @enderror" required>
                                    <option value="company_handles" {{ old('payment_mode', $booking->payment_mode) === 'company_handles' ? 'selected' : '' }}>Company Handles</option>
                                    <option value="client_direct" {{ old('payment_mode', $booking->payment_mode) === 'client_direct' ? 'selected' : '' }}>Client Direct</option>
                                </select>
                            </div>

                            <div class="col-12">
                                <label for="notes" class="form-label fw-semibold">Internal Notes</label>
                                <textarea name="notes" id="notes" rows="3" class="form-control">{{ old('notes', $booking->notes) }}</textarea>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between align-items-center pt-4 mt-3 border-top">
                            <button type="button" class="btn btn-outline-danger" onclick="if(confirm('Are you sure you want to archive this booking?')) document.getElementById('deleteBookingForm').submit();">
                                <i class="bi bi-trash me-1"></i> Archive Booking
                            </button>
                            <div class="d-flex gap-2">
                                <a href="{{ route('admin.bookings.show', $booking->id) }}" class="btn btn-outline-secondary">Cancel</a>
                                <button type="submit" class="btn btn-uwt-primary">
                                    <i class="bi bi-save me-1"></i> Save Changes
                                </button>
                            </div>
                        </div>
                    </form>

                    <form id="deleteBookingForm" action="{{ route('admin.bookings.destroy', $booking->id) }}" method="POST" class="d-none">
                        @csrf
                        @method('DELETE')
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
