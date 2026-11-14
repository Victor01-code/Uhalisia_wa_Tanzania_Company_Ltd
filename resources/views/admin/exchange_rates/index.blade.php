@extends('layouts.admin')

@section('page_title', 'Monthly Exchange Rates')

@section('admin_content')
<div class="container-fluid p-0">
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-2">
        <div>
            <h2 class="h4 brand-font text-uwt-dark mb-1">Exchange Rates (TZS per USD)</h2>
            <p class="text-muted small mb-0">Monthly accounting conversion rates for USD safari quotes converted to Tanzanian Shillings.</p>
        </div>
        <div class="d-flex align-items-center gap-2">
            <label class="small fw-semibold text-muted mb-0">Calendar Year:</label>
            <div class="btn-group btn-group-sm">
                @foreach($years as $yr)
                    <a href="{{ route('admin.exchange-rates.index', ['year' => $yr]) }}" class="btn {{ $year === $yr ? 'btn-uwt-primary fw-bold' : 'btn-outline-secondary' }}">
                        {{ $yr }}
                    </a>
                @endforeach
            </div>
        </div>
    </div>

    <div class="card shadow-sm border-0 mb-4" style="max-width: 800px;">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <h5 class="h6 mb-0 fw-bold"><i class="bi bi-calendar3 me-2 text-uwt-primary"></i> Exchange Conversion Table &bull; {{ $year }}</h5>
            <span class="badge bg-cream text-uwt-dark border">12 Monthly Conversion Slices</span>
        </div>
        <div class="card-body p-4">
            <form action="{{ route('admin.exchange-rates.update') }}" method="POST">
                @csrf
                <input type="hidden" name="year" value="{{ $year }}">

                <div class="row g-3">
                    @for($m = 1; $m <= 12; $m++)
                        @php
                            $monthName = \Carbon\Carbon::createFromDate($year, $m, 1)->format('F');
                            $val = $storedRates[$m] ?? '';
                            $carried = $rates[$m];
                        @endphp
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold text-muted d-flex justify-content-between">
                                <span>{{ $monthName }} {{ $year }}</span>
                                @if(isset($storedRates[$m]))
                                    <span class="badge bg-success-subtle text-success small">Explicit</span>
                                @else
                                    <span class="badge bg-light text-muted small" title="Carried forward from previous month">Carried Forward</span>
                                @endif
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-light text-muted small">1 USD =</span>
                                <input type="number" step="0.01" class="form-control" name="rates[{{ $m }}]" value="{{ $val ?: $carried }}" placeholder="{{ number_format($carried, 2) }}">
                                <span class="input-group-text bg-light text-muted small">TZS</span>
                            </div>
                        </div>
                    @endfor
                </div>

                <div class="mt-4 pt-3 border-top d-flex justify-content-between align-items-center">
                    <span class="small text-muted">
                        <i class="bi bi-info-circle me-1"></i> Rates apply to payments and operational expenses recorded in each calendar month.
                    </span>
                    <button type="submit" class="btn btn-uwt-primary px-4">
                        <i class="bi bi-save me-1"></i> Save {{ $year }} Exchange Rates
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
