@extends('layouts.admin')

@section('title', 'Position Wise Salary Rules')
@section('page-title', 'Position Wise Salary Rules')

@section('breadcrumbs')
    <span class="breadcrumb-separator">›</span>
    <span class="breadcrumb-item active">Position Wise Salary Rules</span>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <h3>Position Wise Salary Rules</h3>
            <a href="{{ route('admin.position-wise-salary-rules.create') }}" class="btn btn-primary">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 8px;">
                    <line x1="12" y1="5" x2="12" y2="19"></line>
                    <line x1="5" y1="12" x2="19" y2="12"></line>
                </svg>
                Add New Rule
            </a>
        </div>
    </div>
    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        @if($rules->count() > 0)
            <div style="overflow-x: auto;">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Position</th>
                            <th>Job</th>
                            <th>Amount</th>
                            <th>Description</th>
                            <th>Status</th>
                            <th>Created At</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($rules as $rule)
                            <tr>
                                <td>{{ $rule->position->position_name }}</td>
                                <td>{{ $rule->job ? $rule->job->job_number . ' - ' . $rule->job->job_name : 'General Rule' }}</td>
                                <td>Rs. {{ number_format($rule->amount, 2) }}</td>
                                <td>{{ $rule->description ?: 'No description' }}</td>
                                <td>
                                    <span class="badge {{ $rule->status === 'active' ? 'badge-success' : 'badge-secondary' }}">
                                        {{ ucfirst($rule->status) }}
                                    </span>
                                </td>
                                <td>{{ $rule->created_at->format('M d, Y') }}</td>
                                <td>
                                    <div style="display: flex; gap: 0.5rem;">
                                        <a href="{{ route('admin.position-wise-salary-rules.edit', $rule) }}" class="btn btn-sm btn-outline-primary">
                                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                                <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                            </svg>
                                        </a>
                                        <form action="{{ route('admin.position-wise-salary-rules.destroy', $rule) }}" method="POST" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this salary rule?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                    <polyline points="3,6 5,6 21,6"></polyline>
                                                    <path d="M19,6v14a2,2 0 0,1 -2,2H7a2,2 0 0,1 -2,-2V6m3,0V4a2,2 0 0,1 2,-2h4a2,2 0 0,1 2,2v2"></path>
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div style="text-align: center; padding: 3rem; color: #6b7280;">
                <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" style="margin-bottom: 1rem; opacity: 0.5;">
                    <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                    <line x1="9" y1="9" x2="15" y2="15"></line>
                    <line x1="15" y1="9" x2="9" y2="15"></line>
                </svg>
                <h4>No Salary Rules Found</h4>
                <p>Start by creating your first position wise salary rule.</p>
                <a href="{{ route('admin.position-wise-salary-rules.create') }}" class="btn btn-primary" style="margin-top: 1rem;">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 8px;">
                        <line x1="12" y1="5" x2="12" y2="19"></line>
                        <line x1="5" y1="12" x2="19" y2="12"></line>
                    </svg>
                    Add First Rule
                </a>
            </div>
        @endif
    </div>
</div>

<style>
.table {
    width: 100%;
    border-collapse: collapse;
    background: white;
    border-radius: 0.5rem;
    overflow: hidden;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}

.table th {
    background-color: #374151;
    color: white;
    padding: 1rem;
    text-align: left;
    font-weight: 600;
    border-bottom: 1px solid #4b5563;
}

.table td {
    padding: 1rem;
    border-bottom: 1px solid #e5e7eb;
    vertical-align: middle;
}

.table tbody tr:hover {
    background-color: #f8fafc;
}

.badge {
    padding: 0.25rem 0.75rem;
    border-radius: 9999px;
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.badge-success {
    background-color: #d1fae5;
    color: #065f46;
}

.badge-secondary {
    background-color: #e5e7eb;
    color: #374151;
}

.btn-sm {
    padding: 0.375rem 0.75rem;
    font-size: 0.875rem;
    border-radius: 0.375rem;
}

.btn-outline-primary {
    color: #3b82f6;
    border-color: #3b82f6;
    background-color: transparent;
}

.btn-outline-primary:hover {
    background-color: #3b82f6;
    color: white;
}

.btn-outline-danger {
    color: #dc2626;
    border-color: #dc2626;
    background-color: transparent;
}

.btn-outline-danger:hover {
    background-color: #dc2626;
    color: white;
}
</style>
@endsection
