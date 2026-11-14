<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; line-height: 1.6; color: #2B1B12; margin: 0; padding: 0; background-color: #FAF6F0; }
        .wrapper { max-width: 660px; margin: 25px auto; background: #ffffff; border-radius: 8px; overflow: hidden; border: 1px solid #EFEAE4; }
        .header { background: #2B1B12; padding: 25px; text-align: center; border-bottom: 3px solid #C9642C; }
        .content { padding: 30px; }
        .footer { background: #FAF6F0; padding: 20px; text-align: center; font-size: 12px; color: #777; border-top: 1px solid #EFEAE4; }
        .kpi-grid { display: flex; flex-wrap: wrap; gap: 12px; margin: 20px 0; }
        .kpi-card { flex: 1; min-width: 120px; background: #FAF6F0; border-radius: 8px; padding: 15px; text-align: center; border: 1px solid #EFEAE4; }
        .kpi-value { font-size: 28px; font-weight: bold; color: #C9642C; }
        .kpi-label { font-size: 11px; color: #888; text-transform: uppercase; letter-spacing: 0.04em; margin-top: 4px; }
        table { width: 100%; border-collapse: collapse; margin: 12px 0; font-size: 13px; }
        th { background: #FAF6F0; color: #666; text-align: left; padding: 8px 10px; font-weight: 600; text-transform: uppercase; font-size: 11px; letter-spacing: 0.03em; border-bottom: 2px solid #EFEAE4; }
        td { padding: 8px 10px; border-bottom: 1px solid #f0ebe5; }
        .section-title { color: #2B1B12; font-size: 15px; font-weight: 700; border-bottom: 2px solid #C9642C; padding-bottom: 6px; margin-top: 28px; margin-bottom: 12px; }
        .badge { display: inline-block; padding: 2px 8px; border-radius: 4px; font-size: 11px; font-weight: 600; }
        .badge-warning { background: #FFF3CD; color: #856404; }
        .badge-success { background: #D4EDDA; color: #155724; }
        .badge-info { background: #D1ECF1; color: #0C5460; }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="header">
            <h2 style="color: #ffffff; margin: 0; font-size: 22px;">📊 Weekly Operations Report</h2>
            <p style="color: #D98B3F; margin: 5px 0 0; font-size: 13px;">{{ $companyName }} &bull; {{ $weekLabel }}</p>
        </div>
        <div class="content">

            {{-- KPI Cards --}}
            <div class="kpi-grid">
                <div class="kpi-card">
                    <div class="kpi-value">{{ $upcomingTrips->count() }}</div>
                    <div class="kpi-label">Upcoming Trips (7d)</div>
                </div>
                <div class="kpi-card">
                    <div class="kpi-value">{{ $activeTrips }}</div>
                    <div class="kpi-label">Active Now</div>
                </div>
                <div class="kpi-card">
                    <div class="kpi-value">{{ $newLeads }}</div>
                    <div class="kpi-label">New Leads</div>
                </div>
                <div class="kpi-card">
                    <div class="kpi-value">{{ $newBookings }}</div>
                    <div class="kpi-label">New Bookings</div>
                </div>
            </div>

            <div class="kpi-grid">
                <div class="kpi-card">
                    <div class="kpi-value" style="color: #198754;">${{ number_format($weeklyIncomeUsd, 0) }}</div>
                    <div class="kpi-label">Weekly Income (USD)</div>
                </div>
                <div class="kpi-card">
                    <div class="kpi-value" style="color: #DC3545;">${{ number_format($totalOutstandingUsd, 0) }}</div>
                    <div class="kpi-label">Outstanding Balances</div>
                </div>
            </div>

            {{-- Upcoming Trips Table --}}
            @if($upcomingTrips->count())
            <div class="section-title">🗓 Upcoming Safaris (Next 7 Days)</div>
            <table>
                <thead>
                    <tr>
                        <th>Reference</th>
                        <th>Client</th>
                        <th>Start</th>
                        <th>Guests</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($upcomingTrips as $trip)
                    <tr>
                        <td><strong>{{ $trip->reference }}</strong></td>
                        <td>{{ $trip->client->full_name ?? 'N/A' }}</td>
                        <td>{{ $trip->start_date->format('M d') }}</td>
                        <td>{{ $trip->adults }}A{{ $trip->children ? ' + ' . $trip->children . 'C' : '' }}</td>
                        <td>
                            @php
                                $statusColors = [
                                    'confirmed' => 'badge-success',
                                    'paid_in_full' => 'badge-success',
                                    'in_progress' => 'badge-info',
                                    'quote_sent' => 'badge-warning',
                                ];
                            @endphp
                            <span class="badge {{ $statusColors[$trip->status] ?? 'badge-info' }}">
                                {{ str_replace('_', ' ', ucfirst($trip->status)) }}
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @else
            <p style="color: #888; font-style: italic;">No trips starting in the next 7 days.</p>
            @endif

            {{-- Outstanding Balances --}}
            @if($outstandingBookings->count())
            <div class="section-title">💰 Outstanding Balances</div>
            <table>
                <thead>
                    <tr>
                        <th>Reference</th>
                        <th>Client</th>
                        <th>Start Date</th>
                        <th style="text-align: right;">Balance (USD)</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($outstandingBookings->sortByDesc(fn ($b) => $b->balanceUsd())->take(15) as $ob)
                    <tr>
                        <td><strong>{{ $ob->reference }}</strong></td>
                        <td>{{ $ob->client->full_name ?? 'N/A' }}</td>
                        <td>{{ $ob->start_date ? $ob->start_date->format('M d, Y') : '—' }}</td>
                        <td style="text-align: right; color: #DC3545; font-weight: bold;">
                            ${{ number_format($ob->balanceUsd(), 2) }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @if($outstandingBookings->count() > 15)
            <p style="color: #888; font-size: 12px;">Showing top 15 of {{ $outstandingBookings->count() }} bookings with outstanding balances.</p>
            @endif
            @endif

            <p style="margin-top: 30px; padding-top: 15px; border-top: 1px solid #eee; color: #888; font-size: 12px;">
                This is an automated weekly report generated by the {{ $companyName }} Portal.<br>
                Report generated: {{ now()->format('l, F j, Y \\a\\t H:i T') }}
            </p>
        </div>
        <div class="footer">
            <p>&copy; {{ date('Y') }} {{ $companyName }}. All Rights Reserved.</p>
        </div>
    </div>
</body>
</html>
