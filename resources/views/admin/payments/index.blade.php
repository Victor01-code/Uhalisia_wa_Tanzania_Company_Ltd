@extends('layouts.admin')

@section('page_title', 'Payments Ledger')

@section('admin_content')
<div class="container-fluid p-0">
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-2">
        <div>
            <h2 class="h4 brand-font text-uwt-dark mb-1">Payments Ledger</h2>
            <p class="text-muted small mb-0">Record and track client deposits, balance settlements, and wire receipts.</p>
        </div>
        <div>
            <a href="{{ route('admin.payments.create') }}" class="btn btn-uwt-olive">
                <i class="bi bi-plus-circle me-1"></i> Record New Payment
            </a>
        </div>
    </div>

    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Booking Ref</th>
                            <th>Client Name</th>
                            <th>Amount (USD)</th>
                            <th>Currency Paid</th>
                            <th>Exchange Rate</th>
                            <th>TZS Equivalent</th>
                            <th>Method</th>
                            <th>Reference</th>
                            <th>Type</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($payments as $p)
                            <tr>
                                <td class="small">{{ $p->paid_on ? $p->paid_on->format('d M Y') : 'N/A' }}</td>
                                <td>
                                    <a href="{{ route('admin.bookings.show', $p->booking_id) }}" class="fw-bold text-uwt-primary text-decoration-none">
                                        {{ $p->booking->reference ?? 'Booking #' . $p->booking_id }}
                                    </a>
                                </td>
                                <td>{{ $p->booking->client->full_name ?? 'N/A' }}</td>
                                <td class="fw-bold text-success">${{ number_format($p->amount_usd, 2) }}</td>
                                <td>
                                    @if($p->currency === 'TZS')
                                        {{ number_format($p->amount, 0) }} TZS
                                    @else
                                        ${{ number_format($p->amount, 2) }} USD
                                    @endif
                                </td>
                                <td class="small">{{ number_format($p->exchange_rate_used, 0) }} TZS</td>
                                <td class="small text-muted">{{ number_format($p->amount_tzs, 0) }} TZS</td>
                                <td><span class="badge bg-light text-dark border">{{ ucfirst($p->method) }}</span></td>
                                <td><small class="text-muted">{{ $p->reference ?? '-' }}</small></td>
                                <td><span class="badge bg-cream text-uwt-dark">{{ ucfirst($p->type) }}</span></td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="text-center py-5 text-muted">No payments recorded yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if($payments->hasPages())
            <div class="card-footer bg-white border-top p-3">
                {{ $payments->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </div>
</div>
@endsection
