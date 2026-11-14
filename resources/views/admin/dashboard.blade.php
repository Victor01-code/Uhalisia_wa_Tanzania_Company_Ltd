@extends('layouts.admin')

@section('page_title', 'Safari Operations Dashboard')

@section('admin_content')
<div class="container-fluid p-0">

    <!-- KPI Stat Cards Row -->
    <div class="row g-3 mb-4">
        <!-- Card 1: New Leads -->
        <div class="col-sm-6 col-xl-3">
            <div class="card stat-card shadow-sm h-100">
                <div class="card-body p-3 d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-muted small text-uppercase fw-semibold">New Leads</span>
                        <h2 class="display-6 fw-bold mb-0 text-uwt-dark">{{ $newLeadsCount }}</h2>
                        <small class="text-uwt-primary fw-semibold">
                            <i class="bi bi-clock-history me-1"></i> Awaiting Quote / Action
                        </small>
                    </div>
                    <div class="stat-icon bg-cream text-uwt-primary">
                        <i class="bi bi-inbox-fill"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card 2: Upcoming Trips -->
        <div class="col-sm-6 col-xl-3">
            <div class="card stat-card stat-gold shadow-sm h-100">
                <div class="card-body p-3 d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-muted small text-uppercase fw-semibold">Upcoming Departures</span>
                        <h2 class="display-6 fw-bold mb-0 text-uwt-dark">{{ $upcomingTripsCount }}</h2>
                        <small class="text-uwt-gold fw-semibold">
                            <i class="bi bi-calendar-event me-1"></i> Next 30 Days
                        </small>
                    </div>
                    <div class="stat-icon bg-cream text-uwt-gold">
                        <i class="bi bi-compass-fill"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card 3: Trips in Progress -->
        <div class="col-sm-6 col-xl-3">
            <div class="card stat-card stat-olive shadow-sm h-100">
                <div class="card-body p-3 d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-muted small text-uppercase fw-semibold">Trips in Progress</span>
                        <h2 class="display-6 fw-bold mb-0 text-uwt-dark">{{ $tripsInProgressCount }}</h2>
                        <small class="text-uwt-olive fw-semibold">
                            <i class="bi bi-geo-alt-fill me-1"></i> Currently on Safari / Mountain
                        </small>
                    </div>
                    <div class="stat-icon bg-cream text-uwt-olive">
                        <i class="bi bi-binoculars-fill"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card 4: Unpaid Balances -->
        <div class="col-sm-6 col-xl-3">
            <div class="card stat-card stat-dark shadow-sm h-100">
                <div class="card-body p-3 d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-muted small text-uppercase fw-semibold">Unpaid Balances</span>
                        <h2 class="display-6 fw-bold mb-0 text-danger">${{ number_format($totalUnpaidBalanceUsd, 0) }}</h2>
                        <small class="text-muted">
                            Across <strong>{{ $unpaidBalancesCount }}</strong> active bookings
                        </small>
                    </div>
                    <div class="stat-icon bg-light text-danger">
                        <i class="bi bi-cash-coin"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions Banner -->
    <div class="card bg-white border-0 shadow-sm p-3 mb-4">
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
            <div class="d-flex align-items-center gap-2">
                <i class="bi bi-lightning-charge-fill text-uwt-primary fs-4"></i>
                <div>
                    <h6 class="mb-0 fw-bold">Quick Operations</h6>
                    <small class="text-muted">Frequently used shortcuts for safari coordinators and agents</small>
                </div>
            </div>

            <div class="d-flex flex-wrap gap-2">
                @if(Auth::user()->hasRole(['admin', 'agent']))
                    <a href="{{ route('admin.leads.create') }}" class="btn btn-uwt-primary btn-sm">
                        <i class="bi bi-person-plus-fill me-1"></i> Log Manual Lead
                    </a>
                    <a href="{{ route('admin.bookings.create') }}" class="btn btn-uwt-gold text-white btn-sm">
                        <i class="bi bi-calendar-plus me-1"></i> New Booking
                    </a>
                @endif

                @if(Auth::user()->hasRole(['admin', 'accountant']))
                    <a href="{{ route('admin.payments.create') }}" class="btn btn-uwt-olive btn-sm">
                        <i class="bi bi-currency-dollar me-1"></i> Record Payment
                    </a>
                @endif

                <a href="{{ route('book.show') }}" target="_blank" class="btn btn-outline-dark btn-sm">
                    <i class="bi bi-box-arrow-up-right me-1"></i> Public /book Form
                </a>
            </div>
        </div>
    </div>

    <!-- Two-Column Operational Grids -->
    <div class="row g-4 mb-4">
        <!-- Left: Recent Leads & Inquiries -->
        <div class="col-lg-6">
            <div class="card shadow-sm h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span><i class="bi bi-inbox text-uwt-primary me-2"></i> Recent Inquiries & Leads</span>
                    <a href="{{ route('admin.leads.index') }}" class="btn btn-sm btn-link text-uwt-primary text-decoration-none">
                        View All Leads <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>Client / Lead</th>
                                    <th>Interest</th>
                                    <th>Source</th>
                                    <th>Status</th>
                                    <th class="text-end">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentLeads as $lead)
                                    <tr>
                                        <td>
                                            <a href="{{ route('admin.leads.show', $lead->id) }}" class="fw-bold text-dark text-decoration-none">
                                                {{ $lead->name }}
                                            </a>
                                            <div class="small text-muted">{{ $lead->country ?? 'No country' }}</div>
                                        </td>
                                        <td>
                                            <small class="d-block text-truncate" style="max-width: 140px;" title="{{ $lead->safari_interest }}">
                                                {{ $lead->safari_interest ?? 'Custom' }}
                                            </small>
                                        </td>
                                        <td>
                                            <span class="badge bg-light text-dark border">
                                                {{ $lead->source === 'website_quote_form' ? 'Website' : ($lead->source === 'book_form' ? '/book' : 'Manual') }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge-status status-{{ $lead->status }}">
                                                {{ ucfirst($lead->status) }}
                                            </span>
                                        </td>
                                        <td class="text-end">
                                            <a href="{{ route('admin.leads.show', $lead->id) }}" class="btn btn-sm btn-outline-secondary py-0 px-2" title="View Lead">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-4 text-muted">
                                            <i class="bi bi-inbox fs-3 d-block text-muted mb-2"></i>
                                            No inquiries logged yet.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right: Upcoming & Active Departures -->
        <div class="col-lg-6">
            <div class="card shadow-sm h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span><i class="bi bi-calendar-check text-uwt-gold me-2"></i> Upcoming Departures & Active Trips</span>
                    <a href="{{ route('admin.bookings.index') }}" class="btn btn-sm btn-link text-uwt-primary text-decoration-none">
                        View All Bookings <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>Ref / Client</th>
                                    <th>Dates</th>
                                    <th>Status</th>
                                    <th>Balance</th>
                                    <th class="text-end">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($upcomingBookings as $b)
                                    <tr>
                                        <td>
                                            <a href="{{ route('admin.bookings.show', $b->id) }}" class="fw-bold text-dark text-decoration-none">
                                                {{ $b->reference }}
                                            </a>
                                            <div class="small text-muted">{{ $b->client->full_name ?? 'N/A' }}</div>
                                        </td>
                                        <td>
                                            <small class="fw-semibold">{{ $b->start_date ? $b->start_date->format('d M') : 'TBD' }} - {{ $b->end_date ? $b->end_date->format('d M Y') : 'TBD' }}</small>
                                            <div class="small text-muted">{{ $b->durationDays() }} Days &bull; {{ $b->adults }} Ad, {{ $b->children }} Ch</div>
                                        </td>
                                        <td>
                                            <span class="badge-status status-{{ $b->status }}">
                                                {{ str_replace('_', ' ', ucfirst($b->status)) }}
                                            </span>
                                        </td>
                                        <td>
                                            @if($b->balanceUsd() <= 0.01)
                                                <span class="badge bg-success-subtle text-success fw-bold">PAID</span>
                                            @else
                                                <span class="fw-bold text-danger">${{ number_format($b->balanceUsd(), 0) }}</span>
                                            @endif
                                        </td>
                                        <td class="text-end">
                                            <a href="{{ route('admin.bookings.show', $b->id) }}" class="btn btn-sm btn-outline-secondary py-0 px-2" title="Manage Booking">
                                                <i class="bi bi-arrow-right-circle"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-4 text-muted">
                                            <i class="bi bi-calendar-x fs-3 d-block text-muted mb-2"></i>
                                            No departures scheduled in the next 30 days.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bottom Row: Recent Financial Transactions -->
    @if(Auth::user()->hasRole(['admin', 'accountant']))
        <div class="card shadow-sm mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="bi bi-cash-stack text-success me-2"></i> Recent Payment Receipts</span>
                <a href="{{ route('admin.payments.index') }}" class="btn btn-sm btn-link text-uwt-primary text-decoration-none">
                    View Payments Ledger <i class="bi bi-arrow-right"></i>
                </a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Booking Ref</th>
                                <th>Client</th>
                                <th>Amount Received</th>
                                <th>Exchange Rate</th>
                                <th>Equivalent TZS</th>
                                <th>Method</th>
                                <th>Type</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentPayments as $p)
                                <tr>
                                    <td>{{ $p->paid_on ? $p->paid_on->format('d M Y') : 'N/A' }}</td>
                                    <td>
                                        <a href="{{ route('admin.bookings.show', $p->booking_id) }}" class="fw-bold text-decoration-none text-uwt-primary">
                                            {{ $p->booking->reference ?? 'Booking #' . $p->booking_id }}
                                        </a>
                                    </td>
                                    <td>{{ $p->booking->client->full_name ?? 'N/A' }}</td>
                                    <td class="fw-bold text-success">
                                        ${{ number_format($p->amount_usd, 2) }} USD
                                        @if($p->currency === 'TZS')
                                            <small class="d-block text-muted">({{ number_format($p->amount, 0) }} TZS)</small>
                                        @endif
                                    </td>
                                    <td>1 USD = {{ number_format($p->exchange_rate_used, 0) }} TZS</td>
                                    <td class="text-muted">{{ number_format($p->amount_tzs, 0) }} TZS</td>
                                    <td><span class="badge bg-light text-dark border">{{ ucfirst($p->method) }}</span></td>
                                    <td><span class="badge bg-cream text-uwt-dark">{{ ucfirst($p->type) }}</span></td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center py-4 text-muted">No payments recorded yet.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif

</div>
@endsection
