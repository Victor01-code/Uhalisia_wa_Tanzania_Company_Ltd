@extends('layouts.admin')

@section('page_title', 'Record Payment Receipt')

@section('admin_content')
<div class="container-fluid p-0">
    <div class="d-flex align-items-center gap-2 mb-4">
        <a href="{{ route('admin.payments.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left"></i> Back to Payments
        </a>
        <h2 class="h4 brand-font text-uwt-dark mb-0">Record Client Payment Receipt</h2>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body p-4 p-md-5">
                    <form action="{{ route('admin.payments.store') }}" method="POST">
                        @csrf

                        <div class="row g-3 mb-3">
                            <div class="col-12">
                                <label for="booking_id" class="form-label fw-semibold">Select Booking <span class="text-danger">*</span></label>
                                <select name="booking_id" id="booking_id" class="form-select @error('booking_id') is-invalid @enderror" required>
                                    <option value="">-- Choose Booking --</option>
                                    @foreach($bookings as $b)
                                        <option value="{{ $b->id }}" {{ (old('booking_id', $selectedBookingId) == $b->id) ? 'selected' : '' }}>
                                            {{ $b->reference }} - {{ $b->client->full_name }} ({{ $b->title }} &bull; Balance: ${{ number_format($b->balanceUsd(), 2) }} USD)
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label for="paid_on" class="form-label fw-semibold">Payment Date <span class="text-danger">*</span></label>
                                <input type="date" name="paid_on" id="paid_on" class="form-control" value="{{ old('paid_on', date('Y-m-d')) }}" required>
                            </div>

                            <div class="col-md-3 col-6">
                                <label for="amount" class="form-label fw-semibold">Amount <span class="text-danger">*</span></label>
                                <input type="number" step="0.01" name="amount" id="amount" class="form-control" value="{{ old('amount') }}" min="0.01" placeholder="0.00" required>
                            </div>

                            <div class="col-md-3 col-6">
                                <label for="currency" class="form-label fw-semibold">Currency <span class="text-danger">*</span></label>
                                <select name="currency" id="currency" class="form-select" required>
                                    <option value="USD" {{ old('currency') === 'USD' ? 'selected' : '' }}>USD ($)</option>
                                    <option value="TZS" {{ old('currency') === 'TZS' ? 'selected' : '' }}>TZS (Shillings)</option>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label for="exchange_rate_used" class="form-label fw-semibold">Exchange Rate (TZS per USD) <span class="text-danger">*</span></label>
                                <input type="number" step="0.01" name="exchange_rate_used" id="exchange_rate_used" class="form-control" value="{{ old('exchange_rate_used', $currentRate) }}" required>
                                <div class="form-text">Defaults to this month's stored exchange rate.</div>
                            </div>

                            <div class="col-md-6">
                                <label for="method" class="form-label fw-semibold">Payment Method <span class="text-danger">*</span></label>
                                <select name="method" id="method" class="form-select" required>
                                    <option value="bank" {{ old('method') === 'bank' ? 'selected' : '' }}>Bank Wire Transfer</option>
                                    <option value="card" {{ old('method') === 'card' ? 'selected' : '' }}>Credit / Debit Card</option>
                                    <option value="cash" {{ old('method') === 'cash' ? 'selected' : '' }}>Cash</option>
                                    <option value="mobile_money" {{ old('method') === 'mobile_money' ? 'selected' : '' }}>Mobile Money (M-Pesa / Tigo Pesa)</option>
                                    <option value="other" {{ old('method') === 'other' ? 'selected' : '' }}>Other</option>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label for="type" class="form-label fw-semibold">Payment Type <span class="text-danger">*</span></label>
                                <select name="type" id="type" class="form-select" required>
                                    <option value="deposit" {{ old('type') === 'deposit' ? 'selected' : '' }}>Deposit (30%)</option>
                                    <option value="balance" {{ old('type') === 'balance' ? 'selected' : '' }}>Balance Settlement</option>
                                    <option value="refund" {{ old('type') === 'refund' ? 'selected' : '' }}>Refund</option>
                                    <option value="other" {{ old('type') === 'other' ? 'selected' : '' }}>Other</option>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label for="reference" class="form-label fw-semibold">Transaction / Bank Reference</label>
                                <input type="text" name="reference" id="reference" class="form-control" value="{{ old('reference') }}" placeholder="e.g. SWIFT Ref or Receipt #">
                            </div>

                            <div class="col-12">
                                <label for="notes" class="form-label fw-semibold">Notes / Receipt Remarks</label>
                                <textarea name="notes" id="notes" rows="2" class="form-control" placeholder="Sender name, bank notes...">{{ old('notes') }}</textarea>
                            </div>

                            <div class="col-12">
                                <div class="p-3 rounded bg-light border">
                                    <div class="form-check form-switch mb-1">
                                        <input class="form-check-input" type="checkbox" name="transition_status" id="transition_status" value="1" checked>
                                        <label class="form-check-label fw-semibold text-uwt-dark" for="transition_status">
                                            <i class="bi bi-arrow-repeat text-uwt-primary me-1"></i> Auto-advance booking stage upon receipt
                                        </label>
                                    </div>
                                    <div class="small text-muted ms-4">
                                        Advances status to <strong>Confirmed</strong> when receiving a deposit, or to <strong>Paid in Full</strong> when the trip balance reaches zero.
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2 pt-3 border-top">
                            <a href="{{ route('admin.payments.index') }}" class="btn btn-outline-secondary">Cancel</a>
                            <button type="submit" class="btn btn-uwt-olive">
                                <i class="bi bi-save me-1"></i> Save Payment Receipt
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
