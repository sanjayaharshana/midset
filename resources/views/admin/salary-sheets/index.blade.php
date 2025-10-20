@extends('layouts.admin')

@section('title', __('salary_sheets.salary_sheets'))
@section('page-title', __('salary_sheets.salary_sheets'))

@section('breadcrumbs')
    <span class="breadcrumb-separator">›</span>
    <span class="breadcrumb-item active">{{ __('salary_sheets.salary_sheets') }}</span>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <h3>{{ __('salary_sheets.salary_sheets') }}</h3>
            @can('create salary sheets')
                <a href="{{ route('admin.salary-sheets.create') }}" class="btn btn-success">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 8px;">
                        <line x1="12" y1="5" x2="12" y2="19"></line>
                        <line x1="5" y1="12" x2="19" y2="12"></line>
                    </svg>
                    {{ __('salary_sheets.create_salary_sheet') }}
                </a>
            @endcan
        </div>
    </div>
    <div class="card-body">
        @if($salarySheets->count() > 0)
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>{{ __('salary_sheets.sheet_number') }}</th>
                            <th>{{ __('salary_sheets.period') }}</th>
                            <th>{{ __('salary_sheets.job_title') }}</th>
                            <th>{{ __('common.count') }}</th>
                            <th>{{ __('salary_sheets.total_promoters') }}</th>
                            <th>{{ __('salary_sheets.status') }}</th>
                            <th>{{ __('salary_sheets.created') }}</th>
                            <th>{{ __('common.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($salarySheets as $sheet)
                        <tr>
                            <td>
                                <span style="background: #1f2937; color: white; padding: 0.25rem 0.5rem; border-radius: 0.25rem; font-weight: bold; font-size: 0.8rem; font-family: monospace;">
                                    {{ $sheet->sheet_no }}
                                </span>
                            </td>
                            <td>
                                <div style="font-weight: 600;">{{ $sheet->created_at->format('M Y') }}</div>
                            </td>
                            <td>
                                <div>
                                    @if($sheet->job)
                                        <div style="font-weight: 500;">{{ $sheet->job->job_number ?? 'N/A' }}</div>
                                        <div style="color: #6b7280; font-size: 0.8rem;">{{ $sheet->job->job_title ?? 'N/A' }}</div>
                                        @if($sheet->job->client)
                                            <div style="color: #6b7280; font-size: 0.75rem;">{{ $sheet->job->client->client_name ?? 'N/A' }}</div>
                                        @endif
                                    @else
                                        <div style="color: #6b7280;">No job assigned</div>
                                    @endif
                                </div>
                            </td>
                            <td>
                                <span style="background: #3b82f6; color: white; padding: 0.25rem 0.5rem; border-radius: 0.25rem; font-weight: bold; font-size: 0.8rem;">
                                    {{ $sheet->items_count }}
                                </span>
                            </td>
                            <td>
                                <span style="background: #10b981; color: white; padding: 0.25rem 0.5rem; border-radius: 0.25rem; font-weight: bold; font-size: 0.8rem;">
                                    {{ $sheet->promoters_count }}
                                </span>
                            </td>
                            <td>
                                <span class="status-badge status-{{ $sheet->status }}">
                                    {{ ucfirst($sheet->status) }}
                                </span>
                            </td>
                            <td>{{ $sheet->created_at->format('M d, Y') }}</td>
                            <td>
                                <div style="display: flex; gap: 8px;">
                                    @can('view salary sheets')
                                        <a href="{{ route('admin.salary-sheets.show', $sheet) }}" class="btn btn-sm btn-info">
                                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                                <circle cx="12" cy="12" r="3"></circle>
                                            </svg>
                                        </a>
                                    @endcan
                                    @can('view salary sheets')
                                        <a href="{{ route('admin.salary-sheets.print', $sheet) }}" target="_blank" class="btn btn-sm btn-primary">
                                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <polyline points="6,9 6,2 18,2 18,9"></polyline>
                                                <path d="M6,18H4a2,2 0 0,1 -2,-2V11a2,2 0 0,1 2,-2H20a2,2 0 0,1 2,2v5a2,2 0 0,1 -2,2H18"></path>
                                                <polyline points="6,14 18,14 18,22 6,22 6,14"></polyline>
                                            </svg>
                                        </a>
                                    @endcan
                                    @can('delete salary sheets')
                                        @if($sheet->job && $sheet->job->status !== 'completed')
                                            <form action="{{ route('admin.salary-sheets.destroy', $sheet) }}" method="POST" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete salary sheet {{ $sheet->sheet_no }}?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger">
                                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                        <polyline points="3,6 5,6 21,6"></polyline>
                                                        <path d="M19,6v14a2,2 0 0,1 -2,2H7a2,2 0 0,1 -2,-2V6m3,0V4a2,2 0 0,1 2,-2h4a2,2 0 0,1 2,2v2"></path>
                                                    </svg>
                                                </button>
                                            </form>
                                        @else
                                            <button type="button" class="btn btn-sm btn-danger" style="opacity: 0.5; cursor: not-allowed;" title="Cannot delete salary sheets for completed jobs">
                                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                    <polyline points="3,6 5,6 21,6"></polyline>
                                                    <path d="M19,6v14a2,2 0 0,1 -2,2H7a2,2 0 0,1 -2,-2V6m3,0V4a2,2 0 0,1 2,-2h4a2,2 0 0,1 2,2v2"></path>
                                                </svg>
                                            </button>
                                        @endif
                                    @endcan
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <div class="pagination-container">
                {{ $salarySheets->links() }}
            </div>
        @else
            <div style="text-align: center; padding: 2rem;">
                <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="#d1d5db" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" style="margin-bottom: 1rem;">
                    <rect x="2" y="3" width="20" height="14" rx="2" ry="2"></rect>
                    <line x1="8" y1="21" x2="16" y2="21"></line>
                    <line x1="12" y1="17" x2="12" y2="21"></line>
                </svg>
                <h3 style="color: #6b7280; margin-bottom: 0.5rem;">No salary sheets found</h3>
                <p style="color: #9ca3af;">Get started by creating your first salary sheet.</p>
                @can('create salary sheets')
                    <a href="{{ route('admin.salary-sheets.create') }}" class="btn btn-success" style="margin-top: 1rem;">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 8px;">
                            <line x1="12" y1="5" x2="12" y2="19"></line>
                            <line x1="5" y1="12" x2="19" y2="12"></line>
                        </svg>
                        Create First Salary Sheet
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

.status-draft {
    background-color: #fef3c7;
    color: #92400e;
}

.status-approved {
    background-color: #d1fae5;
    color: #065f46;
}

.status-paid {
    background-color: #dbeafe;
    color: #1e40af;
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

.btn-primary {
    background-color: #8b5cf6;
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
.btn-primary:hover,
.btn-warning:hover,
.btn-danger:hover {
    opacity: 0.9;
    transform: translateY(-1px);
}
</style>
@endsection