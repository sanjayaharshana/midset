@extends('layouts.admin')

@section('title', 'Promoters')
@section('page-title', 'Promoter Details')

@section('breadcrumbs')
    <span class="breadcrumb-separator">›</span>
    <span class="breadcrumb-item active">Promoters</span>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <h3>All Promoters</h3>
            @can('create promoters')
                <a href="{{ route('admin.promoters.create') }}" class="btn btn-success">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 8px;">
                        <line x1="12" y1="5" x2="12" y2="19"></line>
                        <line x1="5" y1="12" x2="19" y2="12"></line>
                    </svg>
                    Add New Promoter
                </a>
            @endcan
        </div>
    </div>
    <div class="card-body">
        @if($promoters->count() > 0)
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Promoter ID</th>
                            <th>Name</th>
                            <th>Position</th>
                            <th>ID Card No.</th>
                            <th>Phone</th>
                            <th>Bank Details</th>
                            <th>Status</th>
                            <th>Created</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($promoters as $promoter)
                        <tr>
                            <td>
                                <span style="background: #1f2937; color: white; padding: 0.25rem 0.5rem; border-radius: 0.25rem; font-weight: bold; font-size: 0.8rem; font-family: monospace;">
                                    {{ $promoter->promoter_id }}
                                </span>
                            </td>
                            <td>
                                <div style="font-weight: 600;">{{ $promoter->promoter_name }}</div>
                            </td>
                            <td>
                                @if($promoter->position)
                                    <span style="background: #e0f2fe; color: #0277bd; padding: 0.25rem 0.5rem; border-radius: 0.25rem; font-size: 0.8rem; font-weight: 500;">
                                        {{ $promoter->position->position_name }}
                                    </span>
                                @else
                                    <span style="color: #9ca3af; font-style: italic;">No position</span>
                                @endif
                            </td>
                            <td>
                                <span style="font-family: monospace; font-size: 0.9rem;">{{ $promoter->identity_card_no }}</span>
                            </td>
                            <td>{{ $promoter->phone_no }}</td>
                            <td>
                                <div style="font-size: 0.85rem;">
                                    <div style="font-weight: 500;">{{ $promoter->bank_name }}</div>
                                    <div style="color: #6b7280;">{{ $promoter->bank_branch_name }}</div>
                                    <div style="color: #6b7280; font-family: monospace;">****{{ substr($promoter->bank_account_number, -4) }}</div>
                                </div>
                            </td>
                            <td>
                                <span class="status-badge status-{{ $promoter->status }}">
                                    {{ ucfirst($promoter->status) }}
                                </span>
                            </td>
                            <td>{{ $promoter->created_at->format('M d, Y') }}</td>
                            <td>
                                <div style="display: flex; gap: 8px;">
                                    @can('view promoters')
                                        <a href="{{ route('admin.promoters.show', $promoter) }}" class="btn btn-sm btn-info">
                                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                                <circle cx="12" cy="12" r="3"></circle>
                                            </svg>
                                        </a>
                                    @endcan
                                    @can('edit promoters')
                                        <a href="{{ route('admin.promoters.edit', $promoter) }}" class="btn btn-sm btn-warning">
                                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                                <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                            </svg>
                                        </a>
                                    @endcan
                                    @can('delete promoters')
                                        <form action="{{ route('admin.promoters.destroy', $promoter) }}" method="POST" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete promoter {{ $promoter->promoter_id }}?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">
                                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                    <polyline points="3,6 5,6 21,6"></polyline>
                                                    <path d="M19,6v14a2,2 0 0,1 -2,2H7a2,2 0 0,1 -2,-2V6m3,0V4a2,2 0 0,1 2,-2h4a2,2 0 0,1 2,2v2"></path>
                                                </svg>
                                            </button>
                                        </form>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <div style="margin-top: 1rem;">
                {{ $promoters->links() }}
            </div>
        @else
            <div style="text-align: center; padding: 2rem;">
                <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="#d1d5db" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" style="margin-bottom: 1rem;">
                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                    <circle cx="12" cy="7" r="4"></circle>
                </svg>
                <h3 style="color: #6b7280; margin-bottom: 0.5rem;">No promoters found</h3>
                <p style="color: #9ca3af;">Get started by adding your first promoter.</p>
                @can('create promoters')
                    <a href="{{ route('admin.promoters.create') }}" class="btn btn-success" style="margin-top: 1rem;">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 8px;">
                            <line x1="12" y1="5" x2="12" y2="19"></line>
                            <line x1="5" y1="12" x2="19" y2="12"></line>
                        </svg>
                        Add First Promoter
                    </a>
                @endcan
            </div>
        @endif
    </div>
</div>

<style>
.status-badge {
    padding: 0.25rem 0.75rem;
    border-radius: 9999px;
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.status-active {
    background-color: #d1fae5;
    color: #065f46;
}

.status-inactive {
    background-color: #fef3c7;
    color: #92400e;
}

.status-suspended {
    background-color: #fee2e2;
    color: #991b1b;
}

.table-responsive {
    overflow-x: auto;
}

.table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 1rem;
}

.table th,
.table td {
    padding: 0.75rem;
    text-align: left;
    border-bottom: 1px solid #e5e7eb;
}

.table th {
    background-color: #f9fafb;
    font-weight: 600;
    color: #374151;
}

.table tbody tr:hover {
    background-color: #f9fafb;
}

.btn-sm {
    padding: 0.25rem 0.5rem;
    font-size: 0.875rem;
    border-radius: 0.375rem;
}

.btn-info {
    background-color: #3b82f6;
    color: white;
    border: none;
}

.btn-warning {
    background-color: #f59e0b;
    color: white;
    border: none;
}

.btn-danger {
    background-color: #ef4444;
    color: white;
    border: none;
}

.btn-info:hover,
.btn-warning:hover,
.btn-danger:hover {
    opacity: 0.9;
    transform: translateY(-1px);
}
</style>
@endsection
