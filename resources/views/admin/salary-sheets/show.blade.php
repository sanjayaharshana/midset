@extends('layouts.admin')

@section('title', 'Salary Sheet Details')
@section('page-title', 'Salary Sheet Details')

@section('breadcrumbs')
    <span class="breadcrumb-separator">›</span>
    <a href="{{ route('admin.salary-sheets.index') }}" class="breadcrumb-item">Salary Sheets</a>
    <span class="breadcrumb-separator">›</span>
    <span class="breadcrumb-item active">Details</span>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <h3>Salary Sheet Information</h3>
            <div style="display: flex; gap: 0.5rem;">
                @can('edit salary sheets')
                    @if($salarySheet->job && $salarySheet->job->status !== 'completed' && !in_array($salarySheet->status, ['complete', 'approve', 'paid']))
                        <a href="{{ route('admin.salary-sheets.edit', $salarySheet) }}" class="btn btn-warning">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 8px;">
                                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                            </svg>
                            Edit Salary Sheet
                        </a>
                    @else
                        <span class="btn btn-warning" style="opacity: 0.5; cursor: not-allowed;" title="Cannot edit salary sheets with complete/paid status or for completed jobs">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 8px;">
                                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                            </svg>
                            Edit Salary Sheet (Not Available)
                        </span>
                    @endif
                @endcan
                <a href="{{ route('admin.salary-sheets.index') }}" class="btn btn-secondary">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 8px;">
                        <path d="M19 12H5M12 19l-7-7 7-7"></path>
                    </svg>
                    Back to List
                </a>
            </div>
        </div>
    </div>
    <div class="card-body">
        <!-- Header Section -->
        <div style="display: flex; align-items: center; margin-bottom: 2rem; padding: 1.5rem; background: linear-gradient(135deg, #059669 0%, #047857 100%); border-radius: 0.75rem; color: white;">
            <div style="width: 80px; height: 80px; background: rgba(255,255,255,0.2); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin-right: 1.5rem;">
                <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="2" y="3" width="20" height="14" rx="2" ry="2"></rect>
                    <line x1="8" y1="21" x2="16" y2="21"></line>
                    <line x1="12" y1="17" x2="12" y2="21"></line>
                </svg>
            </div>
            <div>
                <h2 style="margin: 0; font-size: 1.75rem;">{{ $salarySheet->sheet_no }}</h2>
                <p style="margin: 0.5rem 0 0 0; font-size: 1.1rem; opacity: 0.9;">
                    @php
                        $statusDisplay = $salarySheet->status_display;
                        $statusClass = $salarySheet->status;
                        
                        // For reporter role, show "Pending Approval" for "complete" status
                        if (auth()->check() && auth()->user()->hasRole('reporter') && $salarySheet->status === 'complete') {
                            $statusDisplay = 'Pending Approval';
                            $statusClass = 'pending-approval';
                        }
                    @endphp
                    <span class="status-badge status-{{ $statusClass }}" style="background: rgba(255,255,255,0.2); color: white;">
                        {{ $statusDisplay }}
                    </span>
                </p>
                <p style="margin: 0.25rem 0 0 0; font-size: 0.9rem; opacity: 0.8;">
                    {{ $salarySheet->month_name }} {{ $salarySheet->year }}
                </p>
            </div>
        </div>

        <!-- Main Content Grid -->
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem; margin-bottom: 2rem;">
            
            <!-- Left Column: Basic Information -->
            <div>
                <!-- Job Information -->
                @if($salarySheet->job)
                <div class="info-card">
                    <h4 class="card-title">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 8px;">
                            <rect x="2" y="3" width="20" height="14" rx="2" ry="2"></rect>
                            <line x1="8" y1="21" x2="16" y2="21"></line>
                            <line x1="12" y1="17" x2="12" y2="21"></line>
                        </svg>
                        Job Information
                    </h4>
                    
                    <div class="info-grid">
                        <div class="info-item">
                            <label>Job Number</label>
                            <div>{{ $salarySheet->job->job_number ?? 'N/A' }}</div>
                        </div>
                        <div class="info-item">
                            <label>Job Title</label>
                            <div>{{ $salarySheet->job->job_title ?? 'N/A' }}</div>
                        </div>
                        <div class="info-item">
                            <label>Client</label>
                            <div>{{ $salarySheet->job->client->client_name ?? 'N/A' }}</div>
                        </div>
                        <div class="info-item">
                            <label>Location</label>
                            <div>{{ $salarySheet->location ?? 'N/A' }}</div>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Summary Information -->
                <div class="info-card">
                    <h4 class="card-title">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 8px;">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                            <circle cx="12" cy="7" r="4"></circle>
                        </svg>
                        Summary Information
                    </h4>
                    
                    <div class="info-grid">
                        <div class="info-item">
                            <label>Total Items</label>
                            <div>{{ $salarySheet->items->count() }}</div>
                        </div>
                        <div class="info-item">
                            <label>Total Amount</label>
                            <div class="amount">Rs. {{ number_format($salarySheet->total_amount, 2) }}</div>
                        </div>
                        <div class="info-item">
                            <label>Total Attendance Amount</label>
                            <div class="amount">Rs. {{ number_format($salarySheet->total_attendance_amount, 2) }}</div>
                        </div>
                        <div class="info-item">
                            <label>Positions</label>
                            <div>{{ $salarySheet->positions->count() }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column: Financial Summary -->
            <div>
                <!-- Financial Summary -->
                <div class="info-card totals-card">
                    <h4 class="card-title">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 8px;">
                            <rect x="1" y="4" width="22" height="16" rx="2" ry="2"></rect>
                            <line x1="1" y1="10" x2="23" y2="10"></line>
                        </svg>
                        Financial Summary
                    </h4>
                    
                    <div class="totals-grid">
                        <div class="total-item">
                            <label>Total Net Amount</label>
                            <div class="amount net-salary">Rs. {{ number_format($salarySheet->total_amount, 2) }}</div>
                        </div>
                        <div class="total-item">
                            <label>Total Attendance Amount</label>
                            <div class="amount total-earnings">Rs. {{ number_format($salarySheet->total_attendance_amount, 2) }}</div>
                        </div>
                    </div>
                </div>

                <!-- Notes Section -->
                @if($salarySheet->notes)
                <div class="info-card">
                    <h4 class="card-title">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 8px;">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                            <polyline points="14,2 14,8 20,8"></polyline>
                            <line x1="16" y1="13" x2="8" y2="13"></line>
                            <line x1="16" y1="17" x2="8" y2="17"></line>
                            <polyline points="10,9 9,9 8,9"></polyline>
                        </svg>
                        Notes
                    </h4>
                    <div class="notes-content">{{ $salarySheet->notes }}</div>
                </div>
                @endif
            </div>
        </div>

        <!-- Salary Sheet Items Section -->
        @if($salarySheet->items && $salarySheet->items->count() > 0)
        <div class="items-section">
            <h3 class="section-title">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 12px;">
                    <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                    <line x1="16" y1="2" x2="16" y2="6"></line>
                    <line x1="8" y1="2" x2="8" y2="6"></line>
                    <line x1="3" y1="10" x2="21" y2="10"></line>
                </svg>
                Salary Sheet Items ({{ $salarySheet->items->count() }})
            </h3>
            
            @foreach($salarySheet->items as $index => $item)
            <div class="item-card">
                <div class="item-header">
                    <h4>
                        Item #{{ $item->no }}
                        @if($item->position)
                            <span class="position-badge">{{ $item->position->position_name }}</span>
                        @endif
                        @if($item->location)
                            <span class="location-badge">{{ $item->location }}</span>
                        @endif
                    </h4>
                </div>
                
                <div class="item-content">
                    <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 2rem;">
                        
                        <!-- Attendance Data -->
                        <div class="item-section">
                            <h5>Attendance Data</h5>
                            @if($item->daily_attendance && count($item->daily_attendance) > 0)
                            <div class="attendance-dates">
                                <div class="dates-grid">
                                    @foreach($item->daily_attendance as $date => $value)
                                    <div class="date-item {{ $value > 0 ? 'present' : 'absent' }}">
                                        <div class="date">{{ \Carbon\Carbon::parse($date)->format('M d') }}</div>
                                        <div class="value">{{ $value > 0 ? 'Present' : 'Absent' }}</div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                            @endif
                            
                            <div class="attendance-summary">
                                <div class="summary-item">
                                    <label>Total Days</label>
                                    <div class="value">{{ $item->attendance_total }}</div>
                                </div>
                                <div class="summary-item">
                                    <label>Attendance Amount</label>
                                    <div class="value amount">Rs. {{ number_format($item->attendance_amount, 2) }}</div>
                                </div>
                            </div>
                        </div>

                        <!-- Payment Data -->
                        <div class="item-section">
                            <h5>Payment Data</h5>
                            <div class="payment-grid">
                                <div class="payment-item">
                                    <label>Base Amount</label>
                                    <div class="amount">Rs. {{ number_format($item->base_amount, 2) }}</div>
                                </div>
                                <div class="payment-item">
                                    <label>Food Allowance</label>
                                    <div class="amount">Rs. {{ number_format($item->food_allowance, 2) }}</div>
                                </div>
                                <div class="payment-item">
                                    <label>Accommodation Allowance</label>
                                    <div class="amount">Rs. {{ number_format($item->accommodation_allowance, 2) }}</div>
                                </div>
                                <div class="payment-item">
                                    <label>Expenses</label>
                                    <div class="amount deduction">Rs. {{ number_format($item->expenses, 2) }}</div>
                                </div>
                                <div class="payment-item">
                                    <label>Hold for Weeks</label>
                                    <div class="amount deduction">Rs. {{ number_format($item->hold_for_weeks, 2) }}</div>
                                </div>
                                <div class="payment-item total-item">
                                    <label>Net Amount</label>
                                    <div class="amount net-amount">Rs. {{ number_format($item->net_amount, 2) }}</div>
                                </div>
                            </div>
                        </div>

                        <!-- Coordinator Details -->
                        <div class="item-section">
                            <h5>Coordinator Details</h5>
                            @if($item->coordinator_details)
                            <div class="coordinator-grid">
                                <div class="coordinator-item">
                                    <label>Coordinator ID</label>
                                    <div>{{ $item->coordinator_id }}</div>
                                </div>
                                <div class="coordinator-item">
                                    <label>Coordinator Name</label>
                                    <div>{{ $item->coordinator_name }}</div>
                                </div>
                                <div class="coordinator-item">
                                    <label>Coordination Fee</label>
                                    <div class="amount">Rs. {{ number_format($item->coordinator_amount, 2) }}</div>
                                </div>
                            </div>
                            @else
                            <div class="no-coordinator">No coordinator assigned</div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @endif

        <!-- Timestamps -->
        <div class="timestamps-section">
            <div class="timestamp-item">
                <label>Created</label>
                <div>{{ $salarySheet->created_at->format('F d, Y \a\t g:i A') }}</div>
            </div>
            <div class="timestamp-item">
                <label>Last Updated</label>
                <div>{{ $salarySheet->updated_at->format('F d, Y \a\t g:i A') }}</div>
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

.info-card {
    background: #f8fafc;
    padding: 1.5rem;
    border-radius: 0.75rem;
    margin-bottom: 1.5rem;
    border: 1px solid #e5e7eb;
}

.card-title {
    display: flex;
    align-items: center;
    margin-bottom: 1rem;
    color: #374151;
    font-size: 1.1rem;
    font-weight: 600;
}

.info-grid {
    display: grid;
    gap: 1rem;
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
    color: #374151;
    font-weight: 500;
}

.amount {
    font-weight: 600;
    color: #059669;
}

.deduction {
    color: #dc2626;
}

.totals-card {
    background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);
    border: 2px solid #0ea5e9;
}

