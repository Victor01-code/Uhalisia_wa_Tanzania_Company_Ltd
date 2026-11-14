@extends('layouts.admin')

@section('page_title', 'Log Manual Lead')

@section('admin_content')
<div class="container-fluid p-0">
    <div class="d-flex align-items-center gap-2 mb-4">
        <a href="{{ route('admin.leads.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left"></i> Back to Leads
        </a>
        <h2 class="h4 brand-font text-uwt-dark mb-0">Log Manual Safari Inquiry / Phone Call</h2>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-sm border-0">
                <div class="card-body p-4">
                    <form action="{{ route('admin.leads.store') }}" method="POST">
                        @csrf

                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label for="name" class="form-label fw-semibold">Client Name <span class="text-danger">*</span></label>
                                <input type="text" name="name" id="name" class="form-control" value="{{ old('name') }}" placeholder="e.g. Maria Gonzalez" required>
                            </div>

                            <div class="col-md-6">
                                <label for="email" class="form-label fw-semibold">Email Address <span class="text-danger">*</span></label>
                                <input type="email" name="email" id="email" class="form-control" value="{{ old('email') }}" placeholder="client@example.com" required>
                            </div>

                            <div class="col-md-6">
                                <label for="country" class="form-label fw-semibold">Country</label>
                                <select name="country" id="country" class="form-select">
                                    <option value="">-- Select Country --</option>
                                    <optgroup label="Popular Safari Markets">
                                        @foreach(config('countries.popular', []) as $c)
                                            <option value="{{ $c['name'] }}" {{ old('country') == $c['name'] ? 'selected' : '' }}>
                                                {{ $c['name'] }} ({{ $c['dial_code'] }})
                                            </option>
                                        @endforeach
                                    </optgroup>
                                    <optgroup label="All Countries">
                                        @foreach(config('countries.all', []) as $c)
                                            <option value="{{ $c['name'] }}" {{ old('country') == $c['name'] ? 'selected' : '' }}>
                                                {{ $c['name'] }} ({{ $c['dial_code'] }})
                                            </option>
                                        @endforeach
                                    </optgroup>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label for="safari_interest" class="form-label fw-semibold">Tour / Safari Preference</label>
                                <input type="text" name="safari_interest" id="safari_interest" class="form-control" value="{{ old('safari_interest') }}" placeholder="e.g. 10 Days Safari or Kilimanjaro">
                            </div>

                            <div class="col-md-6">
                                <label for="assigned_user_id" class="form-label fw-semibold">Assign to Agent</label>
                                <select name="assigned_user_id" id="assigned_user_id" class="form-select">
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}" {{ old('assigned_user_id', Auth::id()) == $user->id ? 'selected' : '' }}>
                                            {{ $user->name }} ({{ ucfirst($user->role) }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label for="utm_source" class="form-label fw-semibold">Source / Referral Channel</label>
                                <input type="text" name="utm_source" id="utm_source" class="form-control" value="{{ old('utm_source', 'phone_call') }}" placeholder="e.g. phone_call, walk_in, safari_booking_fair">
                            </div>

                            <div class="col-12">
                                <label for="message" class="form-label fw-semibold">Notes / Client Requirements</label>
                                <textarea name="message" id="message" rows="4" class="form-control" placeholder="Details discussed with the client...">{{ old('message') }}</textarea>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2 pt-2 border-top">
                            <a href="{{ route('admin.leads.index') }}" class="btn btn-outline-secondary">Cancel</a>
                            <button type="submit" class="btn btn-uwt-primary">
                                <i class="bi bi-save me-1"></i> Save Lead
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
