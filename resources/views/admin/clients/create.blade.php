@extends('layouts.admin')

@section('page_title', 'Add New Client')

@section('admin_content')
<div class="container-fluid p-0">
    <div class="d-flex align-items-center gap-2 mb-4">
        <a href="{{ route('admin.clients.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left"></i> Back to Clients
        </a>
        <h2 class="h4 brand-font text-uwt-dark mb-0">Create Client Record</h2>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-sm border-0">
                <div class="card-body p-4">
                    <form action="{{ route('admin.clients.store') }}" method="POST">
                        @csrf

                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label for="full_name" class="form-label fw-semibold">Full Name <span class="text-danger">*</span></label>
                                <input type="text" name="full_name" id="full_name" class="form-control" value="{{ old('full_name') }}" placeholder="e.g. Sarah Jenkins" required>
                            </div>

                            <div class="col-md-6">
                                <label for="email" class="form-label fw-semibold">Email Address <span class="text-danger">*</span></label>
                                <input type="email" name="email" id="email" class="form-control" value="{{ old('email') }}" placeholder="sarah@example.com" required>
                            </div>

                            <div class="col-md-6">
                                <label for="country" class="form-label fw-semibold">Country of Residence</label>
                                <select name="country" id="country" class="form-select">
                                    <option value="">-- Select Country --</option>
                                    <optgroup label="Popular Safari Markets">
                                        @foreach(config('countries.popular', []) as $c)
                                            <option value="{{ $c['name'] }}" data-dial="{{ $c['dial_code'] }}" {{ old('country') == $c['name'] ? 'selected' : '' }}>
                                                {{ $c['name'] }} ({{ $c['dial_code'] }})
                                            </option>
                                        @endforeach
                                    </optgroup>
                                    <optgroup label="All Countries">
                                        @foreach(config('countries.all', []) as $c)
                                            <option value="{{ $c['name'] }}" data-dial="{{ $c['dial_code'] }}" {{ old('country') == $c['name'] ? 'selected' : '' }}>
                                                {{ $c['name'] }} ({{ $c['dial_code'] }})
                                            </option>
                                        @endforeach
                                    </optgroup>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label for="phone" class="form-label fw-semibold">Phone / WhatsApp</label>
                                <input type="text" name="phone" id="phone" class="form-control" value="{{ old('phone') }}" placeholder="e.g. +44 7700 900123">
                            </div>

                            <div class="col-12">
                                <label for="notes" class="form-label fw-semibold">Client Notes</label>
                                <textarea name="notes" id="notes" rows="3" class="form-control" placeholder="Special preferences, safari history, dietary notes...">{{ old('notes') }}</textarea>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2 pt-2 border-top">
                            <a href="{{ route('admin.clients.index') }}" class="btn btn-outline-secondary">Cancel</a>
                            <button type="submit" class="btn btn-uwt-primary">
                                <i class="bi bi-save me-1"></i> Save Client
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const country = document.getElementById('country');
    const phone = document.getElementById('phone');
    if (country && phone) {
        country.addEventListener('change', function () {
            const opt = country.options[country.selectedIndex];
            if (opt && opt.dataset.dial) {
                const dial = opt.dataset.dial;
                if (!phone.value || phone.value.trim() === '' || phone.value.startsWith('+')) {
                    const currentParts = phone.value.trim().split(' ');
                    if (currentParts.length > 1 && currentParts[0].startsWith('+')) {
                        currentParts[0] = dial;
                        phone.value = currentParts.join(' ');
                    } else if (phone.value.startsWith('+')) {
                        phone.value = dial + ' ';
                    } else if (!phone.value) {
                        phone.value = dial + ' ';
                    }
                }
            }
        });
    }
});
</script>
@endpush

