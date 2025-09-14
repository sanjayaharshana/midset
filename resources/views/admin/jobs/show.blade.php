@extends('layouts.admin')

@section('title', 'Job Details')
@section('page-title', 'Job Details')

@section('breadcrumbs')
    <span class="breadcrumb-separator">›</span>
    <a href="{{ route('admin.jobs.index') }}" class="breadcrumb-item">Jobs</a>
    <span class="breadcrumb-separator">›</span>
    <span class="breadcrumb-item active">Details</span>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <h3>Job Information</h3>
            <div style="display: flex; gap: 0.5rem;">
                @can('edit jobs')
                    <a href="{{ route('admin.jobs.edit', $job) }}" class="btn btn-warning">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 8px;">
                            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                        </svg>
                        Edit Job
                    </a>
                @endcan
                <a href="{{ route('admin.jobs.index') }}" class="btn btn-secondary">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 8px;">
                        <path d="M19 12H5M12 19l-7-7 7-7"></path>
                    </svg>
                    Back to List
                </a>
            </div>
        </div>
    </div>
    <div class="card-body">
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem;">
            <!-- Job Basic Information -->
            <div>
                <div style="display: flex; align-items: center; margin-bottom: 2rem;">
                    <div style="width: 80px; height: 80px; background: linear-gradient(135deg, #1f2937 0%, #374151 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin-right: 1rem;">
                        <span style="color: white; font-weight: bold; font-size: 16px; font-family: monospace;">
                            {{ $job->job_number }}
                        </span>
                    </div>
                    <div>
                        <h2 style="margin: 0; color: #1f2937;">{{ $job->job_name }}</h2>
                        <p style="margin: 0.25rem 0 0 0; color: #6b7280;">
                            <span class="status-badge status-{{ $job->status }}">
                                {{ ucfirst(str_replace('_', ' ', $job->status)) }}
                            </span>
                            @if($job->is_overdue)
                                <span style="color: #ef4444; margin-left: 0.5rem;">⚠️ Overdue</span>
                            @endif
                        </p>
                    </div>
                </div>

                <div style="background: #f8fafc; padding: 1.5rem; border-radius: 0.5rem; margin-bottom: 1.5rem;">
                    <h4 style="margin-bottom: 1rem; color: #374151;">Job Details</h4>
                    
                    <div style="display: grid; gap: 1rem;">
                        <div class="info-item">
                            <label>Job Number</label>
                            <div>
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 8px; color: #6b7280;">
                                    <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                                    <line x1="9" y1="9" x2="15" y2="15"></line>
                                    <line x1="15" y1="9" x2="9" y2="15"></line>
                                </svg>
                                <span style="font-family: monospace; font-weight: bold; background: #1f2937; color: white; padding: 0.25rem 0.5rem; border-radius: 0.25rem;">
                                    {{ $job->job_number }}
                                </span>
                            </div>
                        </div>

                        <div class="info-item">
                            <label>Client</label>
                            <div>
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 8px; color: #6b7280;">
                                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                    <circle cx="9" cy="7" r="4"></circle>
                                    <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                                    <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                                </svg>
                                <span style="background: #3b82f6; color: white; padding: 0.25rem 0.5rem; border-radius: 0.25rem; font-weight: bold; font-size: 0.8rem; margin-right: 8px;">
                                    {{ $job->client->short_code }}
                                </span>
                                {{ $job->client->name }}
                            </div>
                        </div>

                        @if($job->description)
                        <div class="info-item">
                            <label>Description</label>
                            <div style="color: #4b5563; line-height: 1.6;">
                                {{ $job->description }}
                            </div>
                        </div>
                        @endif
                    </div>
                </div>

                @if($job->officer_name || $job->reporter_officer_name)
                <div style="background: #f8fafc; padding: 1.5rem; border-radius: 0.5rem; margin-bottom: 1.5rem;">
                    <h4 style="margin-bottom: 1rem; color: #374151;">Officer Information</h4>
                    
                    <div style="display: grid; gap: 1rem;">
                        @if($job->officer_name)
                        <div class="info-item">
                            <label>Officer Name</label>
                            <div>
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 8px; color: #6b7280;">
                                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                    <circle cx="12" cy="7" r="4"></circle>
                                </svg>
                                {{ $job->officer_name }}
                            </div>
                        </div>
                        @endif

                        @if($job->reporter_officer_name)
                        <div class="info-item">
                            <label>Reporter Officer</label>
                            <div>
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 8px; color: #6b7280;">
                                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                    <circle cx="12" cy="7" r="4"></circle>
                                </svg>
                                {{ $job->reporter_officer_name }}
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
                @endif
            </div>

            <!-- Timeline and Additional Info -->
            <div>
                <div style="background: #f8fafc; padding: 1.5rem; border-radius: 0.5rem; margin-bottom: 1.5rem;">
                    <h4 style="margin-bottom: 1rem; color: #374151;">Timeline</h4>
                    
                    <div style="display: grid; gap: 1rem;">
                        <div class="info-item">
                            <label>Start Date</label>
                            <div>
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 8px; color: #6b7280;">
                                    <circle cx="12" cy="12" r="10"></circle>
                                    <polyline points="12,6 12,12 16,14"></polyline>
                                </svg>
                                {{ $job->start_date ? $job->start_date->format('F d, Y') : 'Not set' }}
                            </div>
                        </div>

                        <div class="info-item">
                            <label>End Date</label>
                            <div>
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 8px; color: #6b7280;">
                                    <circle cx="12" cy="12" r="10"></circle>
                                    <polyline points="12,6 12,12 16,14"></polyline>
                                </svg>
                                {{ $job->end_date ? $job->end_date->format('F d, Y') : 'Not set' }}
                                @if($job->end_date && $job->is_overdue)
                                    <span style="color: #ef4444; margin-left: 0.5rem;">(Overdue)</span>
                                @endif
                            </div>
                        </div>

                        @if($job->duration)
                        <div class="info-item">
                            <label>Duration</label>
                            <div>
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 8px; color: #6b7280;">
                                    <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                                    <line x1="16" y1="2" x2="16" y2="6"></line>
                                    <line x1="8" y1="2" x2="8" y2="6"></line>
                                    <line x1="3" y1="10" x2="21" y2="10"></line>
                                </svg>
                                {{ $job->duration }} days
                            </div>
                        </div>
                        @endif
                    </div>
                </div>

                <div style="background: #f8fafc; padding: 1.5rem; border-radius: 0.5rem;">
                    <h4 style="margin-bottom: 1rem; color: #374151;">Job Information</h4>
                    
                    <div style="display: grid; gap: 1rem;">
                        <div class="info-item">
                            <label>Created</label>
                            <div>
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 8px; color: #6b7280;">
                                    <circle cx="12" cy="12" r="10"></circle>
                                    <polyline points="12,6 12,12 16,14"></polyline>
                                </svg>
                                {{ $job->created_at->format('F d, Y \a\t g:i A') }}
                            </div>
                        </div>

                        <div class="info-item">
                            <label>Last Updated</label>
                            <div>
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 8px; color: #6b7280;">
                                    <circle cx="12" cy="12" r="10"></circle>
                                    <polyline points="12,6 12,12 16,14"></polyline>
                                </svg>
                                {{ $job->updated_at->format('F d, Y \a\t g:i A') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
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

.info-item {
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
}

.info-item label {
    font-size: 0.75rem;
    font-weight: 600;
    color: #6b7280;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.info-item div {
    display: flex;
    align-items: center;
    color: #374151;
    font-weight: 500;
}

.btn-warning {
    background-color: #f59e0b;
    color: white;
    padding: 0.75rem 1.5rem;
    border: none;
    border-radius: 0.375rem;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    font-weight: 500;
    transition: all 0.2s;
}

.btn-warning:hover {
    background-color: #d97706;
    transform: translateY(-1px);
}

.btn-secondary {
    background-color: #6b7280;
    color: white;
    padding: 0.75rem 1.5rem;
    border: none;
    border-radius: 0.375rem;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    font-weight: 500;
    transition: all 0.2s;
}

.btn-secondary:hover {
    background-color: #4b5563;
    transform: translateY(-1px);
}

@media (max-width: 768px) {
    div[style*="grid-template-columns"] {
        grid-template-columns: 1fr !important;
    }
}
</style>
@endsection

