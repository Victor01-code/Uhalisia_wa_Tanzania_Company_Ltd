@extends('layouts.admin')

@section('page_title', 'Bookings & Trips')

@section('admin_content')
<div class="container-fluid p-0">

    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-2">
        <div>
            <h2 class="h4 brand-font text-uwt-dark mb-1">Bookings & Itineraries</h2>
            <p class="text-muted small mb-0">Active safaris, Kilimanjaro climbs, quotes, and confirmed client expeditions.</p>
        </div>
        <div>
            <a href="{{ route('admin.bookings.create') }}" class="btn btn-uwt-primary">
                <i class="bi bi-calendar-plus me-1"></i> Create New Booking
            </a>
        </div>
    </div>

    <!-- Filters Card -->
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body p-3">
            <form action="{{ route('admin.bookings.index') }}" method="GET" class="row g-2 align-items-end">
                <div class="col-md-3">
                    <label for="search" class="form-label small text-muted mb-1">Search Ref / Client / Title</label>
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-light"><i class="bi bi-search"></i></span>
                        <input type="text" name="search" id="search" class="form-control"
                               placeholder="e.g. UWT-2026-0001, Jenkins..." value="{{ request('search') }}">
                    </div>
                </div>

                <div class="col-md-2 col-6">
                    <label for="status" class="form-label small text-muted mb-1">Booking Status</label>
                    <select name="status" id="status" class="form-select form-select-sm">
                        <option value="">All Statuses</option>
                        @foreach(\App\Models\Booking::STATUSES as $st)
                            <option value="{{ $st }}" {{ request('status') === $st ? 'selected' : '' }}>
                                {{ ucfirst(str_replace('_', ' ', $st)) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2 col-6">
                    <label for="payment_mode" class="form-label small text-muted mb-1">Payment Mode</label>
                    <select name="payment_mode" id="payment_mode" class="form-select form-select-sm">
                        <option value="">All Modes</option>
                        <option value="company_handles" {{ request('payment_mode') === 'company_handles' ? 'selected' : '' }}>Company Handles</option>
                        <option value="client_direct" {{ request('payment_mode') === 'client_direct' ? 'selected' : '' }}>Client Direct</option>
                    </select>
                </div>

                <div class="col-md-2 col-6">
                    <label for="tour_id" class="form-label small text-muted mb-1">Tour Package</label>
                    <select name="tour_id" id="tour_id" class="form-select form-select-sm">
                        <option value="">All Tours</option>
                        @foreach($tours as $t)
                            <option value="{{ $t->id }}" {{ request('tour_id') == $t->id ? 'selected' : '' }}>
                                {{ $t->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2 col-6">
                    <label for="date_from" class="form-label small text-muted mb-1">Departure From</label>
                    <input type="date" name="date_from" id="date_from" class="form-control form-control-sm" value="{{ request('date_from') }}">
                </div>

                <div class="col-12 col-md-auto d-flex gap-1">
                    <button type="submit" class="btn btn-sm btn-uwt-primary px-3" title="Apply Filter">
                        <i class="bi bi-funnel"></i> Filter
                    </button>
                    @if(request()->hasAny(['search', 'status', 'payment_mode', 'tour_id', 'date_from']))
                        <a href="{{ route('admin.bookings.index') }}" class="btn btn-sm btn-outline-secondary" title="Reset">
                            <i class="bi bi-x-lg"></i>
                        </a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <!-- Bookings Table -->
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th>Reference</th>
                            <th>Client</th>
                            <th>Trip Title</th>
                            <th>Travel Dates</th>
                            <th>Party</th>
                            <th>Status</th>
                            <th>Mode</th>
                            <th>Total USD</th>
                            <th>Balance USD</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($bookings as $b)
                            <tr>
                                <td>
                                    <a href="{{ route('admin.bookings.show', $b->id) }}" class="fw-bold text-uwt-primary text-decoration-none">
                                        {{ $b->reference }}
                                    </a>
                                </td>
                                <td>
                                    <a href="{{ route('admin.clients.show', $b->client_id) }}" class="fw-semibold text-dark text-decoration-none">
                                        {{ $b->client->full_name ?? 'N/A' }}
                                    </a>
                                    <div class="small text-muted">{{ $b->client->country ?? '' }}</div>
                                </td>
                                <td>
                                    <div class="fw-semibold small d-block text-truncate" style="max-width: 220px;" title="{{ $b->title }}">
                                        {{ $b->title }}
                                    </div>
                                    @if($b->tour)
                                        <small class="text-muted d-block">{{ $b->tour->name }}</small>
                                    @endif
                                </td>
                                <td>
                                    <div class="small fw-semibold">
                                        {{ $b->start_date ? $b->start_date->format('d M Y') : 'TBD' }}
                                    </div>
                                    <small class="text-muted">
                                        {{ $b->durationDays() }} Days (to {{ $b->end_date ? $b->end_date->format('d M') : 'TBD' }})
                                    </small>
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark border small">
                                        {{ $b->adults }} Ad, {{ $b->children }} Ch
                                    </span>
                                </td>
                                <td>
                                    <span class="badge-status status-{{ $b->status }}">
                                        {{ ucfirst(str_replace('_', ' ', $b->status)) }}
                                    </span>
                                </td>
                                <td>
                                    @if($b->payment_mode === 'company_handles')
                                        <span class="badge bg-cream text-uwt-dark small">Company Handles</span>
                                    @else
                                        <span class="badge bg-secondary text-white small" title="Not counted in company income reports">Client Direct</span>
                                    @endif
                                </td>
                                <td class="fw-bold small">
                                    ${{ number_format($b->netPriceUsd(), 2) }}
                                </td>
                                <td>
                                    @if($b->balanceUsd() <= 0.01)
                                        <span class="badge bg-success-subtle text-success fw-bold">PAID</span>
                                    @else
                                        <span class="fw-bold text-danger small">${{ number_format($b->balanceUsd(), 2) }}</span>
                                    @endif
                                </td>
                                <td class="text-end text-nowrap">
                                    <a href="{{ route('admin.bookings.show', $b->id) }}" class="btn btn-sm btn-outline-secondary py-1 px-2" title="Manage Booking & Itinerary">
                                        <i class="bi bi-eye"></i> View
                                    </a>
                                    <a href="{{ route('admin.bookings.edit', $b->id) }}" class="btn btn-sm btn-outline-secondary py-1 px-2" title="Edit Booking">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="text-center py-5 text-muted">
                                    <i class="bi bi-calendar-x fs-2 d-block mb-2 text-muted"></i>
                                    No bookings found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if($bookings->hasPages())
            <div class="card-footer bg-white border-top p-3">
                {{ $bookings->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </div>

</div>
@endsection
