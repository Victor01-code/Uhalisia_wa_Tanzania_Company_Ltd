@extends('layouts.admin')

@section('page_title', 'Tour Templates')

@section('admin_content')
<div class="container-fluid p-0">

    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-2">
        <div>
            <h2 class="h4 brand-font text-uwt-dark mb-1">Tour & Safari Templates</h2>
            <p class="text-muted small mb-0">Master itinerary packages copied into client bookings and pre-selected from website buttons.</p>
        </div>
        <div>
            <a href="{{ route('admin.tours.create') }}" class="btn btn-uwt-primary">
                <i class="bi bi-plus-circle me-1"></i> New Tour Package
            </a>
        </div>
    </div>

    <!-- Tours Grid -->
    <div class="row g-4 mb-4">
        @foreach($tours as $t)
            <div class="col-md-6 col-xl-4">
                <div class="card shadow-sm h-100 border-0">
                    <div class="card-body p-4 d-flex flex-column">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <span class="badge bg-cream text-uwt-dark border text-uppercase" style="font-size: 0.7rem;">
                                {{ ucfirst($t->type) }}
                            </span>
                            @if($t->active)
                                <span class="badge bg-success-subtle text-success small">Active</span>
                            @else
                                <span class="badge bg-secondary text-white small">Inactive</span>
                            @endif
                        </div>

                        <h5 class="fw-bold text-uwt-dark mb-2 brand-font" style="font-size: 1.15rem;">
                            {{ $t->name }}
                        </h5>

                        <div class="small text-muted mb-2 font-monospace">
                            slug: <span class="text-uwt-primary">{{ $t->slug }}</span>
                        </div>

                        <p class="small text-muted flex-grow-1 mb-3">
                            {{ Str::limit($t->summary, 120) }}
                        </p>

                        <div class="d-flex justify-content-between align-items-center pt-3 border-top mt-auto">
                            <div>
                                <span class="h5 fw-bold text-success mb-0">${{ number_format($t->base_price_usd, 0) }}</span>
                                <small class="text-muted">/ person</small>
                                <div class="small text-muted">{{ $t->days }} Days &bull; {{ $t->tour_days_count }} Day Templates</div>
                            </div>

                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('admin.tours.show', $t->id) }}" class="btn btn-outline-secondary" title="View Days & Details">
                                    <i class="bi bi-eye"></i> View Days
                                </a>
                                <a href="{{ route('admin.tours.edit', $t->id) }}" class="btn btn-outline-secondary" title="Edit Tour">
                                    <i class="bi bi-pencil"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

</div>
@endsection
