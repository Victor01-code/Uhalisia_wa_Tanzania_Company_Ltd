@extends('layouts.admin')

@section('page_title', 'Booking ' . $booking->reference)

@section('admin_content')
<div class="container-fluid p-0">

    <!-- Header & Action Bar -->
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-2">
        <div class="d-flex align-items-center gap-3">
            <a href="{{ route('admin.bookings.index') }}" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-arrow-left"></i> Bookings
            </a>
            <div>
                <div class="d-flex align-items-center gap-2">
                    <h2 class="h4 brand-font text-uwt-dark mb-0">{{ $booking->reference }}</h2>
                    <span class="badge-status status-{{ $booking->status }}">
                        {{ ucfirst(str_replace('_', ' ', $booking->status)) }}
                    </span>
                    @if($booking->payment_mode === 'client_direct')
                        <span class="badge bg-secondary text-white small">Client Direct</span>
                    @endif
                </div>
                <div class="text-muted small">{{ $booking->title }}</div>
            </div>
        </div>

        <div class="d-flex flex-wrap gap-2">
            <!-- PDF Quote Dropdown -->
            <div class="dropdown">
                <button class="btn btn-outline-secondary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="bi bi-file-earmark-pdf me-1 text-danger"></i> Quote PDF
                </button>
                <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                    <li>
                        <a class="dropdown-item" href="{{ route('admin.bookings.quote_pdf', ['booking' => $booking->id, 'filename' => 'UWT-Quote-' . $booking->reference . '.pdf']) }}" target="_blank">
                            <i class="bi bi-eye me-2 text-primary"></i> Preview in Browser
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="{{ route('admin.bookings.quote_pdf', ['booking' => $booking->id, 'filename' => 'UWT-Quote-' . $booking->reference . '.pdf', 'download' => 1]) }}" download="UWT-Quote-{{ $booking->reference }}.pdf">
                            <i class="bi bi-download me-2 text-success"></i> Download PDF
                        </a>
                    </li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <button class="dropdown-item" type="button" data-bs-toggle="modal" data-bs-target="#emailQuoteModal">
                            <i class="bi bi-envelope-paper me-2 text-uwt-primary"></i> Email Quote to Client
                        </button>
                    </li>
                </ul>
            </div>

            <!-- PDF Voucher Dropdown -->
            <div class="dropdown">
                <button class="btn btn-outline-secondary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="bi bi-journal-bookmark me-1 text-uwt-olive"></i> Itinerary Voucher PDF
                </button>
                <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                    <li>
                        <a class="dropdown-item" href="{{ route('admin.bookings.itinerary_pdf', ['booking' => $booking->id, 'filename' => 'UWT-Itinerary-' . $booking->reference . '.pdf']) }}" target="_blank">
                            <i class="bi bi-eye me-2 text-primary"></i> Preview in Browser
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="{{ route('admin.bookings.itinerary_pdf', ['booking' => $booking->id, 'filename' => 'UWT-Itinerary-' . $booking->reference . '.pdf', 'download' => 1]) }}" download="UWT-Itinerary-{{ $booking->reference }}.pdf">
                            <i class="bi bi-download me-2 text-success"></i> Download PDF
                        </a>
                    </li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <button class="dropdown-item" type="button" data-bs-toggle="modal" data-bs-target="#emailItineraryModal">
                            <i class="bi bi-send me-2 text-uwt-olive"></i> Email Voucher to Client
                        </button>
                    </li>
                </ul>
            </div>

            <a href="{{ route('admin.bookings.edit', $booking->id) }}" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-pencil me-1"></i> Edit Details
            </a>

            @if(Auth::user()->hasRole(['admin', 'accountant']))
                <a href="{{ route('admin.payments.create', ['booking_id' => $booking->id]) }}" class="btn btn-uwt-olive btn-sm">
                    <i class="bi bi-currency-dollar me-1"></i> Record Payment
                </a>
            @endif

            <a href="{{ url('/client/b/' . $booking->public_token) }}" target="_blank" class="btn btn-outline-primary btn-sm" title="Preview Client View">
                <i class="bi bi-box-arrow-up-right me-1"></i> Client Portal
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show small mb-4" role="alert">
            <i class="bi bi-check-circle-fill me-1"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show small mb-4" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-1"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show small mb-4" role="alert">
            <ul class="mb-0 ps-3">
                @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('status_suggestion'))
        @php $sugg = session('status_suggestion'); @endphp
        <div class="alert alert-info border-info d-flex flex-wrap justify-content-between align-items-center mb-4 shadow-sm gap-2" role="alert">
            <div class="d-flex align-items-center gap-2">
                <i class="bi bi-stars text-primary fs-4"></i>
                <div>
                    <strong>Status Transition Suggestion:</strong> {{ $sugg['message'] }}
                </div>
            </div>
            <form action="{{ route('admin.bookings.update_status', $sugg['booking_id']) }}" method="POST" class="d-inline mb-0">
                @csrf
                @method('PATCH')
                <input type="hidden" name="status" value="{{ $sugg['status'] }}">
                <input type="hidden" name="note" value="Staff confirmed automated status transition to {{ $sugg['label'] }} after payment.">
                <button type="submit" class="btn btn-sm btn-primary fw-bold">
                    <i class="bi bi-check2-circle me-1"></i> Update Status to {{ $sugg['label'] }}
                </button>
            </form>
        </div>
    @endif

    <!-- Quick Financial & Travel Metrics Row -->
    <div class="row g-3 mb-4">
        <div class="col-sm-6 col-lg-3">
            <div class="card border-0 shadow-sm p-3 bg-white">
                <span class="text-muted small text-uppercase fw-semibold">Net Trip Price</span>
                <h4 class="fw-bold mb-0 text-uwt-dark">${{ number_format($booking->netPriceUsd(), 2) }} USD</h4>
                @if($booking->discount_usd > 0)
                    <small class="text-muted">Includes ${{ number_format($booking->discount_usd, 0) }} discount</small>
                @else
                    <small class="text-muted">Total Quoted Price</small>
                @endif
            </div>
        </div>

        <div class="col-sm-6 col-lg-3">
            <div class="card border-0 shadow-sm p-3 bg-white">
                <span class="text-muted small text-uppercase fw-semibold">Total Paid</span>
                <h4 class="fw-bold mb-0 text-success">${{ number_format($booking->paidUsd(), 2) }} USD</h4>
                <small class="text-muted">{{ $booking->payments->count() }} payments recorded</small>
            </div>
        </div>

        <div class="col-sm-6 col-lg-3">
            <div class="card border-0 shadow-sm p-3 bg-white">
                <span class="text-muted small text-uppercase fw-semibold">Outstanding Balance</span>
                @if($booking->balanceUsd() <= 0.01)
                    <h4 class="fw-bold mb-0 text-success">$0.00 USD</h4>
                    <small class="badge bg-success-subtle text-success fw-bold">Fully Settled</small>
                @else
                    <h4 class="fw-bold mb-0 text-danger">${{ number_format($booking->balanceUsd(), 2) }} USD</h4>
                    <small class="text-danger fw-semibold">Payment Due</small>
                @endif
            </div>
        </div>

        <div class="col-sm-6 col-lg-3">
            <div class="card border-0 shadow-sm p-3 bg-white">
                <span class="text-muted small text-uppercase fw-semibold">Travel Dates</span>
                <h4 class="fw-bold mb-0 text-uwt-gold" style="font-size: 1.15rem;">
                    {{ $booking->start_date ? $booking->start_date->format('d M') : 'TBD' }} - {{ $booking->end_date ? $booking->end_date->format('d M Y') : 'TBD' }}
                </h4>
                <small class="text-muted">{{ $booking->durationDays() }} Days &bull; {{ $booking->adults }} Adults, {{ $booking->children }} Children</small>
            </div>
        </div>
    </div>

    <!-- Main Content Layout -->
    <div class="row g-4">
        <!-- Left: Itinerary, Travelers, Payments -->
        <div class="col-lg-8">

            <!-- Client & Trip Summary Card -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="h6 mb-0 fw-bold"><i class="bi bi-person text-uwt-primary me-2"></i> Client & Booking Overview</h5>
                    <a href="{{ route('admin.clients.show', $booking->client_id) }}" class="btn btn-sm btn-link text-uwt-primary text-decoration-none p-0">
                        View Client Dossier <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
                <div class="card-body p-4">
                    <div class="row g-3">
                        <div class="col-sm-6">
                            <label class="small text-muted d-block">Lead Client</label>
                            <span class="fw-bold fs-6">{{ $booking->client->full_name }}</span>
                            <div class="small text-muted">{{ $booking->client->country ?? 'Country not set' }}</div>
                        </div>

                        <div class="col-sm-6">
                            <label class="small text-muted d-block">Client Contact</label>
                            <div><a href="mailto:{{ $booking->client->email }}" class="text-uwt-primary small text-decoration-none">{{ $booking->client->email }}</a></div>
                            @if($booking->client->phone)
                                <div class="small text-muted"><i class="bi bi-telephone me-1"></i> {{ $booking->client->phone }}</div>
                            @endif
                        </div>

                        <div class="col-sm-6">
                            <label class="small text-muted d-block">Tour Template</label>
                            @if($booking->tour)
                                <span class="badge bg-cream text-uwt-dark border">{{ $booking->tour->name }}</span>
                            @else
                                <span class="text-muted small">Custom Tailor-made Expedition</span>
                            @endif
                        </div>

                        <div class="col-sm-6">
                            <label class="small text-muted d-block">Created By Staff</label>
                            <span class="small">{{ $booking->creator->name ?? 'System' }} ({{ $booking->created_at->format('d M Y') }})</span>
                        </div>

                        <div class="col-12">
                            <label class="small text-muted d-block">Private Client Portal Link</label>
                            <div class="input-group input-group-sm">
                                <input type="text" readonly class="form-control bg-light" value="{{ url('/client/b/' . $booking->public_token) }}" id="clientPortalUrl">
                                <button class="btn btn-outline-secondary" type="button" onclick="navigator.clipboard.writeText(document.getElementById('clientPortalUrl').value); alert('Client link copied to clipboard!');">
                                    <i class="bi bi-clipboard"></i> Copy Link
                                </button>
                                <a href="{{ url('/client/b/' . $booking->public_token) }}" target="_blank" class="btn btn-outline-primary">
                                    <i class="bi bi-box-arrow-up-right"></i> Open
                                </a>
                            </div>
                            <div class="form-text" style="font-size: 0.75rem;">Clients use this private link to view their itinerary, driver contacts, and invoices without needing a password.</div>
                        </div>

                        @if($booking->notes)
                            <div class="col-12 border-top pt-3">
                                <label class="small text-muted d-block fw-bold">Internal Notes:</label>
                                <div class="p-2 bg-light rounded small">{{ $booking->notes }}</div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Tabbed Operational Sections -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white p-0 border-bottom">
                    <ul class="nav nav-tabs card-header-tabs m-0 px-3 pt-2" id="bookingTab" role="tablist">
                        <li class="nav-item">
                            <button class="nav-link active fw-semibold" id="itinerary-tab" data-bs-toggle="tab" data-bs-target="#itinerary" type="button" role="tab">
                                <i class="bi bi-map me-1"></i> Itinerary Days ({{ $booking->bookingDays->count() }})
                            </button>
                        </li>
                        <li class="nav-item">
                            <button class="nav-link fw-semibold" id="travelers-tab" data-bs-toggle="tab" data-bs-target="#travelers" type="button" role="tab">
                                <i class="bi bi-people me-1"></i> Travelers ({{ $booking->travelers->count() }})
                            </button>
                        </li>
                        <li class="nav-item">
                            <button class="nav-link fw-semibold" id="payments-tab" data-bs-toggle="tab" data-bs-target="#payments" type="button" role="tab">
                                <i class="bi bi-cash-stack me-1"></i> Payments ({{ $booking->payments->count() }})
                            </button>
                        </li>
                        <li class="nav-item">
                            <button class="nav-link fw-semibold" id="expenses-tab" data-bs-toggle="tab" data-bs-target="#expenses" type="button" role="tab">
                                <i class="bi bi-receipt me-1"></i> Expenses ({{ $booking->expenses->count() }})
                            </button>
                        </li>
                        <li class="nav-item">
                            <button class="nav-link fw-semibold" id="assignments-tab" data-bs-toggle="tab" data-bs-target="#assignments" type="button" role="tab">
                                <i class="bi bi-truck-front me-1"></i> Fleet & Crew ({{ $booking->assignments->count() }})
                            </button>
                        </li>
                        <li class="nav-item">
                            <button class="nav-link fw-semibold" id="documents-tab" data-bs-toggle="tab" data-bs-target="#documents" type="button" role="tab">
                                <i class="bi bi-folder2-open me-1"></i> Documents ({{ $booking->documents->count() }})
                            </button>
                        </li>
                    </ul>
                </div>

                <div class="card-body p-4">
                    <div class="tab-content" id="bookingTabContent">

                        <!-- Tab 1: Live Itinerary Days Editor -->
                        <div class="tab-pane fade show active" id="itinerary" role="tabpanel">
                            <div class="d-flex flex-wrap justify-content-between align-items-center mb-3 gap-2">
                                <div>
                                    <h6 class="fw-bold mb-0">Day-by-Day Schedule</h6>
                                    <span class="small text-muted">{{ $booking->bookingDays->count() }} itinerary days configured</span>
                                </div>
                                <div class="d-flex gap-2">
                                    <form action="{{ route('admin.bookings.days.redate', $booking->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-outline-secondary btn-sm" title="Synchronize calendar dates with booking start date">
                                            <i class="bi bi-calendar-sync me-1"></i> Sync Dates
                                        </button>
                                    </form>
                                    <button type="button" class="btn btn-uwt-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addDayModal">
                                        <i class="bi bi-plus-circle me-1"></i> Add Day
                                    </button>
                                </div>
                            </div>

                            @forelse($booking->bookingDays as $day)
                                <div class="border rounded p-3 mb-3 bg-white shadow-sm">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <div class="d-flex align-items-center gap-2">
                                            <span class="badge bg-uwt-primary text-white fw-bold">Day {{ $day->day_number }}</span>
                                            <h6 class="fw-bold mb-0 text-uwt-dark">{{ $day->title }}</h6>
                                        </div>
                                        <div class="d-flex align-items-center gap-1">
                                            @if($day->date)
                                                <span class="small text-muted fw-semibold me-2">
                                                    <i class="bi bi-calendar me-1"></i> {{ $day->date->format('D, d M Y') }}
                                                </span>
                                            @endif

                                            <!-- Reorder up / down -->
                                            <form action="{{ route('admin.bookings.days.reorder', $booking->id) }}" method="POST" class="d-inline m-0">
                                                @csrf
                                                <input type="hidden" name="day_id" value="{{ $day->id }}">
                                                <input type="hidden" name="direction" value="up">
                                                <button type="submit" class="btn btn-sm btn-outline-secondary p-1" title="Move Up" {{ $loop->first ? 'disabled' : '' }}>
                                                    <i class="bi bi-arrow-up"></i>
                                                </button>
                                            </form>
                                            <form action="{{ route('admin.bookings.days.reorder', $booking->id) }}" method="POST" class="d-inline m-0">
                                                @csrf
                                                <input type="hidden" name="day_id" value="{{ $day->id }}">
                                                <input type="hidden" name="direction" value="down">
                                                <button type="submit" class="btn btn-sm btn-outline-secondary p-1" title="Move Down" {{ $loop->last ? 'disabled' : '' }}>
                                                    <i class="bi bi-arrow-down"></i>
                                                </button>
                                            </form>

                                            <!-- Edit Day Button -->
                                            <button type="button" class="btn btn-sm btn-outline-primary p-1" data-bs-toggle="modal" data-bs-target="#editDayModal{{ $day->id }}" title="Edit Day">
                                                <i class="bi bi-pencil"></i>
                                            </button>

                                            <!-- Delete Day Button -->
                                            <form action="{{ route('admin.bookings.days.destroy', ['booking' => $booking->id, 'day' => $day->id]) }}" method="POST" class="d-inline m-0" onsubmit="return confirm('Delete Day {{ $day->day_number }} from itinerary?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger p-1" title="Delete Day">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </div>

                                    <div class="row g-2 small text-muted mb-2">
                                        @if($day->destination)
                                            <div class="col-md-4">
                                                <i class="bi bi-geo-alt text-danger me-1"></i> <strong>Destination:</strong> {{ $day->destination }}
                                            </div>
                                        @endif
                                        @if($day->accommodation)
                                            <div class="col-md-4">
                                                <i class="bi bi-house text-uwt-gold me-1"></i> <strong>Lodge/Camp:</strong> {{ $day->accommodation }}
                                            </div>
                                        @endif
                                        @if($day->meals)
                                            <div class="col-md-4">
                                                <i class="bi bi-cup-hot text-uwt-olive me-1"></i> <strong>Meals:</strong> {{ $day->meals }}
                                            </div>
                                        @endif
                                    </div>

                                    @if($day->activities)
                                        <div class="small text-muted mb-1">
                                            <strong>Activities:</strong> {{ $day->activities }}
                                        </div>
                                    @endif

                                    @if($day->description)
                                        <p class="small text-dark mb-0 mt-2">{{ $day->description }}</p>
                                    @endif
                                </div>

                                <!-- Edit Day Modal -->
                                <div class="modal fade" id="editDayModal{{ $day->id }}" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                            <form action="{{ route('admin.bookings.days.update', ['booking' => $booking->id, 'day' => $day->id]) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <div class="modal-header">
                                                    <h5 class="modal-title h6 fw-bold">Edit Day {{ $day->day_number }} - {{ $booking->reference }}</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="row g-3">
                                                        <div class="col-md-3">
                                                            <label class="form-label small fw-semibold">Day #</label>
                                                            <input type="number" name="day_number" class="form-control" value="{{ $day->day_number }}" required min="1">
                                                        </div>
                                                        <div class="col-md-4">
                                                            <label class="form-label small fw-semibold">Date</label>
                                                            <input type="date" name="date" class="form-control" value="{{ $day->date ? $day->date->format('Y-m-d') : '' }}">
                                                        </div>
                                                        <div class="col-md-5">
                                                            <label class="form-label small fw-semibold">Destination</label>
                                                            <input type="text" name="destination" class="form-control" value="{{ $day->destination }}" placeholder="e.g. Serengeti National Park">
                                                        </div>
                                                        <div class="col-12">
                                                            <label class="form-label small fw-semibold">Day Title *</label>
                                                            <input type="text" name="title" class="form-control" value="{{ $day->title }}" required>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label class="form-label small fw-semibold">Lodge / Camp</label>
                                                            <input type="text" name="accommodation" class="form-control" value="{{ $day->accommodation }}" placeholder="e.g. Serengeti Serena Safari Lodge">
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label class="form-label small fw-semibold">Meal Plan</label>
                                                            <input type="text" name="meals" class="form-control" value="{{ $day->meals }}" placeholder="e.g. Breakfast, Lunch, Dinner">
                                                        </div>
                                                        <div class="col-12">
                                                            <label class="form-label small fw-semibold">Activities & Highlights</label>
                                                            <input type="text" name="activities" class="form-control" value="{{ $day->activities }}" placeholder="e.g. Full day game drive tracking big cats">
                                                        </div>
                                                        <div class="col-12">
                                                            <label class="form-label small fw-semibold">Narrative Description</label>
                                                            <textarea name="description" rows="4" class="form-control" placeholder="Detailed daily narrative for client vouchers...">{{ $day->description }}</textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                                                    <button type="submit" class="btn btn-uwt-primary btn-sm">Save Day Changes</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-5 text-muted border rounded bg-white">
                                    <i class="bi bi-map fs-2 d-block mb-2 text-uwt-gold"></i>
                                    <h6 class="fw-bold">No itinerary days added yet</h6>
                                    <p class="small text-muted mb-3">Build a tailored day-by-day expedition schedule for this guest.</p>
                                    <button type="button" class="btn btn-uwt-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addDayModal">
                                        <i class="bi bi-plus-circle me-1"></i> Add First Day
                                    </button>
                                </div>
                            @endforelse
                        </div>

                        <!-- Tab 2: Travelers -->
                        <div class="tab-pane fade" id="travelers" role="tabpanel">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div>
                                    <h6 class="fw-bold mb-0">Registered Travelers</h6>
                                    <span class="small text-muted">{{ $booking->travelers->count() }} passenger(s) on manifest</span>
                                </div>
                                <button type="button" class="btn btn-uwt-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addTravelerModal">
                                    <i class="bi bi-person-plus me-1"></i> Add Traveler
                                </button>
                            </div>

                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead>
                                        <tr>
                                            <th>Full Name</th>
                                            <th>Nationality</th>
                                            <th>DOB</th>
                                            <th>Passport No.</th>
                                            <th>Dietary Notes</th>
                                            <th>Emergency Contact</th>
                                            <th class="text-end">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($booking->travelers as $t)
                                            <tr>
                                                <td class="fw-bold">{{ $t->full_name }}</td>
                                                <td>{{ $t->nationality ?? '-' }}</td>
                                                <td>{{ $t->date_of_birth ? $t->date_of_birth->format('d M Y') : '-' }}</td>
                                                <td>
                                                    <span class="badge bg-light text-dark border font-monospace">
                                                        {{ $t->passport_number ? substr($t->passport_number, 0, 3) . '•••••' : '-' }}
                                                    </span>
                                                </td>
                                                <td><small>{{ $t->dietary_notes ?? 'None' }}</small></td>
                                                <td><small>{{ $t->emergency_contact ?? '-' }}</small></td>
                                                <td class="text-end">
                                                    <button type="button" class="btn btn-sm btn-outline-secondary p-1" data-bs-toggle="modal" data-bs-target="#editTravelerModal{{ $t->id }}" title="Edit">
                                                        <i class="bi bi-pencil"></i>
                                                    </button>
                                                    <form action="{{ route('admin.bookings.travelers.destroy', ['booking' => $booking->id, 'traveler' => $t->id]) }}" method="POST" class="d-inline m-0" onsubmit="return confirm('Remove traveler {{ $t->full_name }}?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-outline-danger p-1" title="Delete">
                                                            <i class="bi bi-trash"></i>
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>

                                            <!-- Edit Traveler Modal -->
                                            <div class="modal fade" id="editTravelerModal{{ $t->id }}" tabindex="-1" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <form action="{{ route('admin.bookings.travelers.update', ['booking' => $booking->id, 'traveler' => $t->id]) }}" method="POST">
                                                            @csrf
                                                            @method('PUT')
                                                            <div class="modal-header">
                                                                <h5 class="modal-title h6 fw-bold">Edit Traveler - {{ $t->full_name }}</h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <div class="row g-3">
                                                                    <div class="col-12">
                                                                        <label class="form-label small fw-semibold">Full Legal Name *</label>
                                                                        <input type="text" name="full_name" class="form-control" value="{{ $t->full_name }}" required>
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <label class="form-label small fw-semibold">Nationality</label>
                                                                        <input type="text" name="nationality" class="form-control" value="{{ $t->nationality }}">
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <label class="form-label small fw-semibold">Date of Birth</label>
                                                                        <input type="date" name="date_of_birth" class="form-control" value="{{ $t->date_of_birth ? $t->date_of_birth->format('Y-m-d') : '' }}">
                                                                    </div>
                                                                    <div class="col-12">
                                                                        <label class="form-label small fw-semibold">Passport Number (Encrypted at rest)</label>
                                                                        <input type="text" name="passport_number" class="form-control" value="{{ $t->passport_number }}">
                                                                    </div>
                                                                    <div class="col-12">
                                                                        <label class="form-label small fw-semibold">Dietary & Medical Requirements</label>
                                                                        <textarea name="dietary_notes" rows="2" class="form-control" placeholder="e.g. Vegetarian, Gluten-free, Peanut allergy...">{{ $t->dietary_notes }}</textarea>
                                                                    </div>
                                                                    <div class="col-12">
                                                                        <label class="form-label small fw-semibold">Emergency Contact</label>
                                                                        <input type="text" name="emergency_contact" class="form-control" value="{{ $t->emergency_contact }}" placeholder="Name & Phone Number">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                                                                <button type="submit" class="btn btn-uwt-primary btn-sm">Save Changes</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        @empty
                                            <tr>
                                                <td colspan="7" class="text-center py-4 text-muted">
                                                    No travelers registered to this booking yet.
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Tab 3: Payments -->
                        <div class="tab-pane fade" id="payments" role="tabpanel">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div>
                                    <h6 class="fw-bold mb-0">Payment Transactions</h6>
                                    <span class="small text-muted">All client receipts, deposits, and settlements for this expedition</span>
                                </div>
                                @if(Auth::user()->hasRole(['admin', 'accountant']))
                                    <button type="button" class="btn btn-sm btn-uwt-olive" data-bs-toggle="modal" data-bs-target="#recordPaymentModal">
                                        <i class="bi bi-plus-circle me-1"></i> Record Payment
                                    </button>
                                @endif
                            </div>

                            <div class="table-responsive">
                                <table class="table table-sm table-hover align-middle mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Date</th>
                                            <th>Amount (USD)</th>
                                            <th>Recorded As</th>
                                            <th>Exchange Rate</th>
                                            <th>TZS Equivalent</th>
                                            <th>Method</th>
                                            <th>Type</th>
                                            <th>Reference</th>
                                            <th class="text-end">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($booking->payments as $p)
                                            <tr>
                                                <td>{{ $p->paid_on ? $p->paid_on->format('d M Y') : 'N/A' }}</td>
                                                <td class="fw-bold text-success">${{ number_format($p->amount_usd, 2) }}</td>
                                                <td>
                                                    @if($p->currency === 'TZS')
                                                        {{ number_format($p->amount, 0) }} TZS
                                                    @else
                                                        ${{ number_format($p->amount, 2) }} USD
                                                    @endif
                                                </td>
                                                <td class="small">{{ number_format($p->exchange_rate_used, 0) }} TZS/USD</td>
                                                <td class="small text-muted">{{ number_format($p->amount_tzs, 0) }} TZS</td>
                                                <td><span class="badge bg-light text-dark border">{{ ucfirst($p->method) }}</span></td>
                                                <td><span class="badge bg-cream text-uwt-dark">{{ ucfirst($p->type) }}</span></td>
                                                <td><small class="text-muted font-monospace">{{ $p->reference ?: '-' }}</small></td>
                                                <td class="text-end">
                                                    @if(Auth::user()->hasRole(['admin', 'accountant']))
                                                        <form action="{{ route('admin.payments.destroy', $p->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Permanently remove this payment record?');">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-outline-danger btn-sm p-1" title="Delete Payment Record">
                                                                <i class="bi bi-trash"></i>
                                                            </button>
                                                        </form>
                                                    @endif
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="9" class="text-center py-4 text-muted">
                                                    <i class="bi bi-cash-stack fs-4 text-muted d-block mb-1"></i>
                                                    No payments recorded for this trip yet.<br>
                                                    <small>Click "Record Payment" above to log a deposit or final payment.</small>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Tab: Trip Operational Expenses -->
                        <div class="tab-pane fade" id="expenses" role="tabpanel">
                            @php
                                $tripExpensesUsd = $booking->expenses->sum('amount_usd');
                                $tripExpensesTzs = $booking->expenses->sum('amount_tzs');
                                $tripProfitUsd = $booking->paidUsd() - $tripExpensesUsd;
                                $profitMargin = $booking->paidUsd() > 0 ? (($tripProfitUsd / $booking->paidUsd()) * 100) : 0;
                            @endphp

                            <!-- Booking Profitability Card -->
                            <div class="row g-2 mb-3">
                                <div class="col-sm-4">
                                    <div class="border rounded p-2 bg-light">
                                        <span class="text-muted small d-block">Collected Revenue</span>
                                        <strong class="text-success fs-6">${{ number_format($booking->paidUsd(), 2) }} USD</strong>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="border rounded p-2 bg-light">
                                        <span class="text-muted small d-block">Direct Trip Expenses</span>
                                        <strong class="text-danger fs-6">${{ number_format($tripExpensesUsd, 2) }} USD</strong>
                                        <div class="small text-muted" style="font-size: 0.75rem;">{{ number_format($tripExpensesTzs, 0) }} TZS</div>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="border rounded p-2 bg-light">
                                        <span class="text-muted small d-block">Net Trip Profit / Margin</span>
                                        <strong class="{{ $tripProfitUsd >= 0 ? 'text-uwt-primary' : 'text-danger' }} fs-6">
                                            ${{ number_format($tripProfitUsd, 2) }} USD
                                        </strong>
                                        <span class="badge {{ $tripProfitUsd >= 0 ? 'bg-success-subtle text-success' : 'bg-danger-subtle text-danger' }} ms-1" style="font-size: 0.7rem;">
                                            {{ number_format($profitMargin, 1) }}%
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div>
                                    <h6 class="fw-bold mb-0">Direct Trip Operational Expenses</h6>
                                    <span class="small text-muted">Park fees, fuel, driver allowances, and lodge payments assigned to this trip</span>
                                </div>
                                @if(Auth::user()->hasRole(['admin', 'accountant', 'agent']))
                                    <button type="button" class="btn btn-sm btn-uwt-primary" data-bs-toggle="modal" data-bs-target="#recordExpenseModal">
                                        <i class="bi bi-receipt me-1"></i> Log Trip Expense
                                    </button>
                                @endif
                            </div>

                            <div class="table-responsive">
                                <table class="table table-sm table-hover align-middle mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Date</th>
                                            <th>Category</th>
                                            <th>Description</th>
                                            <th>Amount (Entered)</th>
                                            <th>TZS Equivalent</th>
                                            <th>USD Value</th>
                                            <th class="text-end">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($booking->expenses as $exp)
                                            <tr>
                                                <td>{{ $exp->expense_date ? $exp->expense_date->format('d M Y') : 'N/A' }}</td>
                                                <td>
                                                    <span class="badge bg-secondary-subtle text-dark border">
                                                        {{ $exp->categoryLabel() }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <div class="fw-semibold text-uwt-dark">{{ $exp->description }}</div>
                                                    @if($exp->notes)
                                                        <small class="text-muted">{{ Str::limit($exp->notes, 40) }}</small>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($exp->currency === 'USD')
                                                        <span class="fw-bold text-uwt-dark">${{ number_format($exp->amount, 2) }} USD</span>
                                                    @else
                                                        <span class="fw-bold text-uwt-dark">{{ number_format($exp->amount, 0) }} TZS</span>
                                                    @endif
                                                </td>
                                                <td class="small text-muted">{{ number_format($exp->amount_tzs, 0) }} TZS</td>
                                                <td class="fw-bold text-danger">${{ number_format($exp->amount_usd, 2) }}</td>
                                                <td class="text-end">
                                                    @if(Auth::user()->hasRole(['admin', 'accountant', 'agent']))
                                                        <div class="btn-group btn-group-sm">
                                                            <a href="{{ route('admin.expenses.edit', $exp->id) }}" class="btn btn-outline-secondary" title="Edit Expense">
                                                                <i class="bi bi-pencil"></i>
                                                            </a>
                                                            <form action="{{ route('admin.expenses.destroy', $exp->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Permanently remove this expense?');">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="btn btn-outline-danger" title="Delete Expense">
                                                                    <i class="bi bi-trash"></i>
                                                                </button>
                                                            </form>
                                                        </div>
                                                    @endif
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="7" class="text-center py-4 text-muted">
                                                    <i class="bi bi-receipt text-muted fs-4 d-block mb-1"></i>
                                                    No direct operational costs logged for this expedition yet.<br>
                                                    <small>Log park entrance fees, accommodation payments, and fuel to track trip profit margin.</small>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Tab 4: Fleet & Crew Assignments -->
                        <div class="tab-pane fade" id="assignments" role="tabpanel">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div>
                                    <h6 class="fw-bold mb-0">Assigned Fleet, Drivers & Guides</h6>
                                    <span class="small text-muted">Field operations schedule with overlap conflict protection</span>
                                </div>
                                @if(Auth::user()->hasRole(['admin', 'agent']))
                                    <button type="button" class="btn btn-sm btn-uwt-primary" data-bs-toggle="modal" data-bs-target="#assignResourceModal">
                                        <i class="bi bi-plus-circle me-1"></i> Assign Resource
                                    </button>
                                @endif
                            </div>

                            <div class="table-responsive">
                                <table class="table table-sm table-hover align-middle mb-0">
                                    <thead>
                                        <tr>
                                            <th>Type</th>
                                            <th>Resource Details</th>
                                            <th>Assignment Dates</th>
                                            <th>Duration</th>
                                            <th class="text-end">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($booking->assignments as $a)
                                            @php $res = $a->resource(); @endphp
                                            <tr>
                                                <td>
                                                    @if($a->resource_type === 'vehicle')
                                                        <span class="badge bg-primary-subtle text-primary"><i class="bi bi-truck-front me-1"></i> Vehicle</span>
                                                    @elseif($a->resource_type === 'driver')
                                                        <span class="badge bg-success-subtle text-success"><i class="bi bi-person-badge me-1"></i> Driver</span>
                                                    @else
                                                        <span class="badge bg-warning-subtle text-warning"><i class="bi bi-binoculars me-1"></i> Guide</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($res)
                                                        <div class="fw-bold">{{ $res->name }}</div>
                                                        <div class="small text-muted">
                                                            @if($a->resource_type === 'vehicle')
                                                                Plate: <span class="font-monospace">{{ $res->plate_number }}</span> &bull; {{ $res->type }} ({{ $res->seats }} window seats)
                                                            @elseif($a->resource_type === 'driver')
                                                                Phone: {{ $res->phone }} &bull; Licence: {{ $res->licence_number ?? 'N/A' }}
                                                            @else
                                                                Phone: {{ $res->phone }} &bull; Permit: {{ $res->permit_number ?? 'N/A' }}
                                                            @endif
                                                        </div>
                                                    @else
                                                        <span class="text-danger small">Unknown / Deleted Resource</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <i class="bi bi-calendar3 me-1 text-muted"></i>
                                                    {{ $a->start_date ? $a->start_date->format('d M Y') : 'N/A' }} &rarr; {{ $a->end_date ? $a->end_date->format('d M Y') : 'N/A' }}
                                                </td>
                                                <td>
                                                    @if($a->start_date && $a->end_date)
                                                        <span class="badge bg-light text-dark border">{{ $a->start_date->diffInDays($a->end_date) + 1 }} days</span>
                                                    @endif
                                                </td>
                                                <td class="text-end">
                                                    @if(Auth::user()->hasRole(['admin', 'agent']))
                                                        <form action="{{ route('admin.bookings.assignments.destroy', ['booking' => $booking->id, 'assignment' => $a->id]) }}" method="POST" class="d-inline" onsubmit="return confirm('Remove this resource assignment from the booking?');">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-outline-danger btn-sm p-1" title="Unassign Resource">
                                                                <i class="bi bi-trash"></i>
                                                            </button>
                                                        </form>
                                                    @endif
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5" class="text-center py-4 text-muted">
                                                    <i class="bi bi-truck text-muted fs-4 d-block mb-1"></i>
                                                    No safari vehicles, drivers, or guides assigned to this booking yet.<br>
                                                    <small>Click "Assign Resource" above to schedule cruisers and crew.</small>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Tab 5: Trip Documents -->
                        <div class="tab-pane fade" id="documents" role="tabpanel">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div>
                                    <h6 class="fw-bold mb-0">Attached Trip Documents & Files</h6>
                                    <span class="small text-muted">Flight tickets, national park permits, travel insurance, and contracts</span>
                                </div>
                                <button type="button" class="btn btn-sm btn-uwt-primary" data-bs-toggle="modal" data-bs-target="#uploadDocumentModal">
                                    <i class="bi bi-cloud-arrow-up me-1"></i> Upload Document
                                </button>
                            </div>

                            <div class="table-responsive">
                                <table class="table table-sm table-hover align-middle mb-0">
                                    <thead>
                                        <tr>
                                            <th>Document Title</th>
                                            <th>Client Visibility</th>
                                            <th>Uploaded By</th>
                                            <th>Date Attached</th>
                                            <th class="text-end">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($booking->documents as $doc)
                                            <tr>
                                                <td>
                                                    <div class="fw-bold d-flex align-items-center gap-2">
                                                        <i class="bi bi-file-earmark-arrow-down text-primary"></i>
                                                        {{ $doc->title }}
                                                    </div>
                                                    <small class="text-muted font-monospace">{{ basename($doc->file_path) }}</small>
                                                </td>
                                                <td>
                                                    <form action="{{ route('admin.bookings.documents.toggle_visibility', ['booking' => $booking->id, 'document' => $doc->id]) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        @method('PATCH')
                                                        @if($doc->visible_to_client)
                                                            <button type="submit" class="btn btn-sm btn-success-subtle text-success p-1 border-0" title="Click to hide from client portal">
                                                                <i class="bi bi-eye-fill me-1"></i> Visible to Client
                                                            </button>
                                                        @else
                                                            <button type="submit" class="btn btn-sm btn-secondary p-1 border-0" title="Click to show in client portal">
                                                                <i class="bi bi-eye-slash-fill me-1"></i> Internal Only
                                                            </button>
                                                        @endif
                                                    </form>
                                                </td>
                                                <td>
                                                    <small class="text-muted">{{ $doc->uploader->name ?? 'Staff' }}</small>
                                                </td>
                                                <td>
                                                    <small class="text-muted">{{ $doc->created_at->format('d M Y - H:i') }}</small>
                                                </td>
                                                <td class="text-end">
                                                    <div class="btn-group btn-group-sm">
                                                        <a href="{{ route('admin.bookings.documents.download', ['booking' => $booking->id, 'document' => $doc->id]) }}" class="btn btn-outline-primary" title="Download Document">
                                                            <i class="bi bi-download"></i>
                                                        </a>
                                                        <form action="{{ route('admin.bookings.documents.destroy', ['booking' => $booking->id, 'document' => $doc->id]) }}" method="POST" class="d-inline" onsubmit="return confirm('Permanently delete this document?');">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-outline-danger" title="Delete Document">
                                                                <i class="bi bi-trash"></i>
                                                            </button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5" class="text-center py-4 text-muted">
                                                    <i class="bi bi-folder-x text-muted fs-4 d-block mb-1"></i>
                                                    No documents uploaded for this booking yet.<br>
                                                    <small>Upload flight tickets, permits, or rooming lists to securely store them.</small>
                                                </td>
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

        <!-- Right: Status Management & History Timeline -->
        <div class="col-lg-4">

            <!-- Status Transition Card -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white">
                    <h5 class="h6 mb-0 fw-bold"><i class="bi bi-arrow-repeat text-uwt-primary me-2"></i> Update Booking Status</h5>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('admin.bookings.update_status', $booking->id) }}" method="POST">
                        @csrf
                        @method('PATCH')

                        <div class="mb-3">
                            <label for="status" class="form-label small fw-semibold">Current Booking Stage</label>
                            <select name="status" id="status" class="form-select">
                                @foreach(\App\Models\Booking::STATUSES as $st)
                                    <option value="{{ $st }}" {{ $booking->status === $st ? 'selected' : '' }}>
                                        {{ ucfirst(str_replace('_', ' ', $st)) }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="form-text" style="font-size: 0.75rem;">
                                Order: inquiry &rarr; quote_sent &rarr; confirmed &rarr; paid_in_full &rarr; in_progress &rarr; completed (or cancelled)
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="note" class="form-label small fw-semibold">Reason / Note for Status Change</label>
                            <textarea name="note" id="note" rows="3" class="form-control" placeholder="e.g. Deposit verified, moved to confirmed..."></textarea>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-uwt-primary">
                                <i class="bi bi-check2-circle me-1"></i> Update Status & Log
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Status History Timeline Card -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white">
                    <h5 class="h6 mb-0 fw-bold"><i class="bi bi-clock-history text-uwt-primary me-2"></i> Status Timeline Audit</h5>
                </div>
                <div class="card-body p-3">
                    <div class="timeline ps-2">
                        @forelse($history as $item)
                            <div class="mb-3 border-start border-2 border-warning ps-3 position-relative">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <span class="badge-status status-{{ $item->to_status }}" style="font-size: 0.65rem;">
                                        {{ ucfirst(str_replace('_', ' ', $item->to_status)) }}
                                    </span>
                                    <small class="text-muted" style="font-size: 0.75rem;">
                                        {{ $item->created_at->format('d M Y, H:i') }}
                                    </small>
                                </div>
                                <div class="small fw-semibold text-dark">{{ $item->note }}</div>
                                <div class="small text-muted" style="font-size: 0.75rem;">
                                    By: {{ $item->user->name ?? 'System' }}
                                </div>
                            </div>
                        @empty
                            <div class="text-muted small text-center py-3">No status changes recorded yet.</div>
                        @endforelse
                    </div>
                </div>
            </div>

        </div>
    </div>

</div>

<!-- Modal: Add Itinerary Day -->
<div class="modal fade" id="addDayModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="{{ route('admin.bookings.days.store', $booking->id) }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title h6 fw-bold">Add Day to Itinerary - {{ $booking->reference }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label small fw-semibold">Day Number *</label>
                            <input type="number" name="day_number" class="form-control" value="{{ $booking->bookingDays->count() + 1 }}" required min="1">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-semibold">Date</label>
                            @php
                                $suggestedDate = $booking->start_date ? \Carbon\Carbon::parse($booking->start_date)->addDays($booking->bookingDays->count())->format('Y-m-d') : '';
                            @endphp
                            <input type="date" name="date" class="form-control" value="{{ $suggestedDate }}">
                        </div>
                        <div class="col-md-5">
                            <label class="form-label small fw-semibold">Destination</label>
                            <input type="text" name="destination" class="form-control" placeholder="e.g. Ngorongoro Crater">
                        </div>
                        <div class="col-12">
                            <label class="form-label small fw-semibold">Day Title *</label>
                            <input type="text" name="title" class="form-control" placeholder="e.g. Descent into Ngorongoro Crater & Big Five Safari" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Accommodation (Lodge / Camp)</label>
                            <input type="text" name="accommodation" class="form-control" placeholder="e.g. Ngorongoro Serena Safari Lodge">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Meal Plan</label>
                            <input type="text" name="meals" class="form-control" value="Breakfast, Lunch, Dinner">
                        </div>
                        <div class="col-12">
                            <label class="form-label small fw-semibold">Safari Focus & Activities</label>
                            <input type="text" name="activities" class="form-control" placeholder="e.g. 6-hour crater floor game drive, picnic lunch near hippo pool">
                        </div>
                        <div class="col-12">
                            <label class="form-label small fw-semibold">Detailed Day Description</label>
                            <textarea name="description" rows="4" class="form-control" placeholder="Detailed narrative for guest vouchers..."></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-uwt-primary btn-sm">Add Itinerary Day</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal: Add Traveler -->
<div class="modal fade" id="addTravelerModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.bookings.travelers.store', $booking->id) }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title h6 fw-bold">Register Passenger to Booking {{ $booking->reference }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label small fw-semibold">Full Legal Name *</label>
                            <input type="text" name="full_name" class="form-control" placeholder="e.g. Jane Marie Doe" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Nationality</label>
                            <input type="text" name="nationality" class="form-control" placeholder="e.g. British, American">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Date of Birth</label>
                            <input type="date" name="date_of_birth" class="form-control">
                        </div>
                        <div class="col-12">
                            <label class="form-label small fw-semibold">Passport Number (Encrypted at rest)</label>
                            <input type="text" name="passport_number" class="form-control" placeholder="Encrypted at rest with AES-256">
                        </div>
                        <div class="col-12">
                            <label class="form-label small fw-semibold">Dietary & Medical Requirements</label>
                            <textarea name="dietary_notes" rows="2" class="form-control" placeholder="e.g. Vegetarian, Nut Allergy, Diabetic..."></textarea>
                        </div>
                        <div class="col-12">
                            <label class="form-label small fw-semibold">Emergency Contact</label>
                            <input type="text" name="emergency_contact" class="form-control" placeholder="e.g. John Doe (+1 555 987 6543)">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-uwt-primary btn-sm">Add Traveler</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal: Email Quote -->
<div class="modal fade" id="emailQuoteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.bookings.send_quote', $booking->id) }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title h6 fw-bold">Email Quote to Client</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="small text-muted mb-3">
                        This will automatically generate the official <strong>Quotation PDF</strong> and email it directly to <strong>{{ $booking->client->email }}</strong>.
                    </p>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Recipient</label>
                        <input type="text" class="form-control bg-light" value="{{ $booking->client->full_name }} &lt;{{ $booking->client->email }}&gt;" readonly>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Personalized Note from Consultant (Optional)</label>
                        <textarea name="message" rows="4" class="form-control" placeholder="e.g. Hello {{ $booking->client->full_name }}, following our call, we have included the Ngorongoro crater rim lodge option..."></textarea>
                    </div>
                    <div class="alert alert-info py-2 small mb-0">
                        <i class="bi bi-info-circle me-1"></i> Dispatches with attached PDF: <code>UWT-Quote-{{ $booking->reference }}.pdf</code> and updates status to <em>Quote Sent</em>.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-uwt-primary btn-sm">
                        <i class="bi bi-send me-1"></i> Send Official Quote
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal: Email Itinerary Voucher -->
<div class="modal fade" id="emailItineraryModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.bookings.send_itinerary', $booking->id) }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title h6 fw-bold">Email Itinerary Voucher to Client</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="small text-muted mb-3">
                        This will generate the confirmed <strong>Full Itinerary Voucher & Packing Guide PDF</strong> and email it to <strong>{{ $booking->client->email }}</strong>.
                    </p>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Recipient</label>
                        <input type="text" class="form-control bg-light" value="{{ $booking->client->full_name }} &lt;{{ $booking->client->email }}&gt;" readonly>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Personalized Coordinator Note (Optional)</label>
                        <textarea name="message" rows="4" class="form-control" placeholder="e.g. We look forward to meeting you at Kilimanjaro Airport! Our driver-guide will be holding a Uhalisia wa Tanzania sign..."></textarea>
                    </div>
                    <div class="alert alert-success py-2 small mb-0">
                        <i class="bi bi-check-circle me-1"></i> Includes day-by-day vouchers, packing checklist, and 24/7 hotline.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-uwt-olive btn-sm text-white">
                        <i class="bi bi-send me-1"></i> Send Travel Voucher
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal: Assign Resource -->
<div class="modal fade" id="assignResourceModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.bookings.assignments.store', $booking->id) }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title h6 fw-bold"><i class="bi bi-truck-front text-uwt-primary me-2"></i> Assign Fleet / Field Resource</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="assign_resource_type" class="form-label small fw-semibold">Resource Type <span class="text-danger">*</span></label>
                        <select name="resource_type" id="assign_resource_type" class="form-select" required onchange="filterResourceDropdown()">
                            <option value="vehicle">Safari 4x4 Cruiser / Minivan</option>
                            <option value="driver">Safari Driver</option>
                            <option value="guide">Safari & Mountain Naturalist Guide</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="assign_resource_id" class="form-label small fw-semibold">Select Resource <span class="text-danger">*</span></label>
                        <select name="resource_id" id="assign_resource_id" class="form-select" required>
                            <!-- Populated dynamically via JS -->
                        </select>
                    </div>

                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label for="assign_start_date" class="form-label small fw-semibold">Start Date <span class="text-danger">*</span></label>
                            <input type="date" name="start_date" id="assign_start_date" class="form-control" value="{{ $booking->start_date ? $booking->start_date->format('Y-m-d') : '' }}" required>
                        </div>
                        <div class="col-6">
                            <label for="assign_end_date" class="form-label small fw-semibold">End Date <span class="text-danger">*</span></label>
                            <input type="date" name="end_date" id="assign_end_date" class="form-control" value="{{ $booking->end_date ? $booking->end_date->format('Y-m-d') : '' }}" required>
                        </div>
                    </div>

                    <div class="alert alert-warning py-2 small mb-0">
                        <i class="bi bi-shield-check me-1"></i> Overlap detection is automatically enforced. If the resource is already booked on another active safari on overlapping dates, double-booking is blocked.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-uwt-primary btn-sm">
                        <i class="bi bi-check2-circle me-1"></i> Save Assignment
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal: Upload Document -->
<div class="modal fade" id="uploadDocumentModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.bookings.documents.store', $booking->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title h6 fw-bold"><i class="bi bi-cloud-arrow-up text-uwt-primary me-2"></i> Upload Trip Document</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="doc_title" class="form-label small fw-semibold">Document Title <span class="text-danger">*</span></label>
                        <input type="text" name="title" id="doc_title" class="form-control" placeholder="e.g. Flight Tickets, TANAPA Park Permits, Insurance" required>
                    </div>

                    <div class="mb-3">
                        <label for="doc_file" class="form-label small fw-semibold">File Upload <span class="text-danger">*</span></label>
                        <input type="file" name="file" id="doc_file" class="form-control" accept=".pdf,.jpg,.jpeg,.png,.doc,.docx,.webp" required>
                        <div class="form-text small">Accepted: PDF, JPG, PNG, DOC, DOCX (Max: 10MB)</div>
                    </div>

                    <div class="form-check form-switch mb-2">
                        <input class="form-check-input" type="checkbox" name="visible_to_client" id="visible_to_client" value="1" checked>
                        <label class="form-check-label small fw-semibold" for="visible_to_client">
                            Visible to Client in Portal
                        </label>
                        <div class="form-text small">Turn off if this is an internal accounting receipt or private lodge contract.</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-uwt-primary btn-sm">
                        <i class="bi bi-upload me-1"></i> Upload & Attach
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Record Payment Modal -->
<div class="modal fade" id="recordPaymentModal" tabindex="-1" aria-labelledby="recordPaymentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="{{ route('admin.payments.store') }}" method="POST">
                @csrf
                <input type="hidden" name="booking_id" value="{{ $booking->id }}">

                <div class="modal-header bg-light">
                    <h5 class="modal-title h6 fw-bold" id="recordPaymentModalLabel">
                        <i class="bi bi-cash-stack text-success me-2"></i> Record Client Payment Receipt
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="alert alert-light border small mb-3">
                        <div class="d-flex justify-content-between mb-1">
                            <span class="text-muted">Booking Reference:</span>
                            <strong>{{ $booking->reference }}</strong>
                        </div>
                        <div class="d-flex justify-content-between mb-1">
                            <span class="text-muted">Quoted Net Price:</span>
                            <strong>${{ number_format($booking->netPriceUsd(), 2) }} USD</strong>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="text-muted">Remaining Balance:</span>
                            <strong class="{{ $booking->balanceUsd() > 0 ? 'text-danger' : 'text-success' }}">
                                ${{ number_format($booking->balanceUsd(), 2) }} USD
                            </strong>
                        </div>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Payment Date <span class="text-danger">*</span></label>
                            <input type="date" name="paid_on" class="form-control form-control-sm" value="{{ date('Y-m-d') }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Payment Type <span class="text-danger">*</span></label>
                            <select name="type" class="form-select form-select-sm" required>
                                <option value="deposit" {{ $booking->payments->isEmpty() ? 'selected' : '' }}>Deposit (30%)</option>
                                <option value="balance" {{ $booking->payments->isNotEmpty() ? 'selected' : '' }}>Balance Settlement</option>
                                <option value="refund">Refund</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Amount <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" name="amount" class="form-control form-control-sm" min="0.01" value="{{ $booking->balanceUsd() > 0 ? $booking->balanceUsd() : '' }}" placeholder="0.00" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Currency <span class="text-danger">*</span></label>
                            <select name="currency" class="form-select form-select-sm" required>
                                <option value="USD">USD ($)</option>
                                <option value="TZS">TZS (Shillings)</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Exchange Rate (TZS/USD) <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" name="exchange_rate_used" class="form-control form-control-sm" value="{{ $currentExchangeRate }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Payment Method <span class="text-danger">*</span></label>
                            <select name="method" class="form-select form-select-sm" required>
                                <option value="bank">Bank Wire Transfer</option>
                                <option value="card">Credit / Debit Card</option>
                                <option value="cash">Cash</option>
                                <option value="mobile_money">Mobile Money (M-Pesa)</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label small fw-semibold">Bank / Transaction Reference</label>
                            <input type="text" name="reference" class="form-control form-control-sm" placeholder="e.g. SWIFT Ref or Bank Slip #">
                        </div>
                        <div class="col-12">
                            <label class="form-label small fw-semibold">Notes / Receipt Remarks</label>
                            <textarea name="notes" rows="2" class="form-control form-control-sm" placeholder="Sender name, notes..."></textarea>
                        </div>
                        <div class="col-12">
                            <div class="form-check form-switch small">
                                <input class="form-check-input" type="checkbox" name="transition_status" id="modal_transition_status" value="1" checked>
                                <label class="form-check-label fw-semibold" for="modal_transition_status">
                                    Auto-advance stage (e.g. to Confirmed or Paid in Full)
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-uwt-olive btn-sm">
                        <i class="bi bi-save me-1"></i> Save Payment Receipt
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Log Trip Expense Modal -->
<div class="modal fade" id="recordExpenseModal" tabindex="-1" aria-labelledby="recordExpenseModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="{{ route('admin.expenses.store') }}" method="POST">
                @csrf
                <input type="hidden" name="booking_id" value="{{ $booking->id }}">

                <div class="modal-header bg-light">
                    <h5 class="modal-title h6 fw-bold" id="recordExpenseModalLabel">
                        <i class="bi bi-receipt text-danger me-2"></i> Log Trip Operational Expense
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Expense Date <span class="text-danger">*</span></label>
                            <input type="date" name="expense_date" class="form-control form-control-sm" value="{{ date('Y-m-d') }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Category <span class="text-danger">*</span></label>
                            <select name="category" class="form-select form-select-sm" required>
                                <option value="">-- Select Category --</option>
                                @foreach(\App\Models\Expense::CATEGORIES as $k => $l)
                                    <option value="{{ $k }}">{{ $l }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label small fw-semibold">Description / Payee <span class="text-danger">*</span></label>
                            <input type="text" name="description" class="form-control form-control-sm" placeholder="e.g. TANAPA Park Entry Fees, Fuel Shell Arusha, Lodge Deposit..." required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Amount <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" name="amount" class="form-control form-control-sm" min="0.01" placeholder="0.00" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Currency <span class="text-danger">*</span></label>
                            <select name="currency" class="form-select form-select-sm" required>
                                <option value="TZS">TZS (Tanzanian Shillings)</option>
                                <option value="USD">USD ($)</option>
                            </select>
                            <div class="form-text" style="font-size: 0.7rem;">Auto-converts at {{ number_format($currentExchangeRate, 0) }} TZS/USD.</div>
                        </div>
                        <div class="col-12">
                            <label class="form-label small fw-semibold">Notes / Receipt Voucher #</label>
                            <textarea name="notes" rows="2" class="form-control form-control-sm" placeholder="Receipt voucher number, driver name, fuel slip..."></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-uwt-primary btn-sm">
                        <i class="bi bi-save me-1"></i> Log Expense
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
const resourceData = {
    vehicle: [
        @foreach($vehicles as $v)
            { id: {{ $v->id }}, name: "{{ addslashes($v->name) }} ({{ $v->plate_number }}) - {{ $v->seats }} window seats" },
        @endforeach
    ],
    driver: [
        @foreach($drivers as $d)
            { id: {{ $d->id }}, name: "{{ addslashes($d->name) }} ({{ $d->phone }})" },
        @endforeach
    ],
    guide: [
        @foreach($guides as $g)
            { id: {{ $g->id }}, name: "{{ addslashes($g->name) }} ({{ $g->phone }})" },
        @endforeach
    ]
};

function filterResourceDropdown() {
    const typeSelect = document.getElementById('assign_resource_type');
    const resSelect = document.getElementById('assign_resource_id');
    if (!typeSelect || !resSelect) return;

    const list = resourceData[typeSelect.value] || [];
    resSelect.innerHTML = '';

    if (list.length === 0) {
        const opt = document.createElement('option');
        opt.value = '';
        opt.textContent = 'No active resources in this category';
        resSelect.appendChild(opt);
        return;
    }

    list.forEach(item => {
        const opt = document.createElement('option');
        opt.value = item.id;
        opt.textContent = item.name;
        resSelect.appendChild(opt);
    });
}

document.addEventListener('DOMContentLoaded', filterResourceDropdown);
</script>

@endsection
