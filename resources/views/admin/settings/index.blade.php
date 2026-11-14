@extends('layouts.admin')

@section('page_title', 'Portal Settings')

@section('admin_content')
<div class="container-fluid p-0">
    <div class="d-flex align-items-center gap-2 mb-4">
        <h2 class="h4 brand-font text-uwt-dark mb-0">System & Company Settings</h2>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body p-4">
                    <form action="{{ route('admin.settings.update') }}" method="POST">
                        @csrf
                        @method('PUT')

                        <h5 class="brand-font text-uwt-primary border-bottom pb-2 mb-3">Company Details</h5>

                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Company Name</label>
                                <input type="text" name="company_name" class="form-control" value="{{ $settings['company_name'] ?? 'Uhalisia wa Tanzania Company Ltd' }}" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Tagline</label>
                                <input type="text" name="company_tagline" class="form-control" value="{{ $settings['company_tagline'] ?? 'Safaris and Tours' }}" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Official Email</label>
                                <input type="email" name="company_email" class="form-control" value="{{ $settings['company_email'] ?? 'info@uhalisiawatanzania.com' }}" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Phone / WhatsApp</label>
                                <input type="text" name="company_phone" class="form-control" value="{{ $settings['company_phone'] ?? '+255 784 590 722' }}" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Website URL</label>
                                <input type="text" name="company_website" class="form-control" value="{{ $settings['company_website'] ?? 'www.uhalisiawatanzania.com' }}" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Office Locations</label>
                                <input type="text" name="company_offices" class="form-control" value="{{ $settings['company_offices'] ?? 'Arusha & Dar es Salaam, Tanzania' }}" required>
                            </div>
                        </div>

                        <h5 class="brand-font text-uwt-primary border-bottom pb-2 mb-3">Quote & Itinerary Defaults</h5>

                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Default Deposit (%)</label>
                                <div class="input-group">
                                    <input type="number" name="default_deposit_percent" class="form-control" value="{{ $settings['default_deposit_percent'] ?? '30' }}" min="10" max="100">
                                    <span class="input-group-text">%</span>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Quote Validity (Days)</label>
                                <div class="input-group">
                                    <input type="number" name="quote_validity_days" class="form-control" value="{{ $settings['quote_validity_days'] ?? '14' }}" min="1" max="90">
                                    <span class="input-group-text">Days</span>
                                </div>
                            </div>

                            <div class="col-12">
                                <label class="form-label fw-semibold">Standard Booking Terms (Inclusions/Exclusions)</label>
                                <textarea name="quote_terms" rows="4" class="form-control">{{ $settings['quote_terms'] ?? '' }}</textarea>
                            </div>

                            <div class="col-12">
                                <label class="form-label fw-semibold">Standard Email Signature</label>
                                <textarea name="email_signature" rows="4" class="form-control">{{ $settings['email_signature'] ?? '' }}</textarea>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end pt-3 border-top">
                            <button type="submit" class="btn btn-uwt-primary">
                                <i class="bi bi-save me-1"></i> Save Settings
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
