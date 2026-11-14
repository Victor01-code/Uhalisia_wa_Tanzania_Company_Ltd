@extends('layouts.admin')

@section('page_title', 'Client Directory')

@section('admin_content')
<div class="container-fluid p-0">

    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-2">
        <div>
            <h2 class="h4 brand-font text-uwt-dark mb-1">Client Directory</h2>
            <p class="text-muted small mb-0">Registered safari and tour travelers with their past and active bookings.</p>
        </div>
        <div>
            <a href="{{ route('admin.clients.create') }}" class="btn btn-uwt-primary">
                <i class="bi bi-person-plus-fill me-1"></i> Add New Client
            </a>
        </div>
    </div>

    <!-- Search Card -->
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body p-3">
            <form action="{{ route('admin.clients.index') }}" method="GET" class="row g-2 align-items-center">
                <div class="col-md-5">
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class="bi bi-search"></i></span>
                        <input type="text" name="search" class="form-control" placeholder="Search by name, email, phone, or country..." value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-uwt-primary w-100">Search</button>
                </div>
                @if(request('search'))
                    <div class="col-md-2">
                        <a href="{{ route('admin.clients.index') }}" class="btn btn-outline-secondary w-100">Clear</a>
                    </div>
                @endif
            </form>
        </div>
    </div>

    <!-- Clients Table -->
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th>Client Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Country</th>
                            <th>Total Bookings</th>
                            <th>Total Inquiries</th>
                            <th>Member Since</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($clients as $c)
                            <tr>
                                <td>
                                    <a href="{{ route('admin.clients.show', $c->id) }}" class="fw-bold text-dark text-decoration-none">
                                        {{ $c->full_name }}
                                    </a>
                                </td>
                                <td><a href="mailto:{{ $c->email }}" class="text-decoration-none text-muted small">{{ $c->email }}</a></td>
                                <td class="small">{{ $c->phone ?? '-' }}</td>
                                <td class="small">{{ $c->country ?? '-' }}</td>
                                <td>
                                    <span class="badge bg-cream text-uwt-dark border">
                                        {{ $c->bookings_count }} Bookings
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark border">
                                        {{ $c->leads_count }} Leads
                                    </span>
                                </td>
                                <td class="small text-muted">{{ $c->created_at->format('d M Y') }}</td>
                                <td class="text-end text-nowrap">
                                    <a href="{{ route('admin.clients.show', $c->id) }}" class="btn btn-sm btn-outline-secondary py-1 px-2" title="View Client">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.clients.edit', $c->id) }}" class="btn btn-sm btn-outline-secondary py-1 px-2" title="Edit Client">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-5 text-muted">
                                    <i class="bi bi-people fs-2 d-block mb-2 text-muted"></i>
                                    No clients found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if($clients->hasPages())
            <div class="card-footer bg-white border-top p-3">
                {{ $clients->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </div>

</div>
@endsection
