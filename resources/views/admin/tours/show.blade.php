@extends('layouts.admin')

@section('page_title', $tour->name)

@section('admin_content')
<div class="container-fluid p-0">

    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-2">
        <div class="d-flex align-items-center gap-2">
            <a href="{{ route('admin.tours.index') }}" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-arrow-left"></i> Back to Tours
            </a>
            <h2 class="h4 brand-font text-uwt-dark mb-0">{{ $tour->name }}</h2>
            <span class="badge bg-cream text-uwt-dark border ms-2">{{ $tour->days }} Days</span>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('book.show', ['tour' => $tour->slug]) }}" target="_blank" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-box-arrow-up-right me-1"></i> Test on /book Form
            </a>
            <a href="{{ route('admin.tours.edit', $tour->id) }}" class="btn btn-uwt-primary btn-sm">
                <i class="bi bi-pencil me-1"></i> Edit Tour Info
            </a>
        </div>
    </div>

    <div class="row g-4">
        <!-- Tour Info Column -->
        <div class="col-lg-4">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white">
                    <h5 class="h6 mb-0 fw-bold"><i class="bi bi-info-circle text-uwt-primary me-2"></i> Package Summary</h5>
                </div>
                <div class="card-body p-4">
                    <div class="mb-3">
                        <label class="small text-muted d-block">Public URL Slug</label>
                        <span class="font-monospace text-uwt-primary fw-bold">{{ $tour->slug }}</span>
                        <div class="small text-muted">Used by website buttons: <code>/book?tour={{ $tour->slug }}</code></div>
                    </div>

                    <div class="mb-3">
                        <label class="small text-muted d-block">Type / Category</label>
                        <span class="badge bg-cream text-uwt-dark border">{{ ucfirst($tour->type) }}</span>
                    </div>

                    <div class="mb-3">
                        <label class="small text-muted d-block">Base Price</label>
                        <h4 class="fw-bold text-success mb-0">${{ number_format($tour->base_price_usd, 2) }} USD</h4>
                        <small class="text-muted">Per person sharing (customizable on bookings)</small>
                    </div>

                    <div class="mb-3">
                        <label class="small text-muted d-block">Duration</label>
                        <span class="fw-bold">{{ $tour->days }} Days &bull; {{ $tour->tourDays->count() }} Blueprint Days</span>
                    </div>

                    <div class="border-top pt-3">
                        <label class="small text-muted d-block fw-bold mb-1">Trip Overview:</label>
                        <p class="small text-muted mb-0">{{ $tour->summary }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tour Days List Column -->
        <div class="col-lg-8">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="h6 mb-0 fw-bold"><i class="bi bi-map text-uwt-gold me-2"></i> Day-by-Day Master Blueprint</h5>
                        <small class="text-muted">{{ $tour->tourDays->count() }} Blueprint Days configured</small>
                    </div>
                    <button type="button" class="btn btn-uwt-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addTourDayModal">
                        <i class="bi bi-plus-circle me-1"></i> Add Blueprint Day
                    </button>
                </div>
                <div class="card-body p-4">
                    @forelse($tour->tourDays as $td)
                        <div class="border rounded p-3 mb-3 bg-light">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <div class="d-flex align-items-center gap-2">
                                    <span class="badge bg-uwt-primary text-white fw-bold">Day {{ $td->day_number }}</span>
                                    <h6 class="fw-bold mb-0 text-uwt-dark">{{ $td->title }}</h6>
                                </div>
                                <div class="d-flex align-items-center gap-1">
                                    <span class="small text-muted me-2"><i class="bi bi-geo-alt text-danger me-1"></i> {{ $td->destination }}</span>
                                    <button type="button" class="btn btn-sm btn-outline-secondary p-1" data-bs-toggle="modal" data-bs-target="#editTourDayModal{{ $td->id }}" title="Edit Blueprint Day">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                    <form action="{{ route('admin.tours.days.destroy', ['tour' => $tour->id, 'tourDay' => $td->id]) }}" method="POST" class="d-inline m-0" onsubmit="return confirm('Delete Day {{ $td->day_number }} from tour blueprint?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger p-1" title="Delete">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>

                            <div class="row g-2 small text-muted mb-2">
                                <div class="col-md-6">
                                    <i class="bi bi-house text-uwt-gold me-1"></i> <strong>Lodge/Camp:</strong> {{ $td->accommodation_note }}
                                </div>
                                <div class="col-md-6">
                                    <i class="bi bi-cup-hot text-uwt-olive me-1"></i> <strong>Meals:</strong> {{ $td->meals }}
                                </div>
                            </div>

                            @if($td->activities)
                                <div class="small text-muted mb-2">
                                    <strong>Activities:</strong> {{ $td->activities }}
                                </div>
                            @endif

                            <p class="small text-dark mb-0">{{ $td->description }}</p>
                        </div>

                        <!-- Edit Tour Day Modal -->
                        <div class="modal fade" id="editTourDayModal{{ $td->id }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <form action="{{ route('admin.tours.days.update', ['tour' => $tour->id, 'tourDay' => $td->id]) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="modal-header">
                                            <h5 class="modal-title h6 fw-bold">Edit Blueprint Day {{ $td->day_number }} - {{ $tour->name }}</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="row g-3">
                                                <div class="col-md-3">
                                                    <label class="form-label small fw-semibold">Day Number *</label>
                                                    <input type="number" name="day_number" class="form-control" value="{{ $td->day_number }}" required min="1">
                                                </div>
                                                <div class="col-md-9">
                                                    <label class="form-label small fw-semibold">Destination</label>
                                                    <input type="text" name="destination" class="form-control" value="{{ $td->destination }}" placeholder="e.g. Serengeti National Park">
                                                </div>
                                                <div class="col-12">
                                                    <label class="form-label small fw-semibold">Day Title *</label>
                                                    <input type="text" name="title" class="form-control" value="{{ $td->title }}" required>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label small fw-semibold">Recommended Lodge / Camp</label>
                                                    <input type="text" name="accommodation_note" class="form-control" value="{{ $td->accommodation_note }}">
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label small fw-semibold">Meal Plan</label>
                                                    <input type="text" name="meals" class="form-control" value="{{ $td->meals }}">
                                                </div>
                                                <div class="col-12">
                                                    <label class="form-label small fw-semibold">Activities & Highlights</label>
                                                    <input type="text" name="activities" class="form-control" value="{{ $td->activities }}">
                                                </div>
                                                <div class="col-12">
                                                    <label class="form-label small fw-semibold">Master Description</label>
                                                    <textarea name="description" rows="4" class="form-control">{{ $td->description }}</textarea>
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
                        <div class="text-center py-5 text-muted border rounded bg-white">
                            <i class="bi bi-map fs-2 d-block mb-2 text-uwt-gold"></i>
                            <h6 class="fw-bold">No days outlined for this tour template yet</h6>
                            <p class="small text-muted mb-3">Add daily blueprints that will automatically populate when bookings are created.</p>
                            <button type="button" class="btn btn-uwt-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addTourDayModal">
                                <i class="bi bi-plus-circle me-1"></i> Add Day 1
                            </button>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

</div>

<!-- Modal: Add Tour Day -->
<div class="modal fade" id="addTourDayModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="{{ route('admin.tours.days.store', $tour->id) }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title h6 fw-bold">Add Blueprint Day to {{ $tour->name }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label small fw-semibold">Day Number *</label>
                            <input type="number" name="day_number" class="form-control" value="{{ $tour->tourDays->count() + 1 }}" required min="1">
                        </div>
                        <div class="col-md-9">
                            <label class="form-label small fw-semibold">Destination</label>
                            <input type="text" name="destination" class="form-control" placeholder="e.g. Tarangire National Park">
                        </div>
                        <div class="col-12">
                            <label class="form-label small fw-semibold">Day Title *</label>
                            <input type="text" name="title" class="form-control" placeholder="e.g. Arusha to Tarangire - Land of the Giants" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Recommended Lodge / Camp</label>
                            <input type="text" name="accommodation_note" class="form-control" placeholder="e.g. Tarangire Safari Lodge">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Meal Plan</label>
                            <input type="text" name="meals" class="form-control" value="Lunch, Dinner">
                        </div>
                        <div class="col-12">
                            <label class="form-label small fw-semibold">Activities & Highlights</label>
                            <input type="text" name="activities" class="form-control" placeholder="e.g. Afternoon game drive tracking tree-climbing lions and elephants">
                        </div>
                        <div class="col-12">
                            <label class="form-label small fw-semibold">Narrative Description</label>
                            <textarea name="description" rows="4" class="form-control" placeholder="Detailed master description..."></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-uwt-primary btn-sm">Add Blueprint Day</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