.totals-grid {
    display: grid;
    gap: 1rem;
}

.total-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.75rem;
    background: white;
    border-radius: 0.5rem;
    border: 1px solid #e5e7eb;
}

.total-earnings {
    font-size: 1.2rem;
    color: #2563eb;
}

.net-salary {
    font-size: 1.3rem;
    color: #dc2626;
    font-weight: bold;
}

.items-section {
    margin: 2rem 0;
}

.section-title {
    display: flex;
    align-items: center;
    margin-bottom: 1.5rem;
    color: #374151;
    font-size: 1.25rem;
    font-weight: 600;
}

.item-card {
    background: white;
    border: 1px solid #e5e7eb;
    border-radius: 0.75rem;
    margin-bottom: 1rem;
    overflow: hidden;
}

.item-header {
    background: #f8fafc;
    padding: 1rem 1.5rem;
    border-bottom: 1px solid #e5e7eb;
}

.item-header h4 {
    margin: 0;
    color: #374151;
    font-size: 1.1rem;
}

.position-badge {
    background: #dbeafe;
    color: #1e40af;
    padding: 0.25rem 0.5rem;
    border-radius: 0.375rem;
    font-size: 0.75rem;
    font-weight: 500;
    margin-left: 0.5rem;
}

.location-badge {
    background: #dcfce7;
    color: #166534;
    padding: 0.25rem 0.5rem;
    border-radius: 0.375rem;
    font-size: 0.75rem;
    font-weight: 500;
    margin-left: 0.5rem;
}

