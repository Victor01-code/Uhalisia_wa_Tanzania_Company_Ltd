@extends('layouts.admin')

@section('page_title', 'Edit Staff: ' . $user->name)

@section('admin_content')
<div class="container-fluid p-0">
    <div class="d-flex align-items-center gap-2 mb-4">
        <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left"></i> Back to Staff List
        </a>
        <h2 class="h4 brand-font text-uwt-dark mb-0">Edit Staff Account: {{ $user->name }}</h2>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-7">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body p-4">
                    <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="name" class="form-label fw-semibold">Staff Full Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $user->name) }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label fw-semibold">Staff Email Address <span class="text-danger">*</span></label>
                            <input type="email" name="email" id="email" class="form-control" value="{{ old('email', $user->email) }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="role" class="form-label fw-semibold">Portal Role <span class="text-danger">*</span></label>
                            <select name="role" id="role" class="form-select" required>
                                <option value="agent" {{ old('role', $user->role) === 'agent' ? 'selected' : '' }}>Agent (Leads, Bookings, Itineraries, Operations)</option>
                                <option value="accountant" {{ old('role', $user->role) === 'accountant' ? 'selected' : '' }}>Accountant (Bookings, Payments, Expenses, Reports)</option>
                                <option value="admin" {{ old('role', $user->role) === 'admin' ? 'selected' : '' }}>Admin (Full System Privileges)</option>
                            </select>
                        </div>

                        <div class="p-3 bg-light rounded border mb-3">
                            <h6 class="fw-bold mb-2 text-uwt-primary"><i class="bi bi-key me-1"></i> Reset Password (Optional)</h6>
                            <p class="small text-muted mb-3">Leave both password fields blank to keep the current password unchanged.</p>

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="password" class="form-label small fw-semibold">New Password</label>
                                    <input type="password" name="password" id="password" class="form-control" minlength="8" placeholder="New password">
                                </div>

                                <div class="col-md-6">
                                    <label for="password_confirmation" class="form-label small fw-semibold">Confirm New Password</label>
                                    <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" minlength="8" placeholder="Confirm password">
                                </div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <div class="form-check form-switch">
                                <input type="checkbox" name="active" id="active" class="form-check-input" value="1" {{ old('active', $user->active) ? 'checked' : '' }}>
                                <label for="active" class="form-check-label fw-semibold">Account Active (Can log in)</label>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between align-items-center pt-3 border-top">
                            @if($user->id !== Auth::id())
                                <button type="button" class="btn btn-outline-danger" onclick="if(confirm('Delete staff account for {{ $user->name }}?')) document.getElementById('deleteUserForm').submit();">
                                    <i class="bi bi-trash me-1"></i> Delete User
                                </button>
                            @else
                                <span class="text-muted small">Cannot delete yourself</span>
                            @endif

                            <div class="d-flex gap-2">
                                <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">Cancel</a>
                                <button type="submit" class="btn btn-uwt-primary">
                                    <i class="bi bi-save me-1"></i> Update Staff Account
                                </button>
                            </div>
                        </div>
                    </form>

                    @if($user->id !== Auth::id())
                        <form id="deleteUserForm" action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="d-none">
                            @csrf
                            @method('DELETE')
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
