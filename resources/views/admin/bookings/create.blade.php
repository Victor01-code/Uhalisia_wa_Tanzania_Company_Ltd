@extends('layouts.admin')

@section('page_title', 'Create New Booking')

@section('admin_content')
<div class="container-fluid p-0">
    <div class="d-flex align-items-center gap-2 mb-4">
        <a href="{{ route('admin.bookings.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left"></i> Back to Bookings
        </a>
        <h2 class="h4 brand-font text-uwt-dark mb-0">Create New Safari / Tour Booking</h2>
    </div>

    @if($lead)
        <div class="alert alert-info d-flex align-items-center gap-2 mb-4">
            <i class="bi bi-info-circle-fill fs-5"></i>
            <div>
                <strong>Converting Lead #{{ $lead->id }}:</strong> Details for <strong>{{ $lead->name }}</strong> have been prefilled.
                Interest: <em>{{ $lead->safari_interest }}</em>.
            </div>
        </div>
    @endif

    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body p-4 p-md-5">
                    <form action="{{ route('admin.bookings.store') }}" method="POST">
                        @csrf

                        @if($selectedLeadId)
                            <input type="hidden" name="lead_id" value="{{ $selectedLeadId }}">
                        @endif

                        <div class="row g-4">
                            <!-- Section 1: Client & Tour Association -->
                            <div class="col-12">
                                <h5 class="brand-font text-uwt-primary border-bottom pb-2">
                                    <i class="bi bi-person-check me-2"></i> 1. Client & Tour Selection
                                </h5>
                            </div>

                            <div class="col-md-6">
                                <label for="client_id" class="form-label fw-semibold">Client <span class="text-danger">*</span></label>
                                <select name="client_id" id="client_id" class="form-select @error('client_id') is-invalid @enderror" required>
                                    <option value="">-- Choose Existing Client --</option>
                                    @foreach($clients as $c)
                                        <option value="{{ $c->id }}" {{ (old('client_id', $selectedClientId) == $c->id) ? 'selected' : '' }}>
                                            {{ $c->full_name }} ({{ $c->email }} &bull; {{ $c->country ?? 'No country' }})
                                        </option>
                                    @endforeach
                                </select>
                                <div class="form-text">
                                    Need to create a new client first? <a href="{{ route('admin.clients.create') }}" target="_blank">Add Client</a>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label for="tour_id" class="form-label fw-semibold">Tour Template (Optional)</label>
                                <select name="tour_id" id="tour_id" class="form-select @error('tour_id') is-invalid @enderror">
                                    <option value="">-- Custom Expedition (Blank Itinerary) --</option>
                                    @foreach($tours as $t)
                                        <option value="{{ $t->id }}" {{ (old('tour_id') == $t->id) ? 'selected' : '' }}>
                                            {{ $t->name }} ({{ $t->days }} Days &bull; Base ${{ number_format($t->base_price_usd, 0) }})
                                        </option>
                                    @endforeach
                                </select>
                                <div class="form-text">Selecting a tour template will automatically copy its days into this booking's itinerary.</div>
                            </div>

                            <!-- Section 2: Trip Title & Travel Dates -->
                            <div class="col-12">
                                <h5 class="brand-font text-uwt-primary border-bottom pb-2 mt-2">
                                    <i class="bi bi-calendar-event me-2"></i> 2. Itinerary Details & Dates
                                </h5>
                            </div>

                            <div class="col-12">
                                <label for="title" class="form-label fw-semibold">Booking / Trip Title <span class="text-danger">*</span></label>
                                <input type="text" name="title" id="title" class="form-control @error('title') is-invalid @enderror"
                                       value="{{ old('title', $lead ? ($lead->safari_interest ? $lead->safari_interest . ' - ' . $lead->name : 'Safari for ' . $lead->name) : '') }}"
                                       placeholder="e.g. 10 Days Northern Circuit Wildlife Safari - Smith Family" required>
                            </div>

                            <div class="col-md-4">
                                <label for="start_date" class="form-label fw-semibold">Start Date <span class="text-danger">*</span></label>
                                <input type="date" name="start_date" id="start_date" class="form-control @error('start_date') is-invalid @enderror"
                                       value="{{ old('start_date', date('Y-m-d', strtotime('+14 days'))) }}" required>
                            </div>

                            <div class="col-md-4">
                                <label for="end_date" class="form-label fw-semibold">End Date <span class="text-danger">*</span></label>
                                <input type="date" name="end_date" id="end_date" class="form-control @error('end_date') is-invalid @enderror"
                                       value="{{ old('end_date', date('Y-m-d', strtotime('+23 days'))) }}" required>
                            </div>

                            <div class="col-md-2 col-6">
                                <label for="adults" class="form-label fw-semibold">Adults <span class="text-danger">*</span></label>
                                <input type="number" name="adults" id="adults" class="form-control @error('adults') is-invalid @enderror"
                                       value="{{ old('adults', 2) }}" min="1" max="50" required>
                            </div>

                            <div class="col-md-2 col-6">
                                <label for="children" class="form-label fw-semibold">Children</label>
                                <input type="number" name="children" id="children" class="form-control @error('children') is-invalid @enderror"
                                       value="{{ old('children', 0) }}" min="0" max="50">
                            </div>

                            <!-- Section 3: Financials & Mode -->
                            <div class="col-12">
                                <h5 class="brand-font text-uwt-primary border-bottom pb-2 mt-2">
                                    <i class="bi bi-cash-stack me-2"></i> 3. Pricing & Payment Mode
                                </h5>
                            </div>

                            <div class="col-md-4">
                                <label for="total_price_usd" class="form-label fw-semibold">Total Price (USD) <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text">$</span>
                                    <input type="number" step="0.01" name="total_price_usd" id="total_price_usd" class="form-control @error('total_price_usd') is-invalid @enderror"
                                           value="{{ old('total_price_usd', 0.00) }}" min="0" required>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <label for="discount_usd" class="form-label fw-semibold">Discount (USD)</label>
                                <div class="input-group">
                                    <span class="input-group-text">$</span>
                                    <input type="number" step="0.01" name="discount_usd" id="discount_usd" class="form-control @error('discount_usd') is-invalid @enderror"
                                           value="{{ old('discount_usd', 0.00) }}" min="0">
                                </div>
                            </div>

                            <div class="col-md-4">
                                <label for="payment_mode" class="form-label fw-semibold">Payment Mode <span class="text-danger">*</span></label>
                                <select name="payment_mode" id="payment_mode" class="form-select @error('payment_mode') is-invalid @enderror" required>
                                    <option value="company_handles" {{ old('payment_mode') === 'company_handles' ? 'selected' : '' }}>Company Handles (Full Accounting)</option>
                                    <option value="client_direct" {{ old('payment_mode') === 'client_direct' ? 'selected' : '' }}>Client Direct (Client Pays Own Costs)</option>
                                </select>
                                <div class="form-text">Only <em>Company Handles</em> trips count towards company income reports.</div>
                            </div>

                            <div class="col-md-6">
                                <label for="status" class="form-label fw-semibold">Initial Status <span class="text-danger">*</span></label>
                                <select name="status" id="status" class="form-select @error('status') is-invalid @enderror" required>
                                    <option value="inquiry" {{ old('status') === 'inquiry' ? 'selected' : '' }}>Inquiry (Initial Stage)</option>
                                    <option value="quote_sent" {{ old('status') === 'quote_sent' ? 'selected' : '' }}>Quote Sent</option>
                                    <option value="confirmed" {{ old('status', $lead ? 'confirmed' : 'inquiry') === 'confirmed' ? 'selected' : '' }}>Confirmed (Deposit Received / Promised)</option>
                                    <option value="paid_in_full" {{ old('status') === 'paid_in_full' ? 'selected' : '' }}>Paid in Full</option>
                                </select>
                            </div>

                            <div class="col-12">
                                <label for="notes" class="form-label fw-semibold">Internal Booking Notes / Special Instructions</label>
                                <textarea name="notes" id="notes" rows="3" class="form-control" placeholder="Special guide requests, flight details, room configurations...">{{ old('notes') }}</textarea>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2 pt-4 mt-3 border-top">
                            <a href="{{ route('admin.bookings.index') }}" class="btn btn-outline-secondary">Cancel</a>
                            <button type="submit" class="btn btn-uwt-primary">
                                <i class="bi bi-save me-1"></i> Create Booking & Generate Reference
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