.item-content {
    padding: 1.5rem;
}

.item-section h5 {
    margin: 0 0 1rem 0;
    color: #374151;
    font-size: 1rem;
    font-weight: 600;
}

.dates-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(100px, 1fr));
    gap: 0.5rem;
    margin-bottom: 1rem;
}

.date-item {
    text-align: center;
    padding: 0.5rem;
    border-radius: 0.375rem;
    border: 1px solid #e5e7eb;
    font-size: 0.75rem;
}

.date-item.present {
    background: #d1fae5;
    border-color: #10b981;
}

.date-item.absent {
    background: #fee2e2;
    border-color: #ef4444;
}

.date {
    font-weight: 600;
    color: #6b7280;
    margin-bottom: 0.125rem;
}

.value {
    font-size: 0.75rem;
    font-weight: 500;
}

.attendance-summary {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 0.75rem;
    padding-top: 0.75rem;
    border-top: 1px solid #e5e7eb;
}

.summary-item {
    text-align: center;
}

.summary-item label {
    display: block;
    font-size: 0.625rem;
    font-weight: 600;
    color: #6b7280;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    margin-bottom: 0.125rem;
}

.payment-grid {
    display: grid;
    gap: 0.75rem;
}

.payment-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.5rem;
    background: #f8fafc;
    border-radius: 0.375rem;
    border: 1px solid #e5e7eb;
}

