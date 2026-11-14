@extends('layouts.admin')

@section('page_title', 'Safari & Mountain Guides')

@section('admin_content')
<div class="container-fluid p-0">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="h4 brand-font text-uwt-dark mb-1">Safari & Mountain Guides</h2>
            <p class="text-muted small mb-0">Certified TANAPA naturalists and KINAPA mountain guides for Kilimanjaro and safari circuits.</p>
        </div>
        <a href="{{ route('admin.guides.create') }}" class="btn btn-uwt-primary">
            <i class="bi bi-plus-lg me-1"></i> Add Guide
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
                            <th>Guide Name</th>
                            <th>Phone / WhatsApp</th>
                            <th>Languages</th>
                            <th>TANAPA / KINAPA Permit</th>
                            <th>Status</th>
                            <th>Total Assignments</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($guides as $g)
                            <tr>
                                <td class="fw-bold">{{ $g->name }}</td>
                                <td>
                                    <a href="tel:{{ $g->phone }}" class="text-decoration-none text-dark">
                                        <i class="bi bi-telephone-fill text-muted me-1 small"></i>{{ $g->phone }}
                                    </a>
                                </td>
                                <td>{{ $g->languages ?? '—' }}</td>
                                <td><span class="badge bg-light text-dark border">{{ $g->permit_number ?? '—' }}</span></td>
                                <td>
                                    @if($g->active)
                                        <span class="badge bg-success-subtle text-success small">Active</span>
                                    @else
                                        <span class="badge bg-secondary small">Inactive</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark border">{{ $g->assignments_count }} trips</span>
                                </td>
                                <td class="text-end">
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('admin.guides.edit', $g->id) }}" class="btn btn-outline-secondary" title="Edit Guide">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        @if($g->assignments_count == 0)
                                            <form action="{{ route('admin.guides.destroy', $g->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this guide?');" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-outline-danger" title="Delete Guide">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4 text-muted">
                                    No safari or mountain guides registered yet.
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
