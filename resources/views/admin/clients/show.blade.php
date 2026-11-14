@extends('layouts.admin')

@section('page_title', 'Client Profile: ' . $client->full_name)

@section('admin_content')
<div class="container-fluid p-0">

    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-2">
        <div class="d-flex align-items-center gap-2">
            <a href="{{ route('admin.clients.index') }}" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-arrow-left"></i> Back to Clients
            </a>
            <h2 class="h4 brand-font text-uwt-dark mb-0">{{ $client->full_name }}</h2>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.bookings.create', ['client_id' => $client->id]) }}" class="btn btn-uwt-primary btn-sm">
                <i class="bi bi-calendar-plus me-1"></i> Create Booking for Client
            </a>
            <a href="{{ route('admin.clients.edit', $client->id) }}" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-pencil me-1"></i> Edit Profile
            </a>
        </div>
    </div>

    <div class="row g-4">
        <!-- Client Info Column -->
        <div class="col-lg-4">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white">
                    <h5 class="h6 mb-0 fw-bold"><i class="bi bi-person-badge text-uwt-primary me-2"></i> Client Information</h5>
                </div>
                <div class="card-body p-4">
                    <div class="mb-3">
                        <label class="small text-muted d-block">Full Name</label>
                        <span class="fw-bold fs-6">{{ $client->full_name }}</span>
                    </div>

                    <div class="mb-3">
                        <label class="small text-muted d-block">Email Address</label>
                        <a href="mailto:{{ $client->email }}" class="text-uwt-primary fw-semibold">{{ $client->email }}</a>
                    </div>

                    <div class="mb-3">
                        <label class="small text-muted d-block">Phone / WhatsApp</label>
                        <span>{{ $client->phone ?? 'Not provided' }}</span>
                    </div>

                    <div class="mb-3">
                        <label class="small text-muted d-block">Country of Residence</label>
                        <span>{{ $client->country ?? 'Not specified' }}</span>
                    </div>

                    <div class="mb-3">
                        <label class="small text-muted d-block">Member Since</label>
                        <span>{{ $client->created_at->format('d M Y') }}</span>
                    </div>

                    @if($client->notes)
                        <div class="border-top pt-3">
                            <label class="small text-muted d-block fw-bold mb-1">Internal Notes:</label>
                            <p class="small text-muted mb-0 bg-light p-2 rounded">{{ $client->notes }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Bookings & Leads Column -->
        <div class="col-lg-8">
            <!-- Bookings List -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="h6 mb-0 fw-bold"><i class="bi bi-calendar-check text-uwt-gold me-2"></i> Bookings & Itineraries</h5>
                    <span class="badge bg-cream text-uwt-dark">{{ $client->bookings->count() }} Trips</span>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead>
                                <tr>
                                    <th>Ref</th>
                                    <th>Trip Title</th>
                                    <th>Dates</th>
                                    <th>Status</th>
                                    <th>Total USD</th>
                                    <th>Balance</th>
                                    <th class="text-end">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($client->bookings as $b)
                                    <tr>
                                        <td>
                                            <a href="{{ route('admin.bookings.show', $b->id) }}" class="fw-bold text-uwt-primary text-decoration-none">
                                                {{ $b->reference }}
                                            </a>
                                        </td>
                                        <td><div class="fw-semibold small">{{ $b->title }}</div></td>
                                        <td class="small">{{ $b->start_date ? $b->start_date->format('d M Y') : 'TBD' }}</td>
                                        <td><span class="badge-status status-{{ $b->status }}">{{ ucfirst($b->status) }}</span></td>
                                        <td class="small fw-semibold">${{ number_format($b->netPriceUsd(), 2) }}</td>
                                        <td>
                                            @if($b->balanceUsd() <= 0.01)
                                                <span class="badge bg-success-subtle text-success small">PAID</span>
                                            @else
                                                <span class="small fw-bold text-danger">${{ number_format($b->balanceUsd(), 2) }}</span>
                                            @endif
                                        </td>
                                        <td class="text-end">
                                            <a href="{{ route('admin.bookings.show', $b->id) }}" class="btn btn-sm btn-outline-secondary py-0 px-2">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-4 text-muted">No bookings recorded for this client yet.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Leads History -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white">
                    <h5 class="h6 mb-0 fw-bold"><i class="bi bi-inbox text-uwt-primary me-2"></i> Inquiries from this Client</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Tour Interest</th>
                                    <th>Source</th>
                                    <th>Status</th>
                                    <th class="text-end">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($client->leads as $l)
                                    <tr>
                                        <td class="small">{{ $l->created_at->format('d M Y') }}</td>
                                        <td class="small fw-semibold">{{ $l->safari_interest ?? 'Custom' }}</td>
                                        <td><span class="badge bg-light text-dark border small">{{ $l->source }}</span></td>
                                        <td><span class="badge-status status-{{ $l->status }}">{{ ucfirst($l->status) }}</span></td>
                                        <td class="text-end">
                                            <a href="{{ route('admin.leads.show', $l->id) }}" class="btn btn-sm btn-outline-secondary py-0 px-2">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-3 text-muted small">No past inquiry leads recorded.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
