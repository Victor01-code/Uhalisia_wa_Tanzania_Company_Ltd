@extends('layouts.admin')

@section('page_title', 'Create Staff Account')

@section('admin_content')
<div class="container-fluid p-0">
    <div class="d-flex align-items-center gap-2 mb-4">
        <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left"></i> Back to Staff List
        </a>
        <h2 class="h4 brand-font text-uwt-dark mb-0">Create Staff User Account</h2>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-7">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body p-4">
                    <form action="{{ route('admin.users.store') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label for="name" class="form-label fw-semibold">Staff Full Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" id="name" class="form-control" value="{{ old('name') }}" placeholder="e.g. Grace Lyimo" required>
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label fw-semibold">Staff Email Address <span class="text-danger">*</span></label>
                            <input type="email" name="email" id="email" class="form-control" value="{{ old('email') }}" placeholder="staff@uhalisiawatanzania.com" required>
                        </div>

                        <div class="mb-3">
                            <label for="role" class="form-label fw-semibold">Portal Role <span class="text-danger">*</span></label>
                            <select name="role" id="role" class="form-select" required>
                                <option value="agent" {{ old('role') === 'agent' ? 'selected' : '' }}>Agent (Leads, Bookings, Itineraries, Operations)</option>
                                <option value="accountant" {{ old('role') === 'accountant' ? 'selected' : '' }}>Accountant (Bookings, Payments, Expenses, Reports)</option>
                                <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>Admin (Full System Privileges)</option>
                            </select>
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label for="password" class="form-label fw-semibold">Password <span class="text-danger">*</span></label>
                                <input type="password" name="password" id="password" class="form-control" minlength="8" required>
                                <div class="form-text">Minimum 8 characters.</div>
                            </div>

                            <div class="col-md-6">
                                <label for="password_confirmation" class="form-label fw-semibold">Confirm Password <span class="text-danger">*</span></label>
                                <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" minlength="8" required>
                            </div>
                        </div>

                        <div class="mb-4">
                            <div class="form-check form-switch">
                                <input type="checkbox" name="active" id="active" class="form-check-input" value="1" {{ old('active', true) ? 'checked' : '' }}>
                                <label for="active" class="form-check-label fw-semibold">Account Active (Can log in)</label>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2 pt-3 border-top">
                            <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">Cancel</a>
                            <button type="submit" class="btn btn-uwt-primary">
                                <i class="bi bi-person-check me-1"></i> Create Staff Account
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
