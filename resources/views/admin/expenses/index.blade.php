@extends('layouts.admin')

@section('page_title', 'Operational Expenses')

@section('admin_content')
<div class="container-fluid p-0">
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-2">
        <div>
            <h2 class="h4 brand-font text-uwt-dark mb-1">Safari Operational Expenses</h2>
            <p class="text-muted small mb-0">Track park fees, fuel, driver allowances, lodges, and vehicle maintenance per trip and general overhead.</p>
        </div>
        <a href="{{ route('admin.expenses.create') }}" class="btn btn-uwt-primary">
            <i class="bi bi-plus-circle me-1"></i> Record New Expense
        </a>
    </div>

    <!-- Expense Totals Metrics -->
    <div class="row g-3 mb-4">
        <div class="col-sm-6 col-lg-4">
            <div class="card border-0 shadow-sm p-3 bg-white">
                <span class="text-muted small text-uppercase fw-semibold">Total Expenses (USD Equiv.)</span>
                <h4 class="fw-bold mb-0 text-danger">${{ number_format($totalUsd, 2) }} USD</h4>
                <small class="text-muted">Calculated at month's exchange rate</small>
            </div>
        </div>
        <div class="col-sm-6 col-lg-4">
            <div class="card border-0 shadow-sm p-3 bg-white">
                <span class="text-muted small text-uppercase fw-semibold">Total Expenses (TZS)</span>
                <h4 class="fw-bold mb-0 text-uwt-dark">{{ number_format($totalTzs, 0) }} TZS</h4>
                <small class="text-muted">Local Tanzanian currency spent</small>
            </div>
        </div>
        <div class="col-sm-6 col-lg-4">
            <div class="card border-0 shadow-sm p-3 bg-white">
                <span class="text-muted small text-uppercase fw-semibold">Filtered Transactions</span>
                <h4 class="fw-bold mb-0 text-primary">{{ $expenses->total() }}</h4>
                <small class="text-muted">Expense entries recorded</small>
            </div>
        </div>
    </div>

    <!-- Filter Card -->
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body p-3">
            <form action="{{ route('admin.expenses.index') }}" method="GET" class="row g-2 align-items-end">
                <div class="col-12 col-sm-6 col-lg-3">
                    <label class="form-label small fw-semibold text-muted mb-1">Category</label>
                    <select name="category" class="form-select form-select-sm">
                        <option value="">All Categories</option>
                        @foreach(\App\Models\Expense::CATEGORIES as $key => $label)
                            <option value="{{ $key }}" {{ request('category') === $key ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-12 col-sm-6 col-lg-3">
                    <label class="form-label small fw-semibold text-muted mb-1">Associated Booking</label>
                    <select name="booking_id" class="form-select form-select-sm">
                        <option value="">All (Trip + General)</option>
                        @foreach($bookings as $b)
                            <option value="{{ $b->id }}" {{ request('booking_id') == $b->id ? 'selected' : '' }}>
                                {{ $b->reference }} - {{ $b->client->full_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-6 col-sm-6 col-lg-2">
                    <label class="form-label small fw-semibold text-muted mb-1">Scope</label>
                    <select name="scope" class="form-select form-select-sm">
                        <option value="">All Scopes</option>
                        <option value="booking" {{ request('scope') === 'booking' ? 'selected' : '' }}>Trip Specific</option>
                        <option value="general" {{ request('scope') === 'general' ? 'selected' : '' }}>General Overhead</option>
                    </select>
                </div>

                <div class="col-6 col-sm-6 col-lg-2">
                    <label class="form-label small fw-semibold text-muted mb-1">Start Date</label>
                    <input type="date" name="start_date" value="{{ request('start_date') }}" class="form-control form-control-sm">
                </div>

                <div class="col-12 col-sm-12 col-lg-2 d-flex gap-2">
                    <button type="submit" class="btn btn-sm btn-uwt-primary flex-grow-1">
                        <i class="bi bi-funnel me-1"></i> Filter
                    </button>
                    @if(request()->hasAny(['category', 'booking_id', 'scope', 'start_date', 'end_date']))
                        <a href="{{ route('admin.expenses.index') }}" class="btn btn-sm btn-outline-secondary" title="Clear filters">
                            <i class="bi bi-x-circle"></i>
                        </a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <!-- Expenses Table -->
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Date</th>
                            <th>Scope / Booking</th>
                            <th>Category</th>
                            <th>Description</th>
                            <th>Amount (Entered)</th>
                            <th>TZS Value</th>
                            <th>USD Value</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($expenses as $exp)
                            <tr>
                                <td>{{ $exp->expense_date ? $exp->expense_date->format('d M Y') : 'N/A' }}</td>
                                <td>
                                    @if($exp->booking)
                                        <a href="{{ route('admin.bookings.show', $exp->booking->id) }}" class="badge bg-cream text-uwt-dark border text-decoration-none">
                                            <i class="bi bi-compass me-1"></i> {{ $exp->booking->reference }}
                                        </a>
                                        <div class="small text-muted">{{ $exp->booking->client->full_name ?? '' }}</div>
                                    @else
                                        <span class="badge bg-light text-muted border">General Overhead</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge bg-secondary-subtle text-dark border">
                                        {{ $exp->categoryLabel() }}
                                    </span>
                                </td>
                                <td>
                                    <div class="fw-semibold text-uwt-dark">{{ $exp->description }}</div>
                                    @if($exp->notes)
                                        <small class="text-muted">{{ Str::limit($exp->notes, 50) }}</small>
                                    @endif
                                </td>
                                <td>
                                    @if($exp->currency === 'USD')
                                        <span class="fw-bold text-uwt-dark">${{ number_format($exp->amount, 2) }} USD</span>
                                    @else
                                        <span class="fw-bold text-uwt-dark">{{ number_format($exp->amount, 0) }} TZS</span>
                                    @endif
                                </td>
                                <td class="small text-muted">{{ number_format($exp->amount_tzs, 0) }} TZS</td>
                                <td class="fw-bold text-danger">${{ number_format($exp->amount_usd, 2) }}</td>
                                <td class="text-end">
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('admin.expenses.edit', $exp->id) }}" class="btn btn-outline-secondary" title="Edit Expense">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form action="{{ route('admin.expenses.destroy', $exp->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this expense record?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger" title="Delete Expense">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-5 text-muted">
                                    <i class="bi bi-receipt text-muted fs-3 d-block mb-2"></i>
                                    No expense records found matching criteria.<br>
                                    <small>Click "Record New Expense" above to add trip or operational costs.</small>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($expenses->hasPages())
                <div class="p-3 border-top">
                    {{ $expenses->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
