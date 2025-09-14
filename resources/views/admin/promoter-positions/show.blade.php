@extends('layouts.admin')

@section('title', 'Position Details')
@section('page-title', 'Position Details')

@section('breadcrumbs')
    <span class="breadcrumb-separator">›</span>
    <a href="{{ route('admin.promoter-positions.index') }}" class="breadcrumb-item">Promoter Positions</a>
    <span class="breadcrumb-separator">›</span>
    <span class="breadcrumb-item active">Details</span>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <h3>Position Information</h3>
            <div style="display: flex; gap: 0.5rem;">
                @can('edit promoter positions')
                    <a href="{{ route('admin.promoter-positions.edit', $promoterPosition) }}" class="btn btn-warning">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 8px;">
                            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                        </svg>
                        Edit Position
                    </a>
                @endcan
                <a href="{{ route('admin.promoter-positions.index') }}" class="btn btn-secondary">
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
            <!-- Position Basic Information -->
            <div>
                <div style="display: flex; align-items: center; margin-bottom: 2rem;">
                    <div style="width: 80px; height: 80px; background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin-right: 1rem;">
                        <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                            <circle cx="9" cy="9" r="2"></circle>
                            <path d="M21 15.5V19a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-3.5"></path>
                        </svg>
                    </div>
                    <div>
                        <h2 style="margin: 0; color: #1f2937;">{{ $promoterPosition->position_name }}</h2>
                        <p style="margin: 0.25rem 0 0 0; color: #6b7280;">
                            <span class="status-badge status-{{ $promoterPosition->status }}">
                                {{ $promoterPosition->status_display }}
                            </span>
                        </p>
                    </div>
                </div>

                <div style="background: #f8fafc; padding: 1.5rem; border-radius: 0.5rem;">
                    <h4 style="margin-bottom: 1rem; color: #374151;">Position Details</h4>
                    
                    <div style="display: grid; gap: 1rem;">
                        <div class="info-item">
                            <label>Position Name</label>
                            <div>
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 8px; color: #6b7280;">
                                    <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                                    <circle cx="9" cy="9" r="2"></circle>
                                    <path d="M21 15.5V19a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-3.5"></path>
                                </svg>
                                {{ $promoterPosition->position_name }}
                            </div>
                        </div>

                        <div class="info-item">
                            <label>Status</label>
                            <div>
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 8px; color: #6b7280;">
                                    <circle cx="12" cy="12" r="10"></circle>
                                    <polyline points="12,6 12,12 16,14"></polyline>
                                </svg>
                                <span class="status-badge status-{{ $promoterPosition->status }}">
                                    {{ $promoterPosition->status_display }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Description and Additional Info -->
            <div>
                <div style="background: #f8fafc; padding: 1.5rem; border-radius: 0.5rem; margin-bottom: 1.5rem;">
                    <h4 style="margin-bottom: 1rem; color: #374151;">Description</h4>
                    
                    @if($promoterPosition->description)
                        <div style="color: #374151; line-height: 1.6; white-space: pre-wrap;">{{ $promoterPosition->description }}</div>
                    @else
                        <div style="color: #9ca3af; font-style: italic;">No description provided</div>
                    @endif
                </div>

                <div style="background: #f8fafc; padding: 1.5rem; border-radius: 0.5rem;">
                    <h4 style="margin-bottom: 1rem; color: #374151;">Account Information</h4>
                    
                    <div style="display: grid; gap: 1rem;">
                        <div class="info-item">
                            <label>Created</label>
                            <div>
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 8px; color: #6b7280;">
                                    <circle cx="12" cy="12" r="10"></circle>
                                    <polyline points="12,6 12,12 16,14"></polyline>
                                </svg>
                                {{ $promoterPosition->created_at->format('F d, Y \a\t g:i A') }}
                            </div>
                        </div>

                        <div class="info-item">
                            <label>Last Updated</label>
                            <div>
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 8px; color: #6b7280;">
                                    <circle cx="12" cy="12" r="10"></circle>
                                    <polyline points="12,6 12,12 16,14"></polyline>
                                </svg>
                                {{ $promoterPosition->updated_at->format('F d, Y \a\t g:i A') }}
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

.status-active {
    background-color: #d1fae5;
    color: #065f46;
}

.status-inactive {
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

