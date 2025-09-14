@extends('layouts.admin')

@section('title', 'Jobs')
@section('page-title', 'Job ID Management')

@section('breadcrumbs')
    <span class="breadcrumb-separator">›</span>
    <span class="breadcrumb-item active">Jobs</span>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <h3>All Jobs</h3>
            @can('create jobs')
                <a href="{{ route('admin.jobs.create') }}" class="btn btn-success">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 8px;">
                        <line x1="12" y1="5" x2="12" y2="19"></line>
                        <line x1="5" y1="12" x2="19" y2="12"></line>
                    </svg>
                    Create New Job
                </a>
            @endcan
        </div>
    </div>
    <div class="card-body">
        @if($jobs->count() > 0)
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Job Number</th>
                            <th>Job Name</th>
                            <th>Client</th>
                            <th>Officer</th>
                            <th>Status</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($jobs as $job)
                        <tr>
                            <td>
                                <span style="background: #1f2937; color: white; padding: 0.25rem 0.5rem; border-radius: 0.25rem; font-weight: bold; font-size: 0.8rem; font-family: monospace;">
                                    {{ $job->job_number }}
                                </span>
                            </td>
                            <td>
                                <div style="font-weight: 600;">{{ $job->job_name }}</div>
                                @if($job->description)
                                    <div style="font-size: 0.8rem; color: #6b7280; margin-top: 0.25rem;">
                                        {{ Str::limit($job->description, 50) }}
                                    </div>
                                @endif
                            </td>
                            <td>
                                <div style="display: flex; align-items: center;">
                                    <span style="background: #3b82f6; color: white; padding: 0.25rem 0.5rem; border-radius: 0.25rem; font-weight: bold; font-size: 0.8rem; margin-right: 8px;">
                                        {{ $job->client->short_code }}
                                    </span>
                                    <span>{{ $job->client->name }}</span>
                                </div>
                            </td>
                            <td>
                                @if($job->officer_name)
                                    <div style="font-weight: 500;">{{ $job->officer_name }}</div>
                                    @if($job->reporter_officer_name)
                                        <div style="font-size: 0.8rem; color: #6b7280;">Reporter: {{ $job->reporter_officer_name }}</div>
                                    @endif
                                @else
                                    <span style="color: #9ca3af;">Not assigned</span>
                                @endif
                            </td>
                            <td>
                                <span class="status-badge status-{{ $job->status }}">
                                    {{ ucfirst(str_replace('_', ' ', $job->status)) }}
                                </span>
                                @if($job->is_overdue)
                                    <div style="color: #ef4444; font-size: 0.75rem; margin-top: 0.25rem;">⚠️ Overdue</div>
                                @endif
                            </td>
                            <td>{{ $job->start_date ? $job->start_date->format('M d, Y') : 'N/A' }}</td>
                            <td>{{ $job->end_date ? $job->end_date->format('M d, Y') : 'N/A' }}</td>
                            <td>
                                <div style="display: flex; gap: 8px;">
                                    @can('view jobs')
                                        <a href="{{ route('admin.jobs.show', $job) }}" class="btn btn-sm btn-info">
                                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                                <circle cx="12" cy="12" r="3"></circle>
                                            </svg>
                                        </a>
                                    @endcan
                                    @can('edit jobs')
                                        <a href="{{ route('admin.jobs.edit', $job) }}" class="btn btn-sm btn-warning">
                                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                                <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                            </svg>
                                        </a>
                                    @endcan
                                    @can('delete jobs')
                                        <form action="{{ route('admin.jobs.destroy', $job) }}" method="POST" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete job {{ $job->job_number }}?')">
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
                {{ $jobs->links() }}
            </div>
        @else
            <div style="text-align: center; padding: 2rem;">
                <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="#d1d5db" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" style="margin-bottom: 1rem;">
                    <rect x="2" y="3" width="20" height="14" rx="2" ry="2"></rect>
                    <line x1="8" y1="21" x2="16" y2="21"></line>
                    <line x1="12" y1="17" x2="12" y2="21"></line>
                </svg>
                <h3 style="color: #6b7280; margin-bottom: 0.5rem;">No jobs found</h3>
                <p style="color: #9ca3af;">Get started by creating your first job.</p>
                @can('create jobs')
                    <a href="{{ route('admin.jobs.create') }}" class="btn btn-success" style="margin-top: 1rem;">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 8px;">
                            <line x1="12" y1="5" x2="12" y2="19"></line>
                            <line x1="5" y1="12" x2="19" y2="12"></line>
                        </svg>
                        Create First Job
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

.status-pending {
    background-color: #fef3c7;
    color: #92400e;
}

.status-in-progress {
    background-color: #dbeafe;
    color: #1e40af;
}

.status-completed {
    background-color: #d1fae5;
    color: #065f46;
}

.status-cancelled {
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

