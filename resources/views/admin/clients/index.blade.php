@extends('layouts.admin')

@section('title', 'Clients')
@section('page-title', 'Client Management')

@section('breadcrumbs')
    <span class="breadcrumb-separator">›</span>
    <span class="breadcrumb-item active">Clients</span>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <h3>All Clients</h3>
            @can('create clients')
                <a href="{{ route('admin.clients.create') }}" class="btn btn-success">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 8px;">
                        <line x1="12" y1="5" x2="12" y2="19"></line>
                        <line x1="5" y1="12" x2="19" y2="12"></line>
                    </svg>
                    Add New Client
                </a>
            @endcan
        </div>
    </div>
    <div class="card-body">
        @if($clients->count() > 0)
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Short Code</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Company</th>
                            <th>Status</th>
                            <th>Created</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($clients as $client)
                        <tr>
                            <td>
                                <div style="display: flex; align-items: center;">
                                    <div style="width: 40px; height: 40px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin-right: 12px;">
                                        <span style="color: white; font-weight: bold; font-size: 14px;">
                                            {{ strtoupper(substr($client->name, 0, 2)) }}
                                        </span>
                                    </div>
                                    <div>
                                        <div style="font-weight: 600;">{{ $client->name }}</div>
                                        @if($client->contact_person)
                                            <div style="font-size: 0.8rem; color: #6b7280;">Contact: {{ $client->contact_person }}</div>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span style="background: #3b82f6; color: white; padding: 0.25rem 0.5rem; border-radius: 0.25rem; font-weight: bold; font-size: 0.8rem;">
                                    {{ $client->short_code }}
                                </span>
                            </td>
                            <td>{{ $client->email }}</td>
                            <td>{{ $client->phone ?: 'N/A' }}</td>
                            <td>{{ $client->company_name ?: 'N/A' }}</td>
                            <td>
                                <span class="status-badge status-{{ $client->status }}">
                                    {{ ucfirst($client->status) }}
                                </span>
                            </td>
                            <td>{{ $client->created_at->format('M d, Y') }}</td>
                            <td>
                                <div style="display: flex; gap: 8px;">
                                    @can('view clients')
                                        <a href="{{ route('admin.clients.show', $client) }}" class="btn btn-sm btn-info">
                                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                                <circle cx="12" cy="12" r="3"></circle>
                                            </svg>
                                        </a>
                                    @endcan
                                    @can('edit clients')
                                        <a href="{{ route('admin.clients.edit', $client) }}" class="btn btn-sm btn-warning">
                                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                                <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                            </svg>
                                        </a>
                                    @endcan
                                    @can('delete clients')
                                        <form action="{{ route('admin.clients.destroy', $client) }}" method="POST" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this client?')">
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
                {{ $clients->links() }}
            </div>
        @else
            <div style="text-align: center; padding: 2rem;">
                <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="#d1d5db" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" style="margin-bottom: 1rem;">
                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                    <circle cx="12" cy="7" r="4"></circle>
                </svg>
                <h3 style="color: #6b7280; margin-bottom: 0.5rem;">No clients found</h3>
                <p style="color: #9ca3af;">Get started by adding your first client.</p>
                @can('create clients')
                    <a href="{{ route('admin.clients.create') }}" class="btn btn-success" style="margin-top: 1rem;">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 8px;">
                            <line x1="12" y1="5" x2="12" y2="19"></line>
                            <line x1="5" y1="12" x2="19" y2="12"></line>
                        </svg>
                        Add First Client
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
