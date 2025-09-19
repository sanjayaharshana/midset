@extends('layouts.admin')

@section('title', 'Coordinators')
@section('page-title', 'Coordinator Details')

@section('breadcrumbs')
    <span class="breadcrumb-separator">›</span>
    <span class="breadcrumb-item active">Coordinators</span>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <h3>All Coordinators</h3>
            @can('create coordinators')
                <a href="{{ route('admin.coordinators.create') }}" class="btn btn-success">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 8px;">
                        <line x1="12" y1="5" x2="12" y2="19"></line>
                        <line x1="5" y1="12" x2="19" y2="12"></line>
                    </svg>
                    Add New Coordinator
                </a>
            @endcan
        </div>
    </div>
    <div class="card-body">
        <!-- Search and Filter Bar -->
        <div class="search-filter-bar" style="background: #f8fafc; padding: 1.5rem; border-radius: 8px; margin-bottom: 1.5rem; border: 1px solid #e2e8f0;">
            <form method="GET" action="{{ route('admin.coordinators.index') }}" id="searchFilterForm">
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; margin-bottom: 1rem;">
                    <!-- Search Input -->
                    <div>
                        <label for="search" style="display: block; margin-bottom: 0.5rem; font-weight: 500; color: #374151; font-size: 0.875rem;">Search</label>
                        <div style="position: relative;">
                            <input type="text" 
                                   id="search" 
                                   name="search" 
                                   value="{{ request('search') }}" 
                                   placeholder="Search coordinators..." 
                                   style="width: 100%; padding: 0.75rem 1rem 0.75rem 2.5rem; border: 1px solid #d1d5db; border-radius: 6px; font-size: 0.875rem; background: white;">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="position: absolute; left: 0.75rem; top: 50%; transform: translateY(-50%); color: #6b7280;">
                                <circle cx="11" cy="11" r="8"></circle>
                                <path d="M21 21l-4.35-4.35"></path>
                            </svg>
                        </div>
                    </div>

                    <!-- Status Filter -->
                    <div>
                        <label for="status" style="display: block; margin-bottom: 0.5rem; font-weight: 500; color: #374151; font-size: 0.875rem;">Status</label>
                        <select id="status" name="status" style="width: 100%; padding: 0.75rem 1rem; border: 1px solid #d1d5db; border-radius: 6px; font-size: 0.875rem; background: white;">
                            <option value="">All Status</option>
                            @foreach($statuses as $status)
                                <option value="{{ $status }}" {{ request('status') == $status ? 'selected' : '' }}>
                                    {{ ucfirst($status) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Sort By -->
                    <div>
                        <label for="sort_by" style="display: block; margin-bottom: 0.5rem; font-weight: 500; color: #374151; font-size: 0.875rem;">Sort By</label>
                        <select id="sort_by" name="sort_by" style="width: 100%; padding: 0.75rem 1rem; border: 1px solid #d1d5db; border-radius: 6px; font-size: 0.875rem; background: white;">
                            <option value="created_at" {{ request('sort_by') == 'created_at' ? 'selected' : '' }}>Created Date</option>
                            <option value="coordinator_name" {{ request('sort_by') == 'coordinator_name' ? 'selected' : '' }}>Name</option>
                            <option value="coordinator_id" {{ request('sort_by') == 'coordinator_id' ? 'selected' : '' }}>Coordinator ID</option>
                            <option value="status" {{ request('sort_by') == 'status' ? 'selected' : '' }}>Status</option>
                        </select>
                    </div>
                </div>

                <!-- Date Range Filters -->
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 1rem; margin-bottom: 1rem;">
                    <div>
                        <label for="date_from" style="display: block; margin-bottom: 0.5rem; font-weight: 500; color: #374151; font-size: 0.875rem;">From Date</label>
                        <input type="date" 
                               id="date_from" 
                               name="date_from" 
                               value="{{ request('date_from') }}" 
                               style="width: 100%; padding: 0.75rem 1rem; border: 1px solid #d1d5db; border-radius: 6px; font-size: 0.875rem; background: white;">
                    </div>
                    <div>
                        <label for="date_to" style="display: block; margin-bottom: 0.5rem; font-weight: 500; color: #374151; font-size: 0.875rem;">To Date</label>
                        <input type="date" 
                               id="date_to" 
                               name="date_to" 
                               value="{{ request('date_to') }}" 
                               style="width: 100%; padding: 0.75rem 1rem; border: 1px solid #d1d5db; border-radius: 6px; font-size: 0.875rem; background: white;">
                    </div>
                    <div>
                        <label for="sort_order" style="display: block; margin-bottom: 0.5rem; font-weight: 500; color: #374151; font-size: 0.875rem;">Order</label>
                        <select id="sort_order" name="sort_order" style="width: 100%; padding: 0.75rem 1rem; border: 1px solid #d1d5db; border-radius: 6px; font-size: 0.875rem; background: white;">
                            <option value="desc" {{ request('sort_order') == 'desc' ? 'selected' : '' }}>Descending</option>
                            <option value="asc" {{ request('sort_order') == 'asc' ? 'selected' : '' }}>Ascending</option>
                        </select>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div style="display: flex; gap: 0.75rem; align-items: center;">
                    <button type="submit" class="btn btn-primary" style="display: flex; align-items: center; gap: 0.5rem;">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="11" cy="11" r="8"></circle>
                            <path d="M21 21l-4.35-4.35"></path>
                        </svg>
                        Search & Filter
                    </button>
                    <a href="{{ route('admin.coordinators.index') }}" class="btn btn-secondary" style="display: flex; align-items: center; gap: 0.5rem;">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M3 6h18"></path>
                            <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"></path>
                            <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"></path>
                        </svg>
                        Clear Filters
                    </a>
                    @if(request()->hasAny(['search', 'status', 'date_from', 'date_to', 'sort_by', 'sort_order']))
                        <div style="margin-left: auto; padding: 0.5rem 1rem; background: #dbeafe; color: #1e40af; border-radius: 6px; font-size: 0.875rem; font-weight: 500;">
                            {{ $coordinators->total() }} result(s) found
                        </div>
                    @endif
                </div>
            </form>
        </div>

        @if($coordinators->count() > 0)
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Coordinator ID</th>
                            <th>Name</th>
                            <th>NIC No.</th>
                            <th>Phone</th>
                            <th>Bank Details</th>
                            <th>Status</th>
                            <th>Created</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($coordinators as $coordinator)
                        <tr>
                            <td>
                                <span style="background: #1f2937; color: white; padding: 0.25rem 0.5rem; border-radius: 0.25rem; font-weight: bold; font-size: 0.8rem; font-family: monospace;">
                                    {{ $coordinator->coordinator_id }}
                                </span>
                            </td>
                            <td>
                                <div style="font-weight: 600;">{{ $coordinator->coordinator_name }}</div>
                            </td>
                            <td>
                                <span style="font-family: monospace; font-size: 0.9rem;">{{ $coordinator->nic_no }}</span>
                            </td>
                            <td>{{ $coordinator->phone_no }}</td>
                            <td>
                                <div style="font-size: 0.85rem;">
                                    <div style="font-weight: 500;">{{ $coordinator->bank_name }}</div>
                                    <div style="color: #6b7280;">{{ $coordinator->bank_branch_name }}</div>
                                    <div style="color: #6b7280; font-family: monospace;">****{{ substr($coordinator->account_number, -4) }}</div>
                                </div>
                            </td>
                            <td>
                                <span class="status-badge status-{{ $coordinator->status }}">
                                    {{ $coordinator->status_display }}
                                </span>
                            </td>
                            <td>{{ $coordinator->created_at->format('M d, Y') }}</td>
                            <td>
                                <div style="display: flex; gap: 8px;">
                                    @can('view coordinators')
                                        <a href="{{ route('admin.coordinators.show', $coordinator) }}" class="btn btn-sm btn-info">
                                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                                <circle cx="12" cy="12" r="3"></circle>
                                            </svg>
                                        </a>
                                    @endcan
                                    @can('edit coordinators')
                                        <a href="{{ route('admin.coordinators.edit', $coordinator) }}" class="btn btn-sm btn-warning">
                                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                                <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                            </svg>
                                        </a>
                                    @endcan
                                    @can('delete coordinators')
                                        <form action="{{ route('admin.coordinators.destroy', $coordinator) }}" method="POST" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete coordinator {{ $coordinator->coordinator_id }}?')">
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
            <div class="pagination-container">
                {{ $coordinators->links() }}
            </div>
            </div>
        @else
            <div style="text-align: center; padding: 2rem;">
                <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="#d1d5db" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" style="margin-bottom: 1rem;">
                    <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path>
                    <circle cx="9" cy="7" r="4"></circle>
                    <path d="M22 21v-2a4 4 0 0 0-3-3.87"></path>
                    <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                </svg>
                <h3 style="color: #6b7280; margin-bottom: 0.5rem;">No coordinators found</h3>
                <p style="color: #9ca3af;">Get started by adding your first coordinator.</p>
                @can('create coordinators')
                    <a href="{{ route('admin.coordinators.create') }}" class="btn btn-success" style="margin-top: 1rem;">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 8px;">
                            <line x1="12" y1="5" x2="12" y2="19"></line>
                            <line x1="5" y1="12" x2="19" y2="12"></line>
                        </svg>
                        Add First Coordinator
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

/* Search and Filter Bar Styles */
.search-filter-bar input:focus,
.search-filter-bar select:focus {
    outline: none;
    border-color: #3b82f6;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

.search-filter-bar .btn {
    transition: all 0.15s ease-in-out;
}

.search-filter-bar .btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
}
</style>

<script>
// Search and Filter functionality
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('searchFilterForm');
    const searchInput = document.getElementById('search');
    const statusSelect = document.getElementById('status');
    const sortBySelect = document.getElementById('sort_by');
    const sortOrderSelect = document.getElementById('sort_order');
    
    // Auto-submit on select changes (with debounce for search)
    let searchTimeout;
    searchInput.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            form.submit();
        }, 500); // 500ms delay for search
    });
    
    // Immediate submit for select changes
    [statusSelect, sortBySelect, sortOrderSelect].forEach(select => {
        select.addEventListener('change', function() {
            form.submit();
        });
    });
    
    // Date inputs
    const dateFromInput = document.getElementById('date_from');
    const dateToInput = document.getElementById('date_to');
    
    [dateFromInput, dateToInput].forEach(input => {
        input.addEventListener('change', function() {
            form.submit();
        });
    });
    
    // Clear filters functionality
    const clearButton = document.querySelector('a[href="{{ route('admin.coordinators.index') }}"]');
    if (clearButton) {
        clearButton.addEventListener('click', function(e) {
            e.preventDefault();
            // Clear all form inputs
            form.reset();
            // Submit the form to clear filters
            form.submit();
        });
    }
});
</script>
@endsection

