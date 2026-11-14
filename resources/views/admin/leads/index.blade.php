@extends('layouts.admin')

@section('page_title', 'Leads & Inquiries')

@section('admin_content')
<div class="container-fluid p-0">

    <!-- Page Header & Actions -->
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-2">
        <div>
            <h2 class="h4 brand-font text-uwt-dark mb-1">Leads & Inquiries</h2>
            <p class="text-muted small mb-0">Track and convert incoming website quotes, /book submissions, and manual inquiries.</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.leads.create') }}" class="btn btn-uwt-primary">
                <i class="bi bi-person-plus-fill me-1"></i> Log Manual Lead
            </a>
        </div>
    </div>

    <!-- Filter Bar Card -->
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body p-3">
            <form action="{{ route('admin.leads.index') }}" method="GET" class="row g-2 align-items-end">
                <div class="col-md-3">
                    <label for="search" class="form-label small text-muted mb-1">Search Keywords</label>
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-light"><i class="bi bi-search"></i></span>
                        <input type="text" name="search" id="search" class="form-control"
                               placeholder="Name, email, country, tour..." value="{{ request('search') }}">
                    </div>
                </div>

                <div class="col-md-2 col-6">
                    <label for="status" class="form-label small text-muted mb-1">Status</label>
                    <select name="status" id="status" class="form-select form-select-sm">
                        <option value="">All Statuses</option>
                        @foreach($statuses as $st)
                            <option value="{{ $st }}" {{ request('status') === $st ? 'selected' : '' }}>
                                {{ ucfirst($st) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2 col-6">
                    <label for="source" class="form-label small text-muted mb-1">Source</label>
                    <select name="source" id="source" class="form-select form-select-sm">
                        <option value="">All Sources</option>
                        @foreach($sources as $src)
                            <option value="{{ $src }}" {{ request('source') === $src ? 'selected' : '' }}>
                                {{ $src === 'website_quote_form' ? 'Website Form' : ($src === 'book_form' ? '/book Form' : 'Manual') }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2 col-6">
                    <label for="date_from" class="form-label small text-muted mb-1">From Date</label>
                    <input type="date" name="date_from" id="date_from" class="form-control form-control-sm" value="{{ request('date_from') }}">
                </div>

                <div class="col-md-2 col-6">
                    <label for="date_to" class="form-label small text-muted mb-1">To Date</label>
                    <input type="date" name="date_to" id="date_to" class="form-control form-control-sm" value="{{ request('date_to') }}">
                </div>

                <div class="col-12 col-md-auto d-flex gap-1">
                    <button type="submit" class="btn btn-sm btn-uwt-primary px-3" title="Apply Filters">
                        <i class="bi bi-funnel"></i> Filter
                    </button>
                    @if(request()->hasAny(['search', 'status', 'source', 'date_from', 'date_to']))
                        <a href="{{ route('admin.leads.index') }}" class="btn btn-sm btn-outline-secondary" title="Reset Filters">
                            <i class="bi bi-x-lg"></i>
                        </a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <!-- Leads Table Card -->
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Lead Name</th>
                            <th>Email & Phone</th>
                            <th>Country</th>
                            <th>Safari Interest</th>
                            <th>Source</th>
                            <th>Status</th>
                            <th>Assigned To</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($leads as $lead)
                            <tr>
                                <td class="text-nowrap small text-muted">
                                    {{ $lead->created_at->format('d M Y') }}
                                    <div style="font-size: 0.75rem;">{{ $lead->created_at->format('H:i') }}</div>
                                </td>
                                <td>
                                    <a href="{{ route('admin.leads.show', $lead->id) }}" class="fw-bold text-dark text-decoration-none">
                                        {{ $lead->name }}
                                    </a>
                                    @if($lead->client)
                                        <span class="badge bg-cream text-uwt-dark small ms-1" title="Matched to Client Record">
                                            <i class="bi bi-person-check-fill text-success"></i> Client
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    <div class="small"><a href="mailto:{{ $lead->email }}" class="text-decoration-none text-muted">{{ $lead->email }}</a></div>
                                </td>
                                <td>
                                    <span class="small">{{ $lead->country ?? '-' }}</span>
                                </td>
                                <td>
                                    <span class="small d-block text-truncate" style="max-width: 180px;" title="{{ $lead->safari_interest }}">
                                        {{ $lead->safari_interest ?? 'Custom Inquiry' }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark border small">
                                        {{ $lead->source === 'website_quote_form' ? 'Website' : ($lead->source === 'book_form' ? '/book' : 'Manual') }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge-status status-{{ $lead->status }}">
                                        {{ ucfirst($lead->status) }}
                                    </span>
                                </td>
                                <td class="small text-muted">
                                    {{ $lead->assignedUser->name ?? 'Unassigned' }}
                                </td>
                                <td class="text-end text-nowrap">
                                    <a href="{{ route('admin.leads.show', $lead->id) }}" class="btn btn-sm btn-outline-secondary py-1 px-2" title="View Lead & Actions">
                                        <i class="bi bi-eye"></i> View
                                    </a>
                                    @if($lead->status !== 'won')
                                        <form action="{{ route('admin.leads.convert', $lead->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Convert this lead to a confirmed Client and Booking?');">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-uwt-primary py-1 px-2" title="Convert to Booking">
                                                <i class="bi bi-arrow-right-circle"></i> Convert
                                            </button>
                                        </form>
                                    @else
                                        <span class="badge bg-success-subtle text-success small py-1 px-2">Won</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center py-5 text-muted">
                                    <i class="bi bi-inbox fs-2 d-block mb-2 text-muted"></i>
                                    No leads found matching your criteria.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if($leads->hasPages())
            <div class="card-footer bg-white border-top p-3">
                {{ $leads->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </div>

</div>
@endsection
