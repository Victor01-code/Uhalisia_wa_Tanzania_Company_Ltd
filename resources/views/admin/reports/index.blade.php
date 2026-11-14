@extends('layouts.admin')

@section('page_title', 'Financial & Operational Reports')

@section('admin_content')
<div class="container-fluid p-0">
    <!-- Header with Filters & Export Dropdown -->
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
        <div>
            <h2 class="h4 brand-font text-uwt-dark mb-1">Financial & Operational Analytics</h2>
            <p class="text-muted small mb-0">Track revenue, expedition margins, operational costs, and lead conversions.</p>
        </div>

        <div class="d-flex flex-wrap align-items-center gap-2">
            <!-- Filter Form -->
            <form action="{{ route('admin.reports.index') }}" method="GET" class="d-flex align-items-center gap-2">
                <select name="year" class="form-select form-select-sm" onchange="this.form.submit()">
                    @foreach($years as $yr)
                        <option value="{{ $yr }}" {{ $year === $yr ? 'selected' : '' }}>Year {{ $yr }}</option>
                    @endforeach
                </select>

                <select name="month" class="form-select form-select-sm" onchange="this.form.submit()">
                    <option value="all" {{ !$month ? 'selected' : '' }}>Full Year</option>
                    @for($m = 1; $m <= 12; $m++)
                        <option value="{{ $m }}" {{ $month === $m ? 'selected' : '' }}>
                            {{ \Carbon\Carbon::createFromDate($year, $m, 1)->format('F') }}
                        </option>
                    @endfor
                </select>
            </form>

            <!-- Export Dropdown -->
            <div class="dropdown">
                <button class="btn btn-sm btn-outline-success dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="bi bi-file-earmark-spreadsheet me-1"></i> Excel / CSV Exports
                </button>
                <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                    <li>
                        <a class="dropdown-item" href="{{ route('admin.reports.export_monthly', ['year' => $year]) }}">
                            <i class="bi bi-calendar3 me-2 text-primary"></i> Monthly Financial Summary (.csv)
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="{{ route('admin.reports.export_bookings_profit', ['year' => $year]) }}">
                            <i class="bi bi-compass me-2 text-uwt-primary"></i> Safari Profitability Ledger (.csv)
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="{{ route('admin.reports.export_payments', ['year' => $year]) }}">
                            <i class="bi bi-cash-stack me-2 text-success"></i> Master Payments Register (.csv)
                        </a>
                    </li>
                </ul>
            </div>

            <a href="{{ route('admin.reports.audit_logs') }}" class="btn btn-sm btn-outline-secondary" title="View sensitive data access logs">
                <i class="bi bi-shield-check me-1"></i> Audit Logs
            </a>
        </div>
    </div>

    <!-- Financial KPI Cards -->
    <div class="row g-3 mb-4">
        <div class="col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm p-3 bg-white h-100">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <span class="text-muted small text-uppercase fw-semibold">Net Income Collected</span>
                    <span class="badge bg-success-subtle text-success"><i class="bi bi-arrow-up-right"></i> Revenue</span>
                </div>
                <h4 class="fw-bold mb-0 text-success">${{ number_format($incomeUsd, 2) }} USD</h4>
                <div class="small text-muted mt-1">{{ number_format($incomeTzs, 0) }} TZS</div>
                <div class="small text-muted" style="font-size: 0.72rem;">From company-handled bookings</div>
            </div>
        </div>

        <div class="col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm p-3 bg-white h-100">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <span class="text-muted small text-uppercase fw-semibold">Total Operational Expenses</span>
                    <span class="badge bg-danger-subtle text-danger"><i class="bi bi-arrow-down-right"></i> Outflow</span>
                </div>
                <h4 class="fw-bold mb-0 text-danger">${{ number_format($expensesUsd, 2) }} USD</h4>
                <div class="small text-muted mt-1">{{ number_format($expensesTzs, 0) }} TZS</div>
                <div class="small text-muted" style="font-size: 0.72rem;">Park fees, fuel, crew & overhead</div>
            </div>
        </div>

        <div class="col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm p-3 bg-white h-100">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <span class="text-muted small text-uppercase fw-semibold">Net Operating Result</span>
                    <span class="badge {{ $netUsd >= 0 ? 'bg-primary-subtle text-primary' : 'bg-danger-subtle text-danger' }}">
                        {{ number_format($marginPct, 1) }}% Margin
                    </span>
                </div>
                <h4 class="fw-bold mb-0 {{ $netUsd >= 0 ? 'text-uwt-primary' : 'text-danger' }}">
                    ${{ number_format($netUsd, 2) }} USD
                </h4>
                <div class="small text-muted mt-1">{{ number_format($netTzs, 0) }} TZS</div>
                <div class="small text-muted" style="font-size: 0.72rem;">Revenue minus total expenses</div>
            </div>
        </div>

        <div class="col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm p-3 bg-white h-100">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <span class="text-muted small text-uppercase fw-semibold">Outstanding Balances</span>
                    <span class="badge bg-warning-subtle text-dark">Due</span>
                </div>
                <h4 class="fw-bold mb-0 text-dark">${{ number_format($totalOutstandingUsd, 2) }} USD</h4>
                <div class="small text-muted mt-1">{{ $outstandingBookings->count() }} bookings pending payment</div>
                <div class="small text-muted" style="font-size: 0.72rem;">Uncollected safari receivables</div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="row g-4 mb-4">
        <!-- Monthly Trend Chart -->
        <div class="col-lg-8">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h5 class="h6 mb-0 fw-bold">
                        <i class="bi bi-bar-chart-line text-uwt-primary me-2"></i> Monthly Revenue vs. Expenses ({{ $year }})
                    </h5>
                    <span class="badge bg-light text-muted border">USD ($)</span>
                </div>
                <div class="card-body p-3">
                    <div style="position: relative; height: 300px; width: 100%;">
                        <canvas id="monthlyTrendChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Expenses by Category Doughnut -->
        <div class="col-lg-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-white py-3">
                    <h5 class="h6 mb-0 fw-bold">
                        <i class="bi bi-pie-chart text-uwt-olive me-2"></i> Expenses by Category
                    </h5>
                </div>
                <div class="card-body p-3 d-flex flex-column justify-content-center">
                    @if(count($expensesByCategory) > 0)
                        <div style="position: relative; height: 220px; width: 100%;">
                            <canvas id="categoryChart"></canvas>
                        </div>
                        <div class="mt-3 small" style="max-height: 120px; overflow-y: auto;">
                            @foreach($expensesByCategory as $cat)
                                <div class="d-flex justify-content-between py-1 border-bottom">
                                    <span class="text-muted">{{ $cat['label'] }}</span>
                                    <strong class="text-danger">${{ number_format($cat['usd'], 2) }}</strong>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-5 text-muted small">
                            <i class="bi bi-receipt fs-3 d-block mb-2"></i>
                            No expense data logged for this period.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Detailed Analysis Tabs -->
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-white border-bottom-0 pt-3 px-4">
            <ul class="nav nav-tabs card-header-tabs" id="reportTabs" role="tablist">
                <li class="nav-item">
                    <button class="nav-link active fw-semibold" id="monthly-tab" data-bs-toggle="tab" data-bs-target="#monthly-table" type="button" role="tab">
                        <i class="bi bi-calendar3 me-1"></i> 12-Month Financial Ledger
                    </button>
                </li>
                <li class="nav-item">
                    <button class="nav-link fw-semibold" id="profit-tab" data-bs-toggle="tab" data-bs-target="#profit-table" type="button" role="tab">
                        <i class="bi bi-compass me-1"></i> Safari Expedition Profitability ({{ $bookingsProfit->count() }})
                    </button>
                </li>
                <li class="nav-item">
                    <button class="nav-link fw-semibold" id="receivables-tab" data-bs-toggle="tab" data-bs-target="#receivables-table" type="button" role="tab">
                        <i class="bi bi-cash me-1"></i> Outstanding Receivables ({{ $outstandingBookings->count() }})
                    </button>
                </li>
                <li class="nav-item">
                    <button class="nav-link fw-semibold" id="leads-tab" data-bs-toggle="tab" data-bs-target="#leads-table" type="button" role="tab">
                        <i class="bi bi-funnel me-1"></i> Lead Conversion ({{ number_format($conversionRate, 1) }}%)
                    </button>
                </li>
            </ul>
        </div>

        <div class="card-body p-4">
            <div class="tab-content" id="reportTabContent">

                <!-- Tab 1: 12-Month Breakdown -->
                <div class="tab-pane fade show active" id="monthly-table" role="tabpanel">
                    <div class="table-responsive">
                        <table class="table table-sm table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Month</th>
                                    <th>Income (USD)</th>
                                    <th>Income (TZS)</th>
                                    <th>Expenses (USD)</th>
                                    <th>Expenses (TZS)</th>
                                    <th>Net Result (USD)</th>
                                    <th>Net Result (TZS)</th>
                                    <th class="text-end">Margin</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($monthlyTrend as $mNum => $m)
                                    <tr>
                                        <td class="fw-bold">{{ $m['month_name'] }}</td>
                                        <td class="text-success">${{ number_format($m['income_usd'], 2) }}</td>
                                        <td class="small text-muted">{{ number_format($m['income_tzs'], 0) }} TZS</td>
                                        <td class="text-danger">${{ number_format($m['expenses_usd'], 2) }}</td>
                                        <td class="small text-muted">{{ number_format($m['expenses_tzs'], 0) }} TZS</td>
                                        <td class="fw-bold {{ $m['net_usd'] >= 0 ? 'text-uwt-primary' : 'text-danger' }}">
                                            ${{ number_format($m['net_usd'], 2) }}
                                        </td>
                                        <td class="small text-muted">{{ number_format($m['net_tzs'], 0) }} TZS</td>
                                        <td class="text-end">
                                            <span class="badge {{ $m['net_usd'] >= 0 ? 'bg-success-subtle text-success' : 'bg-danger-subtle text-danger' }}">
                                                {{ number_format($m['margin_pct'], 1) }}%
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="table-light fw-bold">
                                <tr>
                                    <td>TOTAL</td>
                                    <td class="text-success">${{ number_format($incomeUsd, 2) }}</td>
                                    <td class="small text-muted">{{ number_format($incomeTzs, 0) }} TZS</td>
                                    <td class="text-danger">${{ number_format($expensesUsd, 2) }}</td>
                                    <td class="small text-muted">{{ number_format($expensesTzs, 0) }} TZS</td>
                                    <td class="{{ $netUsd >= 0 ? 'text-uwt-primary' : 'text-danger' }}">${{ number_format($netUsd, 2) }}</td>
                                    <td class="small text-muted">{{ number_format($netTzs, 0) }} TZS</td>
                                    <td class="text-end">
                                        <span class="badge {{ $netUsd >= 0 ? 'bg-success text-white' : 'bg-danger text-white' }}">
                                            {{ number_format($marginPct, 1) }}%
                                        </span>
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>

                <!-- Tab 2: Safari Profitability Ledger -->
                <div class="tab-pane fade" id="profit-table" role="tabpanel">
                    <div class="table-responsive">
                        <table class="table table-sm table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Booking Ref</th>
                                    <th>Client</th>
                                    <th>Tour</th>
                                    <th>Dates</th>
                                    <th>Quoted</th>
                                    <th>Collected</th>
                                    <th>Expenses</th>
                                    <th>Net Profit</th>
                                    <th class="text-end">Margin</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($bookingsProfit as $bp)
                                    @php $b = $bp['booking']; @endphp
                                    <tr>
                                        <td>
                                            <a href="{{ route('admin.bookings.show', $b->id) }}" class="badge bg-cream text-uwt-dark border text-decoration-none">
                                                {{ $b->reference }}
                                            </a>
                                        </td>
                                        <td>
                                            <div class="fw-semibold">{{ $b->client->full_name ?? 'N/A' }}</div>
                                            <small class="text-muted">{{ $b->client->country ?? '' }}</small>
                                        </td>
                                        <td><small>{{ Str::limit($b->title, 25) }}</small></td>
                                        <td><small class="text-muted">{{ $b->start_date ? $b->start_date->format('d M') : '' }} - {{ $b->end_date ? $b->end_date->format('d M Y') : '' }}</small></td>
                                        <td>${{ number_format($bp['quoted_usd'], 2) }}</td>
                                        <td class="text-success fw-semibold">${{ number_format($bp['revenue_usd'], 2) }}</td>
                                        <td class="text-danger">${{ number_format($bp['costs_usd'], 2) }}</td>
                                        <td class="fw-bold {{ $bp['profit_usd'] >= 0 ? 'text-uwt-primary' : 'text-danger' }}">
                                            ${{ number_format($bp['profit_usd'], 2) }}
                                        </td>
                                        <td class="text-end">
                                            <span class="badge {{ $bp['profit_usd'] >= 0 ? 'bg-success-subtle text-success' : 'bg-danger-subtle text-danger' }}">
                                                {{ number_format($bp['margin_pct'], 1) }}%
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="text-center py-4 text-muted">No expeditions matching filter.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Tab 3: Outstanding Receivables -->
                <div class="tab-pane fade" id="receivables-table" role="tabpanel">
                    <div class="table-responsive">
                        <table class="table table-sm table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Booking Ref</th>
                                    <th>Client Name</th>
                                    <th>Travel Dates</th>
                                    <th>Status</th>
                                    <th>Quoted Price</th>
                                    <th>Amount Paid</th>
                                    <th class="text-end">Balance Due</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($outstandingBookings as $b)
                                    <tr>
                                        <td>
                                            <a href="{{ route('admin.bookings.show', $b->id) }}" class="badge bg-cream text-uwt-dark border text-decoration-none">
                                                {{ $b->reference }}
                                            </a>
                                        </td>
                                        <td>
                                            <div class="fw-semibold">{{ $b->client->full_name ?? 'N/A' }}</div>
                                            <small class="text-muted"><a href="mailto:{{ $b->client->email ?? '' }}" class="text-decoration-none">{{ $b->client->email ?? '' }}</a></small>
                                        </td>
                                        <td><small class="text-muted">{{ $b->start_date ? $b->start_date->format('d M Y') : 'TBD' }}</small></td>
                                        <td><span class="badge bg-secondary-subtle text-dark">{{ ucfirst(str_replace('_', ' ', $b->status)) }}</span></td>
                                        <td>${{ number_format($b->netPriceUsd(), 2) }}</td>
                                        <td class="text-success">${{ number_format($b->paidUsd(), 2) }}</td>
                                        <td class="text-end fw-bold text-danger">${{ number_format($b->balanceUsd(), 2) }} USD</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-4 text-success fw-semibold">
                                            <i class="bi bi-check-circle fs-4 d-block mb-1"></i>
                                            All active safari bookings are fully settled! No outstanding balances.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Tab 4: Lead Sources & Conversion -->
                <div class="tab-pane fade" id="leads-table" role="tabpanel">
                    <div class="row g-3 mb-3">
                        <div class="col-md-4">
                            <div class="p-3 border rounded bg-light">
                                <span class="small text-muted d-block">Total Inquiries</span>
                                <h4 class="fw-bold mb-0 text-uwt-dark">{{ $totalLeads }}</h4>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="p-3 border rounded bg-light">
                                <span class="small text-muted d-block">Converted Bookings</span>
                                <h4 class="fw-bold mb-0 text-success">{{ $convertedLeads }}</h4>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="p-3 border rounded bg-light">
                                <span class="small text-muted d-block">Conversion Rate</span>
                                <h4 class="fw-bold mb-0 text-uwt-primary">{{ number_format($conversionRate, 1) }}%</h4>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-sm table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Channel / Source</th>
                                    <th>Total Inquiries</th>
                                    <th>Converted to Trips</th>
                                    <th class="text-end">Conversion Rate</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($leadsBySource as $srcKey => $srcData)
                                    <tr>
                                        <td class="fw-bold text-capitalize">{{ str_replace('_', ' ', $srcKey) }}</td>
                                        <td>{{ $srcData['total'] }}</td>
                                        <td class="text-success fw-semibold">{{ $srcData['converted'] }}</td>
                                        <td class="text-end">
                                            <span class="badge {{ $srcData['rate'] >= 20 ? 'bg-success-subtle text-success' : 'bg-light text-dark border' }}">
                                                {{ number_format($srcData['rate'], 1) }}%
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-4 text-muted">No lead activity found for this period.</td>
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

