@extends('layouts.admin')

@section('page_title', 'Lead Details: ' . $lead->name)

@section('admin_content')
<div class="container-fluid p-0">

    <!-- Top Navigation Bar -->
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-2">
        <div class="d-flex align-items-center gap-2">
            <a href="{{ route('admin.leads.index') }}" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-arrow-left"></i> Back to Leads
            </a>
            <h2 class="h4 brand-font text-uwt-dark mb-0">Lead #{{ $lead->id }}: {{ $lead->name }}</h2>
            <span class="badge-status status-{{ $lead->status }} ms-2">
                {{ ucfirst($lead->status) }}
            </span>
        </div>

        <div class="d-flex gap-2">
            @if($lead->booking)
                <a href="{{ route('admin.bookings.show', $lead->booking->id) }}" class="btn btn-success btn-sm">
                    <i class="bi bi-calendar-check me-1"></i> View Linked Booking ({{ $lead->booking->reference }})
                </a>
            @else
                <form action="{{ route('admin.leads.convert', $lead->id) }}" method="POST" onsubmit="return confirm('Convert this lead to a Client and Booking?');">
                    @csrf
                    <button type="submit" class="btn btn-uwt-primary">
                        <i class="bi bi-arrow-right-circle-fill me-1"></i> Convert to Booking
                    </button>
                </form>
            @endif
        </div>
    </div>

    <div class="row g-4">
        <!-- Left Column: Lead Info & Inquiry Message -->
        <div class="col-lg-7">

            <!-- Lead Contact & Trip Summary -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <span><i class="bi bi-person-lines-fill text-uwt-primary me-2"></i> Lead Profile & Inquiry</span>
                    <span class="small text-muted">Received: {{ $lead->created_at->format('d M Y, H:i') }}</span>
                </div>
                <div class="card-body p-4">
                    <div class="row g-3 mb-4">
                        <div class="col-sm-6">
                            <label class="small text-muted d-block">Full Name</label>
                            <span class="fw-bold fs-6">{{ $lead->name }}</span>
                        </div>
                        <div class="col-sm-6">
                            <label class="small text-muted d-block">Email Address</label>
                            <a href="mailto:{{ $lead->email }}" class="fw-semibold text-uwt-primary">
                                {{ $lead->email }}
                            </a>
                        </div>
                        <div class="col-sm-6">
                            <label class="small text-muted d-block">Country of Residence</label>
                            <span class="fw-semibold">{{ $lead->country ?? 'Not specified' }}</span>
                        </div>
                        <div class="col-sm-6">
                            <label class="small text-muted d-block">Safari / Tour Interest</label>
                            <span class="badge bg-cream text-uwt-dark fw-bold border">
                                {{ $lead->safari_interest ?? 'Custom / General' }}
                            </span>
                        </div>
                        <div class="col-sm-6">
                            <label class="small text-muted d-block">Inquiry Source</label>
                            <span class="badge bg-light text-dark border">
                                {{ $lead->source === 'website_quote_form' ? 'Website Request Quote Form' : ($lead->source === 'book_form' ? 'Public /book Form' : 'Manual Entry') }}
                            </span>
                        </div>
                        <div class="col-sm-6">
                            <label class="small text-muted d-block">Client Record</label>
                            @if($lead->client)
                                <a href="{{ route('admin.clients.show', $lead->client->id) }}" class="fw-bold text-success text-decoration-none">
                                    <i class="bi bi-check-circle-fill me-1"></i> {{ $lead->client->full_name }} (View Client)
                                </a>
                            @else
                                <span class="text-muted small">Not matched yet (will auto-create on conversion)</span>
                            @endif
                        </div>
                    </div>

                    @if($lead->utm_source || $lead->utm_medium)
                        <div class="p-2 bg-light rounded border mb-3 small">
                            <span class="fw-bold text-muted me-2"><i class="bi bi-megaphone me-1"></i> Marketing Attribution:</span>
                            Source: <strong>{{ $lead->utm_source ?? '-' }}</strong> &bull; Medium: <strong>{{ $lead->utm_medium ?? '-' }}</strong>
                        </div>
                    @endif

                    <div class="border-top pt-3">
                        <label class="small text-muted d-block mb-1 fw-bold">Inquiry Details / Message:</label>
                        <div class="p-3 bg-light rounded border text-pre-line" style="white-space: pre-line; font-size: 0.95rem;">
                            {{ $lead->message ?: 'No additional notes provided by client.' }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Debugging: Raw Webhook/Form Payload -->
            <div class="card shadow-sm border-0 mb-4" x-data="{ showPayload: false }">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <span class="fw-bold"><i class="bi bi-code-slash text-uwt-gold me-2"></i> Raw Payload (Webhook & Form Debugger)</span>
                    <button type="button" @click="showPayload = !showPayload" class="btn btn-sm btn-outline-secondary">
                        <span x-text="showPayload ? 'Hide JSON' : 'Inspect JSON'">Inspect JSON</span>
                    </button>
                </div>
                <div class="card-body p-3" x-show="showPayload" x-cloak>
                    <p class="small text-muted mb-2">
                        This contains the exact raw data received from WordPress (Elementor Pro / Contact Form 7) or the /book form for debugging field-name mappings:
                    </p>
                    <pre class="bg-dark text-white p-3 rounded small mb-0" style="max-height: 300px; overflow-y: auto;">{{ json_encode($lead->raw_payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</pre>
                </div>
            </div>

        </div>

        <!-- Right Column: Status Controls & History Timeline -->
        <div class="col-lg-5">

            <!-- Status & Assignment Card -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white">
                    <h5 class="h6 mb-0 fw-bold"><i class="bi bi-sliders text-uwt-primary me-2"></i> Update Lead Status</h5>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('admin.leads.status', $lead->id) }}" method="POST">
                        @csrf
                        @method('PATCH')

                        <div class="mb-3">
                            <label for="status" class="form-label small fw-semibold">Status Stage</label>
                            <select name="status" id="status" class="form-select">
                                <option value="new" {{ $lead->status === 'new' ? 'selected' : '' }}>New Inquiry</option>
                                <option value="contacted" {{ $lead->status === 'contacted' ? 'selected' : '' }}>Contacted (In Discussion)</option>
                                <option value="quoted" {{ $lead->status === 'quoted' ? 'selected' : '' }}>Quoted (Itinerary & Price Sent)</option>
                                <option value="won" {{ $lead->status === 'won' ? 'selected' : '' }}>Won (Converted)</option>
                                <option value="lost" {{ $lead->status === 'lost' ? 'selected' : '' }}>Lost (Not Proceeding)</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="assigned_user_id" class="form-label small fw-semibold">Assigned Tour Agent</label>
                            <select name="assigned_user_id" id="assigned_user_id" class="form-select">
                                <option value="">-- Unassigned --</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" {{ $lead->assigned_user_id === $user->id ? 'selected' : '' }}>
                                        {{ $user->name }} ({{ ucfirst($user->role) }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="note" class="form-label small fw-semibold">Internal Note / Progress Update</label>
                            <textarea name="note" id="note" rows="3" class="form-control" placeholder="e.g. Sent 10-day northern circuit quote via email at $3,450/person. Client reviewing with family..."></textarea>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-uwt-primary">
                                <i class="bi bi-check2-circle me-1"></i> Save Status & Note
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Activity & Status History Timeline -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white">
                    <h5 class="h6 mb-0 fw-bold"><i class="bi bi-clock-history text-uwt-primary me-2"></i> Status History Timeline</h5>
                </div>
                <div class="card-body p-3">
                    <div class="timeline ps-2">
                        @forelse($history as $item)
                            <div class="mb-3 border-start border-2 border-warning ps-3 position-relative">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <span class="badge-status status-{{ $item->to_status }}" style="font-size: 0.65rem;">
                                        {{ ucfirst($item->to_status) }}
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
@endsection
