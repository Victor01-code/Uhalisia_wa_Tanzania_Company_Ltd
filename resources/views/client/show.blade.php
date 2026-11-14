@extends('layouts.public')

@section('title', 'Your Tanzania Safari Itinerary - ' . $booking->reference)

@section('public_content')
<div class="container py-4">

    <!-- Hero Card -->
    <div class="card mb-4 border-0 shadow overflow-hidden">
        <div class="p-4 p-md-5 text-white" style="background: linear-gradient(135deg, #2B1B12 0%, #C9642C 100%);">
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-2">
                <span class="badge bg-warning text-dark text-uppercase fw-bold">Live Safari Itinerary</span>
                <span class="text-white-50 small">Reference: <strong>{{ $booking->reference }}</strong></span>
            </div>
            <h1 class="display-6 fw-bold mb-2 text-white brand-font">{{ $booking->title }}</h1>
            <p class="lead mb-0 text-white-50" style="font-size: 1.05rem;">
                Prepared exclusively for <strong>{{ $booking->client->full_name }}</strong> &bull;
                {{ $booking->durationDays() }} Days ({{ $booking->start_date ? $booking->start_date->format('d M Y') : 'Dates TBD' }} - {{ $booking->end_date ? $booking->end_date->format('d M Y') : 'TBD' }})
            </p>
        </div>
    </div>

    <!-- Status & Payment Overview Row -->
    <div class="row g-3 mb-4">
        <div class="col-sm-6 col-lg-3">
            <div class="card border-0 shadow-sm p-3 bg-white">
                <span class="text-muted small text-uppercase fw-semibold">Booking Status</span>
                <div class="mt-1">
                    <span class="badge-status status-{{ $booking->status }}">
                        {{ ucfirst(str_replace('_', ' ', $booking->status)) }}
                    </span>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-lg-3">
            <div class="card border-0 shadow-sm p-3 bg-white">
                <span class="text-muted small text-uppercase fw-semibold">Total Safari Price</span>
                <h4 class="fw-bold mb-0 text-uwt-dark">${{ number_format($booking->netPriceUsd(), 2) }} USD</h4>
            </div>
        </div>

        <div class="col-sm-6 col-lg-3">
            <div class="card border-0 shadow-sm p-3 bg-white">
                <span class="text-muted small text-uppercase fw-semibold">Total Amount Paid</span>
                <h4 class="fw-bold mb-0 text-success">${{ number_format($booking->paidUsd(), 2) }} USD</h4>
            </div>
        </div>

        <div class="col-sm-6 col-lg-3">
            <div class="card border-0 shadow-sm p-3 bg-white">
                <span class="text-muted small text-uppercase fw-semibold">Remaining Balance</span>
                @if($booking->balanceUsd() <= 0.01)
                    <h4 class="fw-bold mb-0 text-success">$0.00 USD</h4>
                    <span class="badge bg-success-subtle text-success fw-bold">Settled in Full</span>
                @else
                    <h4 class="fw-bold mb-0 text-danger">${{ number_format($booking->balanceUsd(), 2) }} USD</h4>
                    <small class="text-danger fw-semibold">Due 30 days before departure</small>
                @endif
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="row g-4">
        <!-- Itinerary Days -->
        <div class="col-lg-8">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="h6 mb-0 fw-bold brand-font"><i class="bi bi-map text-uwt-primary me-2"></i> Your Day-by-Day Journey</h5>
                    <span class="badge bg-cream text-uwt-dark">{{ $booking->bookingDays->count() }} Days</span>
                </div>
                <div class="card-body p-4">
                    @forelse($booking->bookingDays as $day)
                        <div class="border rounded p-3 mb-3 bg-white shadow-sm">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <div class="d-flex align-items-center gap-2">
                                    <span class="badge bg-uwt-primary text-white fw-bold">Day {{ $day->day_number }}</span>
                                    <h6 class="fw-bold mb-0 text-uwt-dark">{{ $day->title }}</h6>
                                </div>
                                @if($day->date)
                                    <span class="small text-muted fw-semibold">
                                        {{ $day->date->format('D, d M Y') }}
                                    </span>
                                @endif
                            </div>

                            <div class="row g-2 small text-muted mb-2">
                                @if($day->destination)
                                    <div class="col-md-6">
                                        <i class="bi bi-geo-alt text-danger me-1"></i> <strong>Location:</strong> {{ $day->destination }}
                                    </div>
                                @endif
                                @if($day->accommodation)
                                    <div class="col-md-6">
                                        <i class="bi bi-house text-uwt-gold me-1"></i> <strong>Lodge/Camp:</strong> {{ $day->accommodation }}
                                    </div>
                                @endif
                                @if($day->meals)
                                    <div class="col-12">
                                        <i class="bi bi-cup-hot text-uwt-olive me-1"></i> <strong>Included Meals:</strong> {{ $day->meals }}
                                    </div>
                                @endif
                            </div>

                            @if($day->activities)
                                <div class="small text-muted mb-2">
                                    <i class="bi bi-binoculars text-uwt-primary me-1"></i> <strong>Activities:</strong> {{ $day->activities }}
                                </div>
                            @endif

                            @if($day->description)
                                <p class="small text-dark mb-0 mt-2">{{ $day->description }}</p>
                            @endif
                        </div>
                    @empty
                        <div class="text-center py-4 text-muted">Detailed itinerary is being prepared by your tour agent.</div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Sidebar: Contact, Operations, Documents -->
        <div class="col-lg-4">

            <!-- Support & Assistance -->
            <div class="card shadow-sm border-0 mb-4 bg-cream-light">
                <div class="card-body p-4 text-center">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo" style="height: 48px;" class="mb-2">
                    <h6 class="fw-bold brand-font text-uwt-dark mb-1">Need Assistance?</h6>
                    <p class="small text-muted mb-3">Our tour coordinators in Arusha are ready to help with flight changes, dietary updates, or extra requests.</p>

                    <div class="d-grid gap-2">
                        <a href="https://wa.me/255784590722" target="_blank" class="btn btn-success btn-sm">
                            <i class="bi bi-whatsapp me-1"></i> WhatsApp: +255 784 590 722
                        </a>
                        <a href="mailto:info@uhalisiawatanzania.com?subject=Inquiry%20regarding%20{{ $booking->reference }}" class="btn btn-outline-dark btn-sm">
                            <i class="bi bi-envelope me-1"></i> Email Our Office
                        </a>
                    </div>
                </div>
            </div>

            <!-- PDF Downloads Card -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white">
                    <h6 class="mb-0 fw-bold"><i class="bi bi-file-earmark-pdf text-danger me-2"></i> Official Travel Documents</h6>
                </div>
                <div class="card-body p-3">
                    <div class="d-grid gap-2">
                        <a href="{{ route('client.itinerary_pdf', ['token' => $booking->public_token, 'filename' => 'Safari-Itinerary-' . $booking->reference . '.pdf']) }}" download="Safari-Itinerary-{{ $booking->reference }}.pdf" class="btn btn-uwt-olive text-white btn-sm text-start p-2">
                            <i class="bi bi-journal-bookmark me-2 fs-6"></i>
                            <strong>Download Safari Voucher PDF</strong>
                            <div class="small text-white-50 ms-4">Complete day-by-day & packing guide</div>
                        </a>

                        <a href="{{ route('client.quote_pdf', ['token' => $booking->public_token, 'filename' => 'Safari-Quote-' . $booking->reference . '.pdf']) }}" download="Safari-Quote-{{ $booking->reference }}.pdf" class="btn btn-outline-secondary btn-sm text-start p-2">
                            <i class="bi bi-file-earmark-pdf me-2 fs-6 text-danger"></i>
                            <strong>Download Official Quotation PDF</strong>
                            <div class="small text-muted ms-4">Commercial terms & payment invoice</div>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Assigned Safari Crew & Vehicle -->
            @if($booking->assignments->isNotEmpty())
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-white">
                        <h6 class="mb-0 fw-bold"><i class="bi bi-people text-uwt-primary me-2"></i> Your Safari Crew & Vehicle</h6>
                    </div>
                    <div class="card-body p-3">
                        @foreach($booking->assignments as $a)
                            @php $res = $a->resource(); @endphp
                            @if($res)
                                <div class="d-flex align-items-center gap-3 mb-2 p-2 rounded bg-light">
                                    <div class="fs-4 text-uwt-primary">
                                        @if($a->resource_type === 'vehicle')
                                            <i class="bi bi-truck-front-fill"></i>
                                        @elseif($a->resource_type === 'driver')
                                            <i class="bi bi-person-badge-fill"></i>
                                        @else
                                            <i class="bi bi-binoculars-fill"></i>
                                        @endif
                                    </div>
                                    <div class="flex-grow-1">
                                        <div class="fw-bold small">{{ $res->name }}</div>
                                        <div class="small text-muted">
                                            @if($a->resource_type === 'vehicle')
                                                <span>{{ $res->type }} &bull; <span class="font-monospace">{{ $res->plate_number }}</span></span>
                                            @elseif($a->resource_type === 'driver')
                                                <span>Driver-Guide &bull; <a href="tel:{{ $res->phone }}" class="text-decoration-none text-dark">{{ $res->phone }}</a></span>
                                            @else
                                                <span>Naturalist Guide &bull; <a href="tel:{{ $res->phone }}" class="text-decoration-none text-dark">{{ $res->phone }}</a></span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Client Documents -->
            @if($booking->documents->isNotEmpty())
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-white">
                        <h6 class="mb-0 fw-bold"><i class="bi bi-folder2-open text-primary me-2"></i> Additional Trip Files</h6>
                    </div>
                    <div class="list-group list-group-flush">
                        @foreach($booking->documents as $doc)
                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <div class="fw-semibold small">{{ $doc->title }}</div>
                                    <small class="text-muted">{{ strtoupper(pathinfo($doc->file_path, PATHINFO_EXTENSION)) }} Document</small>
                                </div>
                                <a href="{{ route('client.documents.download', ['token' => $booking->public_token, 'document' => $doc->id]) }}" class="btn btn-outline-primary btn-sm">
                                    <i class="bi bi-download me-1"></i> Download
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

        </div>
    </div>

</div>
@endsection
