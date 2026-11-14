@extends('layouts.admin')

@section('page_title', 'Record Safari Expense')

@section('admin_content')
<div class="container-fluid p-0">
    <div class="d-flex align-items-center gap-2 mb-4">
        <a href="{{ route('admin.expenses.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left"></i> Back to Expenses
        </a>
        <h2 class="h4 brand-font text-uwt-dark mb-0">Record Operational Expense</h2>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body p-4 p-md-5">
                    <form action="{{ route('admin.expenses.store') }}" method="POST">
                        @csrf

                        <div class="row g-3 mb-3">
                            <div class="col-12">
                                <label for="booking_id" class="form-label fw-semibold">Associated Safari Trip</label>
                                <select name="booking_id" id="booking_id" class="form-select @error('booking_id') is-invalid @enderror">
                                    <option value="">-- General Operational Expense (Company Overhead) --</option>
                                    @foreach($bookings as $b)
                                        <option value="{{ $b->id }}" {{ (old('booking_id', $selectedBookingId) == $b->id) ? 'selected' : '' }}>
                                            Trip: {{ $b->reference }} - {{ $b->client->full_name }} ({{ $b->title }})
                                        </option>
                                    @endforeach
                                </select>
                                <div class="form-text">Leave blank if this expense is a general company overhead (e.g. office, annual vehicle insurance, taxes).</div>
                            </div>

                            <div class="col-md-6">
                                <label for="expense_date" class="form-label fw-semibold">Expense Date <span class="text-danger">*</span></label>
                                <input type="date" name="expense_date" id="expense_date" class="form-control @error('expense_date') is-invalid @enderror" value="{{ old('expense_date', $expenseDate) }}" required>
                            </div>

                            <div class="col-md-6">
                                <label for="category" class="form-label fw-semibold">Expense Category <span class="text-danger">*</span></label>
                                <select name="category" id="category" class="form-select @error('category') is-invalid @enderror" required>
                                    <option value="">-- Choose Category --</option>
                                    @foreach(\App\Models\Expense::CATEGORIES as $key => $label)
                                        <option value="{{ $key }}" {{ old('category') === $key ? 'selected' : '' }}>{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-12">
                                <label for="description" class="form-label fw-semibold">Description / Payee <span class="text-danger">*</span></label>
                                <input type="text" name="description" id="description" class="form-control @error('description') is-invalid @enderror" value="{{ old('description') }}" placeholder="e.g. TANAPA Park Entry Fees - Serengeti, Shell Diesel Arusha..." required>
                            </div>

                            <div class="col-md-6">
                                <label for="amount" class="form-label fw-semibold">Amount <span class="text-danger">*</span></label>
                                <input type="number" step="0.01" name="amount" id="amount" class="form-control @error('amount') is-invalid @enderror" value="{{ old('amount') }}" min="0.01" placeholder="0.00" required>
                            </div>

                            <div class="col-md-6">
                                <label for="currency" class="form-label fw-semibold">Currency <span class="text-danger">*</span></label>
                                <select name="currency" id="currency" class="form-select" required>
                                    <option value="TZS" {{ old('currency', 'TZS') === 'TZS' ? 'selected' : '' }}>TZS (Tanzanian Shillings)</option>
                                    <option value="USD" {{ old('currency') === 'USD' ? 'selected' : '' }}>USD ($)</option>
                                </select>
                                <div class="form-text">
                                    Current accounting rate: <strong>1 USD = {{ number_format($currentRate, 0) }} TZS</strong>. Auto-converts.
                                </div>
                            </div>

                            <div class="col-12">
                                <label for="notes" class="form-label fw-semibold">Additional Notes / Receipt Details</label>
                                <textarea name="notes" id="notes" rows="2" class="form-control" placeholder="Receipt voucher number, fuel slip number, driver name...">{{ old('notes') }}</textarea>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2 pt-3 border-top">
                            <a href="{{ route('admin.expenses.index') }}" class="btn btn-outline-secondary">Cancel</a>
                            <button type="submit" class="btn btn-uwt-primary">
                                <i class="bi bi-save me-1"></i> Save Expense Record
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
