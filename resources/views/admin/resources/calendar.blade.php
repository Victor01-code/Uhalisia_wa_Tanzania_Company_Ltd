@extends('layouts.admin')

@section('page_title', 'Resource Calendar — ' . $monthLabel)

@section('admin_content')
<div class="container-fluid p-0">

    {{-- Header & Month Navigation --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-2">
        <div>
            <h2 class="h4 brand-font text-uwt-dark mb-1">
                <i class="bi bi-calendar-range text-uwt-primary me-2"></i>Resource Assignment Calendar
            </h2>
            <p class="text-muted mb-0 small">Visual overview of vehicles, drivers, and guides assigned to trips.</p>
        </div>

        <div class="d-flex align-items-center gap-2">
            <a href="{{ route('admin.resources.calendar', ['month' => $prevMonth]) }}"
               class="btn btn-outline-secondary btn-sm" title="Previous Month">
                <i class="bi bi-chevron-left"></i>
            </a>
            <span class="fw-bold text-uwt-dark px-2" style="min-width: 130px; text-align: center;">
                {{ $monthLabel }}
            </span>
            <a href="{{ route('admin.resources.calendar', ['month' => $nextMonth]) }}"
               class="btn btn-outline-secondary btn-sm" title="Next Month">
                <i class="bi bi-chevron-right"></i>
            </a>
            <a href="{{ route('admin.resources.calendar') }}"
               class="btn btn-outline-primary btn-sm ms-2" title="Jump to Current Month">
                <i class="bi bi-calendar-check me-1"></i>Today
            </a>
        </div>
    </div>

    {{-- Legend --}}
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body py-2 px-3 d-flex flex-wrap align-items-center gap-3 small">
            <span class="fw-semibold text-muted">Legend:</span>
            <span class="d-inline-flex align-items-center gap-1">
                <span style="width: 14px; height: 14px; border-radius: 3px; background-color: #D1FAE5; border: 1px solid #065F46;" class="d-inline-block"></span>
                Vehicle assigned
            </span>
            <span class="d-inline-flex align-items-center gap-1">
                <span style="width: 14px; height: 14px; border-radius: 3px; background-color: #DBEAFE; border: 1px solid #1D4ED8;" class="d-inline-block"></span>
                Driver assigned
            </span>
            <span class="d-inline-flex align-items-center gap-1">
                <span style="width: 14px; height: 14px; border-radius: 3px; background-color: #FEF3C7; border: 1px solid #92400E;" class="d-inline-block"></span>
                Guide assigned
            </span>
            <span class="d-inline-flex align-items-center gap-1">
                <span style="width: 14px; height: 14px; border-radius: 3px; background-color: #FFFFFF; border: 1px solid #D1D5DB;" class="d-inline-block"></span>
                Available
            </span>
            <span class="d-inline-flex align-items-center gap-1">
                <span style="width: 14px; height: 14px; border-radius: 3px; background: repeating-linear-gradient(45deg, #F3F4F6, #F3F4F6 2px, #E5E7EB 2px, #E5E7EB 4px);" class="d-inline-block"></span>
                Weekend
            </span>
        </div>
    </div>

    {{-- Calendar Grid --}}
    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-sm table-bordered mb-0" style="font-size: 0.75rem; table-layout: fixed;">
                    <thead>
                        <tr>
                            <th class="text-nowrap bg-white" style="min-width: 180px; width: 180px; position: sticky; left: 0; z-index: 2; background: #fff !important;">
                                Resource
                            </th>
                            @for ($d = 1; $d <= $daysInMonth; $d++)
                                @php
                                    $date = \Carbon\Carbon::create($startOfMonth->year, $startOfMonth->month, $d);
                                    $isWeekend = $date->isWeekend();
                                    $isToday = $date->isToday();
                                @endphp
                                <th class="text-center px-0 {{ $isWeekend ? 'bg-light' : '' }} {{ $isToday ? 'border-primary border-2' : '' }}"
                                    style="min-width: 32px; width: 32px;"
                                    title="{{ $date->format('l, d M Y') }}">
                                    <div class="fw-normal text-muted" style="font-size: 0.65rem; line-height: 1;">
                                        {{ $date->format('D') }}
                                    </div>
                                    <div class="fw-bold {{ $isToday ? 'text-primary' : '' }}">
                                        {{ $d }}
                                    </div>
                                </th>
                            @endfor
                        </tr>
                    </thead>
                    <tbody>
                        @php $currentType = ''; @endphp
                        @foreach ($calendarData as $row)
                            {{-- Section header when type changes --}}
                            @if ($currentType !== $row['type'])
                                @php $currentType = $row['type']; @endphp
                                <tr>
                                    <td colspan="{{ $daysInMonth + 1 }}" class="py-1 px-3 fw-bold small text-uppercase"
                                        style="background: var(--uwt-cream-light); color: var(--uwt-dark); letter-spacing: 0.06em;">
                                        @if ($row['type'] === 'vehicle')
                                            <i class="bi bi-truck-front-fill me-1 text-uwt-olive"></i> Vehicles
                                        @elseif ($row['type'] === 'driver')
                                            <i class="bi bi-person-badge-fill me-1 text-primary"></i> Drivers
                                        @else
                                            <i class="bi bi-binoculars-fill me-1 text-uwt-gold"></i> Guides
                                        @endif
                                    </td>
                                </tr>
                            @endif

                            <tr>
                                {{-- Resource name (sticky first column) --}}
                                <td class="text-nowrap text-truncate fw-semibold px-2 py-1"
                                    style="position: sticky; left: 0; z-index: 1; background: #fff; max-width: 180px;"
                                    title="{{ $row['label'] }}">
                                    {{ $row['label'] }}
                                </td>

                                {{-- Day cells --}}
                                @for ($d = 1; $d <= $daysInMonth; $d++)
                                    @php
                                        $assignment = $row['days'][$d];
                                        $date = \Carbon\Carbon::create($startOfMonth->year, $startOfMonth->month, $d);
                                        $isWeekend = $date->isWeekend();
                                        $isToday = $date->isToday();

                                        $bgColor = '';
                                        $borderColor = '';
                                        $tooltip = '';

                                        if ($assignment) {
                                            $bookingRef = $assignment->booking?->reference ?? '—';
                                            $bookingTitle = $assignment->booking?->title ?? '';
                                            $tooltip = "{$bookingRef}: {$bookingTitle} ({$assignment->start_date->format('d M')} – {$assignment->end_date->format('d M')})";

                                            $bgColor = match ($row['type']) {
                                                'vehicle' => '#D1FAE5',
                                                'driver'  => '#DBEAFE',
                                                'guide'   => '#FEF3C7',
                                                default   => '#E5E7EB',
                                            };
                                            $borderColor = match ($row['type']) {
                                                'vehicle' => '#065F46',
                                                'driver'  => '#1D4ED8',
                                                'guide'   => '#92400E',
                                                default   => '#6B7280',
                                            };
                                        } elseif ($isWeekend) {
                                            $bgColor = 'repeating-linear-gradient(45deg, #F9FAFB, #F9FAFB 2px, #F3F4F6 2px, #F3F4F6 4px)';
                                        }
                                    @endphp
                                    <td class="p-0 text-center {{ $isToday ? 'border-primary border-2' : '' }}"
                                        @if ($assignment)
                                            style="background-color: {{ $bgColor }}; cursor: pointer;"
                                            data-bs-toggle="tooltip"
                                            data-bs-placement="top"
                                            title="{{ $tooltip }}"
                                            onclick="window.location='{{ route('admin.bookings.show', $assignment->booking_id) }}'"
                                        @elseif ($isWeekend)
                                            style="background: {{ $bgColor }};"
                                        @endif
                                    >
                                        @if ($assignment)
                                            <div style="width: 100%; height: 26px; display: flex; align-items: center; justify-content: center;">
                                                <span style="color: {{ $borderColor }}; font-weight: 700; font-size: 0.6rem; line-height: 1;" class="text-truncate">
                                                    {{ Str::limit($assignment->booking?->reference ?? '', 9) }}
                                                </span>
                                            </div>
                                        @else
                                            <div style="width: 100%; height: 26px;"></div>
                                        @endif
                                    </td>
                                @endfor
                            </tr>
                        @endforeach

                        @if (count($calendarData) === 0)
                            <tr>
                                <td colspan="{{ $daysInMonth + 1 }}" class="text-center text-muted py-4">
                                    <i class="bi bi-exclamation-triangle me-1"></i>
                                    No active resources found. Add vehicles, drivers, or guides first.
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Summary Stats --}}
    @php
        $totalResources = count($calendarData);
        $totalAssigned = 0;
        foreach ($calendarData as $row) {
            foreach ($row['days'] as $day) {
                if ($day) { $totalAssigned++; break; }
            }
        }
    @endphp
    <div class="row g-3 mt-3">
        <div class="col-md-4">
            <div class="card shadow-sm border-0">
                <div class="card-body py-3 d-flex align-items-center gap-3">
                    <div class="stat-icon bg-uwt-olive bg-opacity-10">
                        <i class="bi bi-truck-front-fill text-uwt-olive"></i>
                    </div>
                    <div>
                        <div class="h5 mb-0 fw-bold text-uwt-dark">{{ $totalResources }}</div>
                        <div class="text-muted small">Active Resources</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-sm border-0">
                <div class="card-body py-3 d-flex align-items-center gap-3">
                    <div class="stat-icon bg-primary bg-opacity-10">
                        <i class="bi bi-calendar-check text-primary"></i>
                    </div>
                    <div>
                        <div class="h5 mb-0 fw-bold text-uwt-dark">{{ $totalAssigned }}</div>
                        <div class="text-muted small">Resources with Assignments This Month</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-sm border-0">
                <div class="card-body py-3 d-flex align-items-center gap-3">
                    <div class="stat-icon bg-success bg-opacity-10">
                        <i class="bi bi-check-circle text-success"></i>
                    </div>
                    <div>
                        <div class="h5 mb-0 fw-bold text-uwt-dark">{{ max(0, $totalResources - $totalAssigned) }}</div>
                        <div class="text-muted small">Fully Available This Month</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Initialize Bootstrap tooltips for assignment cells
    document.addEventListener('DOMContentLoaded', function () {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function (el) {
            return new bootstrap.Tooltip(el);
        });
    });
</script>
@endpush
@endsection
