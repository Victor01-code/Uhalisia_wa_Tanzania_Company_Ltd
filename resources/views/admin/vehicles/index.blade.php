@extends('layouts.admin')

@section('page_title', 'Safari Fleet')

@section('admin_content')
<div class="container-fluid p-0">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="h4 brand-font text-uwt-dark mb-1">Safari Fleet (4x4 Cruisers)</h2>
            <p class="text-muted small mb-0">Tour cruisers, extended Land Cruisers, and safari minivans available for trip assignments.</p>
        </div>
        <a href="{{ route('admin.vehicles.create') }}" class="btn btn-uwt-primary">
            <i class="bi bi-plus-lg me-1"></i> Add Vehicle
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show small" role="alert">
            <i class="bi bi-check-circle-fill me-1"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show small" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-1"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Vehicle Name</th>
                            <th>Plate Number</th>
                            <th>Type</th>
                            <th>Passenger Seats</th>
                            <th>Status</th>
                            <th>Total Assignments</th>
                            <th>Notes</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($vehicles as $v)
                            <tr>
                                <td class="fw-bold">{{ $v->name }}</td>
                                <td><span class="badge bg-light text-dark border font-monospace">{{ $v->plate_number }}</span></td>
                                <td>{{ $v->type }}</td>
                                <td><i class="bi bi-person-fill text-muted me-1"></i>{{ $v->seats }} Window Seats</td>
                                <td>
                                    @if($v->active)
                                        <span class="badge bg-success-subtle text-success small">Active</span>
                                    @else
                                        <span class="badge bg-warning-subtle text-warning small">Maintenance</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark border">{{ $v->assignments_count }} trips</span>
                                </td>
                                <td class="small text-muted">{{ Str::limit($v->notes, 40) ?? '—' }}</td>
                                <td class="text-end">
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('admin.vehicles.edit', $v->id) }}" class="btn btn-outline-secondary" title="Edit Vehicle">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        @if($v->assignments_count == 0)
                                            <form action="{{ route('admin.vehicles.destroy', $v->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this vehicle?');" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-outline-danger" title="Delete Vehicle">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-4 text-muted">
                                    No safari vehicles in fleet yet.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
