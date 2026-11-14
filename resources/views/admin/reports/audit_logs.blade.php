@extends('layouts.admin')

@section('page_title', 'Sensitive Data & Activity Audit Log')

@section('admin_content')
<div class="container-fluid p-0">
    <!-- Header -->
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
        <div>
            <div class="d-flex align-items-center gap-2 mb-1">
                <a href="{{ route('admin.reports.index') }}" class="btn btn-sm btn-outline-secondary">
                    <i class="bi bi-arrow-left me-1"></i> Back to Analytics
                </a>
                <h2 class="h4 brand-font text-uwt-dark mb-0">Sensitive Data & Financial Audit Log</h2>
            </div>
            <p class="text-muted small mb-0">Immutable tracking of report exports, financial record deletions, document access, and client passport views.</p>
        </div>

        <div>
            <form action="{{ route('admin.reports.audit_logs') }}" method="GET" class="d-flex align-items-center gap-2">
                <select name="action" class="form-select form-select-sm" onchange="this.form.submit()">
                    <option value="">All Audited Actions</option>
                    <option value="export_monthly_report" {{ request('action') === 'export_monthly_report' ? 'selected' : '' }}>Export Monthly Report</option>
                    <option value="export_bookings_profit" {{ request('action') === 'export_bookings_profit' ? 'selected' : '' }}>Export Bookings Profit</option>
                    <option value="export_payments" {{ request('action') === 'export_payments' ? 'selected' : '' }}>Export Payments</option>
                    <option value="delete_payment" {{ request('action') === 'delete_payment' ? 'selected' : '' }}>Delete Payment</option>
                    <option value="delete_expense" {{ request('action') === 'delete_expense' ? 'selected' : '' }}>Delete Expense</option>
                    <option value="download_document" {{ request('action') === 'download_document' ? 'selected' : '' }}>Download Document</option>
                    <option value="delete_document" {{ request('action') === 'delete_document' ? 'selected' : '' }}>Delete Document</option>
                    <option value="view_report" {{ request('action') === 'view_report' ? 'selected' : '' }}>View Report Dashboard</option>
                </select>
                @if(request()->filled('action'))
                    <a href="{{ route('admin.reports.audit_logs') }}" class="btn btn-sm btn-outline-secondary" title="Clear filter">
                        <i class="bi bi-x-circle"></i>
                    </a>
                @endif
            </form>
        </div>
    </div>

    <!-- Audit Log Table -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0 fs-6 text-uwt-dark font-monospace">
                <i class="bi bi-shield-lock-fill text-primary me-2"></i>Security Audit Events ({{ $logs->total() }})
            </h5>
            <span class="badge bg-light text-dark border">Auto-recorded with client IP & user session</span>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light text-muted small text-uppercase">
                    <tr>
                        <th style="width: 170px;">Timestamp</th>
                        <th>User / Actor</th>
                        <th>Action</th>
                        <th>Resource</th>
                        <th>Details & Reason</th>
                        <th style="width: 140px;">IP Address</th>
                    </tr>
                </thead>
                <tbody class="small">
                    @forelse($logs as $log)
                        <tr>
                            <td>
                                <div class="fw-bold text-dark">{{ $log->created_at->format('M d, Y H:i:s') }}</div>
                                <div class="text-muted" style="font-size: 0.75rem;">{{ $log->created_at->diffForHumans() }}</div>
                            </td>
                            <td>
                                @if($log->user)
                                    <div class="fw-semibold text-dark">{{ $log->user->name }}</div>
                                    <div class="text-muted" style="font-size: 0.75rem;">
                                        <span class="badge bg-secondary-subtle text-secondary py-0 px-1">{{ ucfirst($log->user->role) }}</span>
                                        {{ $log->user->email }}
                                    </div>
                                @else
                                    <span class="badge bg-light text-muted border">System / Anonymous</span>
                                @endif
                            </td>
                            <td>
                                @php
                                    $actionColors = [
                                        'delete_payment' => 'danger',
                                        'delete_expense' => 'danger',
                                        'delete_document' => 'danger',
                                        'export_monthly_report' => 'success',
                                        'export_bookings_profit' => 'success',
                                        'export_payments' => 'success',
                                        'download_document' => 'info',
                                        'view_report' => 'secondary',
                                    ];
                                    $badgeColor = $actionColors[$log->action] ?? 'primary';
                                @endphp
                                <span class="badge bg-{{ $badgeColor }}-subtle text-{{ $badgeColor }} border border-{{ $badgeColor }}-subtle px-2 py-1">
                                    {{ str_replace('_', ' ', strtoupper($log->action)) }}
                                </span>
                            </td>
                            <td>
                                @if($log->resource_type)
                                    <span class="text-muted text-uppercase fw-semibold">{{ $log->resource_type }}</span>
                                    @if($log->resource_id)
                                        <span class="badge bg-light text-dark border">#{{ $log->resource_id }}</span>
                                    @endif
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                <div class="text-wrap" style="max-width: 450px;">
                                    {{ $log->details ?: 'No additional details provided' }}
                                </div>
                            </td>
                            <td>
                                <span class="font-monospace text-muted">{{ $log->ip_address ?: 'Unknown' }}</span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">
                                <i class="bi bi-shield-check fs-2 text-success d-block mb-2"></i>
                                No audit events logged for the specified criteria.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($logs->hasPages())
            <div class="card-footer bg-white py-3 border-top">
                {{ $logs->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