.payment-item.total-item {
    background: #f0f9ff;
    border-color: #0ea5e9;
    font-weight: 600;
}

.net-amount {
    font-size: 1.1rem;
    color: #dc2626;
    font-weight: bold;
}

.coordinator-grid {
    display: grid;
    gap: 0.75rem;
}

.coordinator-item {
    display: flex;
    flex-direction: column;
    gap: 0.125rem;
    padding: 0.5rem;
    background: #f8fafc;
    border-radius: 0.375rem;
    border: 1px solid #e5e7eb;
}

.coordinator-item label {
    font-size: 0.625rem;
    font-weight: 600;
    color: #6b7280;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.coordinator-item div {
    color: #374151;
    font-weight: 500;
}

.no-coordinator {
    text-align: center;
    color: #6b7280;
    font-style: italic;
    padding: 1rem;
    background: #f8fafc;
    border-radius: 0.375rem;
    border: 1px solid #e5e7eb;
}

.notes-content {
    color: #374151;
    line-height: 1.6;
    white-space: pre-wrap;
}

.timestamps-section {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1rem;
    margin-top: 2rem;
    padding-top: 1.5rem;
    border-top: 1px solid #e5e7eb;
}

.timestamp-item {
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
}

.timestamp-item label {
    font-size: 0.75rem;
    font-weight: 600;
    color: #6b7280;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.timestamp-item div {
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
    
    .dates-grid {
        grid-template-columns: repeat(auto-fill, minmax(80px, 1fr));
    }
    
    .attendance-summary {
        grid-template-columns: 1fr;
    }
    
    .item-content > div {
        grid-template-columns: 1fr !important;
    }
}
</style>
@endsection