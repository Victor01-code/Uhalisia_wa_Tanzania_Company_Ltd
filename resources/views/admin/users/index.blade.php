@extends('layouts.admin')

@section('page_title', 'Staff Accounts')

@section('admin_content')
<div class="container-fluid p-0">

    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-2">
        <div>
            <h2 class="h4 brand-font text-uwt-dark mb-1">Staff Accounts & Access</h2>
            <p class="text-muted small mb-0">Manage portal user accounts and assign operational roles (Admin, Agent, Accountant).</p>
        </div>
        <div>
            <a href="{{ route('admin.users.create') }}" class="btn btn-uwt-primary">
                <i class="bi bi-person-plus-fill me-1"></i> Add Staff User
            </a>
        </div>
    </div>

    <!-- Users Table -->
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th>Staff Member</th>
                            <th>Email Address</th>
                            <th>Portal Role</th>
                            <th>Permissions Summary</th>
                            <th>Status</th>
                            <th>Created On</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $u)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="rounded-circle bg-uwt-primary text-white d-flex align-items-center justify-content-center fw-bold" style="width: 32px; height: 32px; font-size: 0.8rem;">
                                            {{ strtoupper(substr($u->name, 0, 1)) }}
                                        </div>
                                        <span class="fw-bold text-dark">{{ $u->name }}</span>
                                    </div>
                                </td>
                                <td><a href="mailto:{{ $u->email }}" class="text-muted small text-decoration-none">{{ $u->email }}</a></td>
                                <td>
                                    <span class="badge {{ $u->role === 'admin' ? 'bg-danger' : ($u->role === 'accountant' ? 'bg-success' : 'bg-primary') }} text-uppercase" style="font-size: 0.7rem;">
                                        {{ $u->role }}
                                    </span>
                                </td>
                                <td>
                                    <small class="text-muted">
                                        @if($u->role === 'admin')
                                            Full administrative privileges across portal
                                        @elseif($u->role === 'agent')
                                            Leads, bookings, itineraries, resources
                                        @elseif($u->role === 'accountant')
                                            Payments, expenses, exchange rates, reports
                                        @endif
                                    </small>
                                </td>
                                <td>
                                    @if($u->active)
                                        <span class="badge bg-success-subtle text-success small">Active</span>
                                    @else
                                        <span class="badge bg-danger-subtle text-danger small">Deactivated</span>
                                    @endif
                                </td>
                                <td class="small text-muted">{{ $u->created_at->format('d M Y') }}</td>
                                <td class="text-end">
                                    <a href="{{ route('admin.users.edit', $u->id) }}" class="btn btn-sm btn-outline-secondary py-1 px-2" title="Edit Staff & Password">
                                        <i class="bi bi-pencil"></i> Edit
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        @if($users->hasPages())
            <div class="card-footer bg-white border-top p-3">
                {{ $users->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </div>

</div>
@endsection
