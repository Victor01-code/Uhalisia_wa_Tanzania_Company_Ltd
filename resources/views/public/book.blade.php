@extends('layouts.public')

@section('title', 'Book Your Tanzania Safari & Tours - Uhalisia wa Tanzania')

@section('public_content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-9 col-xl-8">

            <!-- Banner Card -->
            <div class="card mb-4 overflow-hidden border-0 shadow">
                <div class="p-4 p-md-5 text-white" style="background: linear-gradient(135deg, #2B1B12 0%, #C9642C 100%);">
                    <span class="badge bg-warning text-dark text-uppercase fw-bold mb-2">Bespoke Safari Planning</span>
                    <h1 class="display-6 fw-bold mb-2 text-white brand-font">Plan Your Tanzanian Safari Adventure</h1>
                    <p class="lead mb-0 text-white-50" style="font-size: 1.05rem;">
                        Let our local Tanzania travel experts craft a personalized safari itinerary tailored to your exact dates, travel style, and budget. All quotes in USD with zero obligation.
                    </p>
                </div>
            </div>

            <!-- Booking Form Card -->
            <div class="card shadow-sm border-0">
                <div class="card-body p-4 p-md-5">

                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <h6 class="alert-heading fw-bold mb-1"><i class="bi bi-exclamation-triangle-fill me-1"></i> Please check the required fields:</h6>
                            <ul class="mb-0 small ps-3">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <form action="{{ route('book.submit') }}" method="POST" id="safariBookingForm">
                        @csrf

                        <!-- Honeypot field to trap spam bots -->
                        <div class="form-hp" aria-hidden="true">
                            <label for="website_url">Do not fill this field</label>
                            <input type="text" name="website_url" id="website_url" tabindex="-1" autocomplete="off">
                        </div>

                        <!-- Hidden UTM parameters -->
                        <input type="hidden" name="utm_source" value="{{ old('utm_source', $utmSource) }}">
                        <input type="hidden" name="utm_medium" value="{{ old('utm_medium', $utmMedium) }}">

                        <!-- Section 1: Trip Selection -->
                        <div class="mb-4">
                            <h5 class="brand-font text-uwt-primary border-bottom pb-2 mb-3">
                                <i class="bi bi-compass me-2"></i> 1. Safari or Tour Preference
                            </h5>

                            <div class="row g-3">
                                <div class="col-md-7">
                                    <label for="tour_slug" class="form-label fw-semibold">Choose a Tour Package</label>
                                    <select name="tour_slug" id="tour_slug" class="form-select @error('tour_slug') is-invalid @enderror">
                                        <option value="">-- Custom Safari / Not Sure Yet --</option>
                                        @foreach($tours as $t)
                                            <option value="{{ $t->slug }}" {{ (old('tour_slug', $selectedTourSlug) === $t->slug) ? 'selected' : '' }}>
                                                {{ $t->name }} ({{ $t->days }} Days &bull; From ${{ number_format($t->base_price_usd, 0) }}/person)
                                            </option>
                                        @endforeach
                                    </select>
                                    <div class="form-text">Choose one of our popular itineraries or leave blank for a custom trip.</div>
                                </div>

                                <div class="col-md-5">
                                    <label for="safari_interest" class="form-label fw-semibold">Specific Interest / Focus</label>
                                    <input type="text" name="safari_interest" id="safari_interest" class="form-control @error('safari_interest') is-invalid @enderror"
                                           value="{{ old('safari_interest', $prefilledInterest) }}" placeholder="e.g. Big Five, Migration, Luxury, Climb">
                                </div>
                            </div>
                        </div>

                        <!-- Section 2: Dates & Party Size -->
                        <div class="mb-4">
                            <h5 class="brand-font text-uwt-primary border-bottom pb-2 mb-3">
                                <i class="bi bi-calendar-event me-2"></i> 2. Travel Dates & Group Size
                            </h5>

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="start_date" class="form-label fw-semibold">Approximate Start Date</label>
                                    <input type="date" name="start_date" id="start_date" class="form-control @error('start_date') is-invalid @enderror"
                                           value="{{ old('start_date') }}" min="{{ date('Y-m-d', strtotime('+1 day')) }}">
                                    <div class="form-text">Dates can be adjusted during trip planning.</div>
                                </div>

                                <div class="col-md-3 col-6">
                                    <label for="adults" class="form-label fw-semibold">Adults (12+ yrs) <span class="text-danger">*</span></label>
                                    <input type="number" name="adults" id="adults" class="form-control @error('adults') is-invalid @enderror"
                                           value="{{ old('adults', 2) }}" min="1" max="50" required>
                                </div>

                                <div class="col-md-3 col-6">
                                    <label for="children" class="form-label fw-semibold">Children (&lt; 12 yrs)</label>
                                    <input type="number" name="children" id="children" class="form-control @error('children') is-invalid @enderror"
                                           value="{{ old('children', 0) }}" min="0" max="50">
                                </div>
                            </div>
                        </div>

                        <!-- Section 3: Contact Details -->
                        <div class="mb-4">
                            <h5 class="brand-font text-uwt-primary border-bottom pb-2 mb-3">
                                <i class="bi bi-person me-2"></i> 3. Your Contact Information
                            </h5>

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="name" class="form-label fw-semibold">
                                        <i class="bi bi-person-fill text-uwt-primary me-1"></i> Full Name <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror"
                                           value="{{ old('name') }}" placeholder="e.g. John Smith" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="email" class="form-label fw-semibold">
                                        <i class="bi bi-envelope-fill text-uwt-primary me-1"></i> Email Address <span class="text-danger">*</span>
                                    </label>
                                    <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror"
                                           value="{{ old('email') }}" placeholder="e.g. john@example.com" required>
                                    <div class="form-text">We will email your quote and itinerary to this address.</div>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Country of Residence selection -->
                                <div class="col-md-6">
                                    <label for="country" class="form-label fw-semibold">
                                        <i class="bi bi-geo-alt-fill text-uwt-primary me-1"></i> Country of Residence <span class="text-danger">*</span>
                                    </label>
                                    <select name="country" id="country" class="form-select @error('country') is-invalid @enderror" required>
                                        <option value="" disabled {{ old('country') ? '' : 'selected' }}>-- Select Your Country --</option>
                                        <optgroup label="Popular Safari Markets">
                                            @foreach(config('countries.popular', []) as $c)
                                                <option value="{{ $c['name'] }}" data-dial="{{ $c['dial_code'] }}" {{ old('country') == $c['name'] ? 'selected' : '' }}>
                                                    {{ $c['name'] }} ({{ $c['dial_code'] }})
                                                </option>
                                            @endforeach
                                        </optgroup>
                                        <optgroup label="All Countries (A - Z)">
                                            @foreach(config('countries.all', []) as $c)
                                                <option value="{{ $c['name'] }}" data-dial="{{ $c['dial_code'] }}" {{ old('country') == $c['name'] ? 'selected' : '' }}>
                                                    {{ $c['name'] }} ({{ $c['dial_code'] }})
                                                </option>
                                            @endforeach
                                        </optgroup>
                                    </select>
                                    <div class="form-text">Selecting your country automatically sets your phone / WhatsApp dial code.</div>
                                    @error('country')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Phone / WhatsApp Number with country code selector -->
                                <div class="col-md-6">
                                    <label for="phone_number" class="form-label fw-semibold">
                                        <i class="bi bi-whatsapp text-success me-1"></i> Phone / WhatsApp Number
                                    </label>
                                    <div class="input-group">
                                        <select class="form-select" id="phone_country_code" name="phone_country_code" style="flex: 0 0 135px; width: 135px; min-width: 135px; font-weight: 600;" aria-label="Calling Code">
                                            <optgroup label="Popular Codes">
                                                @foreach(config('countries.popular', []) as $c)
                                                    <option value="{{ $c['dial_code'] }}" data-country="{{ $c['name'] }}">
                                                        {{ $c['dial_code'] }} ({{ $c['code'] }})
                                                    </option>
                                                @endforeach
                                            </optgroup>
                                            <optgroup label="All Codes">
                                                @foreach(config('countries.all', []) as $c)
                                                    <option value="{{ $c['dial_code'] }}" data-country="{{ $c['name'] }}">
                                                        {{ $c['dial_code'] }} ({{ $c['code'] }})
                                                    </option>
                                                @endforeach
                                            </optgroup>
                                        </select>
                                        <input type="tel" id="phone_number" name="phone_number" class="form-control @error('phone') is-invalid @enderror"
                                               placeholder="e.g. 784 590 722" value="">
                                    </div>
                                    <!-- Full phone number hidden input for form submission -->
                                    <input type="hidden" name="phone" id="phone" value="{{ old('phone') }}">
                                    <div class="form-text d-flex justify-content-between align-items-center mt-1">
                                        <span>For instant safari updates & driver coordination.</span>
                                        <span class="badge bg-light text-dark border" id="phone_preview_badge" style="display: none;">
                                            <i class="bi bi-telephone-fill text-success me-1"></i><span id="phone_preview"></span>
                                        </span>
                                    </div>
                                    @error('phone')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Section 4: Special Wishes -->
                        <div class="mb-4">
                            <h5 class="brand-font text-uwt-primary border-bottom pb-2 mb-3">
                                <i class="bi bi-chat-left-text me-2"></i> 4. Safari Wishes & Notes
                            </h5>

                            <div class="mb-3">
                                <label for="message" class="form-label fw-semibold">Tell us more about your ideal Tanzania trip</label>
                                <textarea name="message" id="message" rows="4" class="form-control @error('message') is-invalid @enderror"
                                          placeholder="Specific parks you want to visit (Serengeti, Ngorongoro, Tarangire), accommodation preference (luxury lodges vs. mid-range tented camps), dietary needs, or special occasions (honeymoon, birthday, anniversary)...">{{ old('message') }}</textarea>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="d-grid gap-2 pt-2">
                            <button type="submit" class="btn btn-uwt-primary btn-lg py-3 shadow-sm">
                                <i class="bi bi-send-fill me-2"></i> Request Free Safari Quote & Itinerary
                            </button>
                            <small class="text-center text-muted mt-2">
                                <i class="bi bi-shield-check text-success me-1"></i> No spam. Your information is strictly used to plan your safari and is never shared.
                            </small>
                        </div>
                    </form>

                </div>
            </div>

            <!-- Trust Highlights -->
            <div class="row g-3 mt-4 text-center">
                <div class="col-md-4">
                    <div class="p-3 bg-white rounded shadow-sm">
                        <i class="bi bi-award-fill text-uwt-primary fs-3 d-block mb-1"></i>
                        <h6 class="fw-bold mb-1">100% Tailor-Made</h6>
                        <small class="text-muted">Bespoke itineraries crafted by Tanzanian safari specialists.</small>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="p-3 bg-white rounded shadow-sm">
                        <i class="bi bi-currency-dollar text-success fs-3 d-block mb-1"></i>
                        <h6 class="fw-bold mb-1">Transparent Pricing</h6>
                        <small class="text-muted">Clear USD pricing with zero hidden park or vehicle fees.</small>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="p-3 bg-white rounded shadow-sm">
                        <i class="bi bi-shield-lock-fill text-uwt-gold fs-3 d-block mb-1"></i>
                        <h6 class="fw-bold mb-1">Private 4x4 Cruisers</h6>
                        <small class="text-muted">Dedicated expert driver/guide and guaranteed window seats.</small>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const countrySelect = document.getElementById('country');
    const codeSelect = document.getElementById('phone_country_code');
    const phoneInput = document.getElementById('phone_number');
    const fullPhoneInput = document.getElementById('phone');
    const phonePreviewBadge = document.getElementById('phone_preview_badge');
    const phonePreview = document.getElementById('phone_preview');

    function updateFullPhone() {
        if (!codeSelect || !phoneInput || !fullPhoneInput) return;
        const code = codeSelect.value ? codeSelect.value.trim() : '';
        const number = phoneInput.value ? phoneInput.value.trim() : '';

        if (number.length > 0) {
            const combined = code ? (code + ' ' + number) : number;
            fullPhoneInput.value = combined;
            if (phonePreview && phonePreviewBadge) {
                phonePreview.textContent = combined;
                phonePreviewBadge.style.display = 'inline-block';
            }
        } else {
            fullPhoneInput.value = '';
            if (phonePreviewBadge) {
                phonePreviewBadge.style.display = 'none';
            }
        }
    }

    // When country changes, update calling code on phone field
    function syncCountryToPhone() {
        if (!countrySelect || !codeSelect) return;
        const selectedOption = countrySelect.options[countrySelect.selectedIndex];
        if (selectedOption && selectedOption.dataset.dial) {
            const dial = selectedOption.dataset.dial;
            for (let i = 0; i < codeSelect.options.length; i++) {
                if (codeSelect.options[i].value === dial) {
                    codeSelect.selectedIndex = i;
                    break;
                }
            }
            updateFullPhone();
        }
    }

    if (countrySelect && codeSelect) {
        countrySelect.addEventListener('change', syncCountryToPhone);
        countrySelect.addEventListener('input', syncCountryToPhone);
    }

    // When calling code is adjusted manually
    if (codeSelect) {
        codeSelect.addEventListener('change', function () {
            updateFullPhone();
        });
    }

    // When phone number is typed or pasted
    if (phoneInput) {
        phoneInput.addEventListener('input', function () {
            let val = phoneInput.value.trim();
            // If user typed/pasted a leading +
            if (val.startsWith('+')) {
                for (let i = 0; i < codeSelect.options.length; i++) {
                    const dial = codeSelect.options[i].value;
                    if (val.startsWith(dial)) {
                        codeSelect.selectedIndex = i;
                        phoneInput.value = val.substring(dial.length).trim();
                        // Also sync country if not set
                        const countryName = codeSelect.options[i].dataset.country;
                        if (countryName && countrySelect) {
                            for (let j = 0; j < countrySelect.options.length; j++) {
                                if (countrySelect.options[j].value === countryName) {
                                    countrySelect.selectedIndex = j;
                                    break;
                                }
                            }
                        }
                        break;
                    }
                }
            }
            updateFullPhone();
        });
    }

    // Initial state setup on page load
    const initialFull = fullPhoneInput ? fullPhoneInput.value.trim() : '';
    if (initialFull) {
        let matched = false;
        if (codeSelect) {
            for (let i = 0; i < codeSelect.options.length; i++) {
                const dial = codeSelect.options[i].value;
                if (initialFull.startsWith(dial)) {
                    codeSelect.selectedIndex = i;
                    if (phoneInput) {
                        phoneInput.value = initialFull.substring(dial.length).trim();
                    }
                    matched = true;
                    break;
                }
            }
        }
        if (!matched && phoneInput) {
            phoneInput.value = initialFull;
        }
    } else if (countrySelect && countrySelect.value) {
        // Trigger sync from selected country
        countrySelect.dispatchEvent(new Event('change'));
    }

    updateFullPhone();
});
</script>
@endpush