@push('scripts')
<!-- Chart.js local bundle -->
<script src="{{ asset('vendor/chartjs/chart.umd.min.js') }}"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    // 1. Monthly Revenue vs Expense Chart
    const trendCtx = document.getElementById('monthlyTrendChart');
    if (trendCtx) {
        new Chart(trendCtx, {
            type: 'bar',
            data: {
                labels: {!! json_encode(array_column($monthlyTrend, 'month_name')) !!},
                datasets: [
                    {
                        label: 'Income (USD)',
                        data: {!! json_encode(array_column($monthlyTrend, 'income_usd')) !!},
                        backgroundColor: '#198754',
                        borderRadius: 4,
                    },
                    {
                        label: 'Expenses (USD)',
                        data: {!! json_encode(array_column($monthlyTrend, 'expenses_usd')) !!},
                        backgroundColor: '#dc3545',
                        borderRadius: 4,
                    },
                    {
                        label: 'Net Profit (USD)',
                        data: {!! json_encode(array_column($monthlyTrend, 'net_usd')) !!},
                        type: 'line',
                        borderColor: '#2b5329',
                        backgroundColor: '#2b5329',
                        borderWidth: 2,
                        tension: 0.3,
                        pointRadius: 4,
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'top' },
                    tooltip: {
                        callbacks: {
                            label: function(ctx) {
                                return ctx.dataset.label + ': $' + ctx.parsed.y.toLocaleString(undefined, {minimumFractionDigits: 2});
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(val) { return '$' + val.toLocaleString(); }
                        }
                    }
                }
            }
        });
    }

    // 2. Expenses Category Doughnut Chart
    const catCtx = document.getElementById('categoryChart');
    if (catCtx) {
        const catLabels = {!! json_encode(array_values(array_column($expensesByCategory, 'label'))) !!};
        const catData = {!! json_encode(array_values(array_column($expensesByCategory, 'usd'))) !!};

        if (catData.length > 0) {
            new Chart(catCtx, {
                type: 'doughnut',
                data: {
                    labels: catLabels,
                    datasets: [{
                        data: catData,
                        backgroundColor: [
                            '#e76f51', '#f4a261', '#e9c46a', '#2a9d8f', '#264653',
                            '#457b9d', '#1d3557', '#6d597a', '#b56576', '#a8dadc'
                        ],
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            callbacks: {
                                label: function(ctx) {
                                    return ctx.label + ': $' + ctx.parsed.toLocaleString(undefined, {minimumFractionDigits: 2});
                                }
                            }
                        }
                    }
                }
            });
        }
    }
});
</script>
@endpush
@endsection
