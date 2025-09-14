@extends('layouts.admin')

@section('title', 'Coordinator Details')
@section('page-title', 'Coordinator Details')

@section('breadcrumbs')
    <span class="breadcrumb-separator">›</span>
    <a href="{{ route('admin.coordinators.index') }}" class="breadcrumb-item">Coordinators</a>
    <span class="breadcrumb-separator">›</span>
    <span class="breadcrumb-item active">Details</span>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <h3>Coordinator Information</h3>
            <div style="display: flex; gap: 0.5rem;">
                @can('edit coordinators')
                    <a href="{{ route('admin.coordinators.edit', $coordinator) }}" class="btn btn-warning">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 8px;">
                            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                        </svg>
                        Edit Coordinator
                    </a>
                @endcan
                <a href="{{ route('admin.coordinators.index') }}" class="btn btn-secondary">
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
            <!-- Coordinator Basic Information -->
            <div>
                <div style="display: flex; align-items: center; margin-bottom: 2rem;">
                    <div style="width: 80px; height: 80px; background: linear-gradient(135deg, #7c3aed 0%, #5b21b6 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin-right: 1rem;">
                        <span style="color: white; font-weight: bold; font-size: 14px; font-family: monospace;">
                            {{ substr($coordinator->coordinator_id, -3) }}
                        </span>
                    </div>
                    <div>
                        <h2 style="margin: 0; color: #1f2937;">{{ $coordinator->coordinator_name }}</h2>
                        <p style="margin: 0.25rem 0 0 0; color: #6b7280;">
                            <span class="status-badge status-{{ $coordinator->status }}">
                                {{ $coordinator->status_display }}
                            </span>
                        </p>
                    </div>
                </div>

                <div style="background: #f8fafc; padding: 1.5rem; border-radius: 0.5rem; margin-bottom: 1.5rem;">
                    <h4 style="margin-bottom: 1rem; color: #374151;">Personal Information</h4>
                    
                    <div style="display: grid; gap: 1rem;">
                        <div class="info-item">
                            <label>Coordinator ID</label>
                            <div>
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 8px; color: #6b7280;">
                                    <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                                    <line x1="9" y1="9" x2="15" y2="15"></line>
                                    <line x1="15" y1="9" x2="9" y2="15"></line>
                                </svg>
                                <span style="font-family: monospace; font-weight: bold; background: #1f2937; color: white; padding: 0.25rem 0.5rem; border-radius: 0.25rem;">
                                    {{ $coordinator->coordinator_id }}
                                </span>
                            </div>
                        </div>

                        <div class="info-item">
                            <label>NIC No.</label>
                            <div>
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 8px; color: #6b7280;">
                                    <rect x="2" y="3" width="20" height="14" rx="2" ry="2"></rect>
                                    <line x1="8" y1="21" x2="16" y2="21"></line>
                                    <line x1="12" y1="17" x2="12" y2="21"></line>
                                </svg>
                                <span style="font-family: monospace;">{{ $coordinator->nic_no }}</span>
                            </div>
                        </div>

                        <div class="info-item">
                            <label>Phone Number</label>
                            <div>
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 8px; color: #6b7280;">
                                    <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path>
                                </svg>
                                {{ $coordinator->phone_no }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Bank Details and Additional Info -->
            <div>
                <div style="background: #f8fafc; padding: 1.5rem; border-radius: 0.5rem; margin-bottom: 1.5rem;">
                    <h4 style="margin-bottom: 1rem; color: #374151;">Bank Details</h4>
                    
                    <div style="display: grid; gap: 1rem;">
                        <div class="info-item">
                            <label>Bank Name</label>
                            <div>
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 8px; color: #6b7280;">
                                    <rect x="2" y="3" width="20" height="14" rx="2" ry="2"></rect>
                                    <line x1="8" y1="21" x2="16" y2="21"></line>
                                    <line x1="12" y1="17" x2="12" y2="21"></line>
                                </svg>
                                {{ $coordinator->bank_name }}
                            </div>
                        </div>

                        <div class="info-item">
                            <label>Bank Branch</label>
                            <div>
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 8px; color: #6b7280;">
                                    <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                                    <polyline points="9,22 9,12 15,12 15,22"></polyline>
                                </svg>
                                {{ $coordinator->bank_branch_name }}
                            </div>
                        </div>

                        <div class="info-item">
                            <label>Account Number</label>
                            <div>
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 8px; color: #6b7280;">
                                    <rect x="1" y="4" width="22" height="16" rx="2" ry="2"></rect>
                                    <line x1="1" y1="10" x2="23" y2="10"></line>
                                </svg>
                                <span style="font-family: monospace;">{{ $coordinator->account_number }}</span>
                            </div>
                        </div>
                    </div>
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
                                {{ $coordinator->created_at->format('F d, Y \a\t g:i A') }}
                            </div>
                        </div>

                        <div class="info-item">
                            <label>Last Updated</label>
                            <div>
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 8px; color: #6b7280;">
                                    <circle cx="12" cy="12" r="10"></circle>
                                    <polyline points="12,6 12,12 16,14"></polyline>
                                </svg>
                                {{ $coordinator->updated_at->format('F d, Y \a\t g:i A') }}
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
    background-color: #fef3c7;
    color: #92400e;
}

.status-suspended {
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

