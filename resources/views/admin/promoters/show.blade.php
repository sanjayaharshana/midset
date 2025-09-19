@extends('layouts.admin')

@section('title', 'Promoter Details')
@section('page-title', 'Promoter Details')

@section('breadcrumbs')
    <span class="breadcrumb-separator">›</span>
    <a href="{{ route('admin.promoters.index') }}" class="breadcrumb-item">Promoters</a>
    <span class="breadcrumb-separator">›</span>
    <span class="breadcrumb-item active">Details</span>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <div>
                <h3>{{ $promoter->promoter_name }}</h3>
                <p style="margin: 0.25rem 0 0 0; color: #6b7280;">
                    <span class="status-badge status-{{ $promoter->status }}">
                        {{ ucfirst($promoter->status) }}
                    </span>
                    <span style="margin-left: 1rem; font-family: monospace; background: #1f2937; color: white; padding: 0.25rem 0.5rem; border-radius: 0.25rem; font-size: 0.8rem;">
                        {{ $promoter->promoter_id }}
                    </span>
                </p>
            </div>
            <div style="display: flex; gap: 0.5rem;">
                @can('edit promoters')
                    <a href="{{ route('admin.promoters.edit', $promoter) }}" class="btn btn-warning">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 8px;">
                            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                        </svg>
                        Edit Promoter
                    </a>
                @endcan
                <a href="{{ route('admin.promoters.index') }}" class="btn btn-secondary">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 8px;">
                        <path d="M19 12H5M12 19l-7-7 7-7"></path>
                    </svg>
                    Back to List
                </a>
            </div>
        </div>
    </div>
    <div class="card-body">
        <!-- Tab Navigation -->
        <div class="tab-navigation">
            <button class="tab-button active" onclick="openTab(event, 'promoter-details')">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 8px;">
                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                    <circle cx="12" cy="7" r="4"></circle>
                </svg>
                Promoter Details
            </button>
            <button class="tab-button" onclick="openTab(event, 'earnings-summary')">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 8px;">
                    <line x1="12" y1="1" x2="12" y2="23"></line>
                    <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
                </svg>
                Earnings Summary
            </button>
            <button class="tab-button" onclick="openTab(event, 'salary-history')">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 8px;">
                    <rect x="2" y="3" width="20" height="14" rx="2" ry="2"></rect>
                    <line x1="8" y1="21" x2="16" y2="21"></line>
                    <line x1="12" y1="17" x2="12" y2="21"></line>
                </svg>
                Salary History
            </button>
        </div>

        <!-- Tab Content -->
        <div id="promoter-details" class="tab-content active">
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem;">
            <!-- Promoter Basic Information -->
            <div>
                <div style="display: flex; align-items: center; margin-bottom: 2rem;">
                    <div style="width: 80px; height: 80px; background: linear-gradient(135deg, #1f2937 0%, #374151 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin-right: 1rem;">
                        <span style="color: white; font-weight: bold; font-size: 14px; font-family: monospace;">
                            {{ substr($promoter->promoter_id, -4) }}
                        </span>
                    </div>
                    <div>
                        <h2 style="margin: 0; color: #1f2937;">{{ $promoter->promoter_name }}</h2>
                        <p style="margin: 0.25rem 0 0 0; color: #6b7280;">
                            <span class="status-badge status-{{ $promoter->status }}">
                                {{ ucfirst($promoter->status) }}
                            </span>
                        </p>
                    </div>
                </div>

                <div style="background: #f8fafc; padding: 1.5rem; border-radius: 0.5rem; margin-bottom: 1.5rem;">
                    <h4 style="margin-bottom: 1rem; color: #374151;">Personal Information</h4>
                    
                    <div style="display: grid; gap: 1rem;">
                        <div class="info-item">
                            <label>Promoter ID</label>
                            <div>
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 8px; color: #6b7280;">
                                    <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                                    <line x1="9" y1="9" x2="15" y2="15"></line>
                                    <line x1="15" y1="9" x2="9" y2="15"></line>
                                </svg>
                                <span style="font-family: monospace; font-weight: bold; background: #1f2937; color: white; padding: 0.25rem 0.5rem; border-radius: 0.25rem;">
                                    {{ $promoter->promoter_id }}
                                </span>
                            </div>
                        </div>

                        <div class="info-item">
                            <label>Position</label>
                            <div>
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 8px; color: #6b7280;">
                                    <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                                    <circle cx="9" cy="9" r="2"></circle>
                                    <path d="M21 15.5V19a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-3.5"></path>
                                </svg>
                                @if($promoter->position)
                                    <span style="background: #e0f2fe; color: #0277bd; padding: 0.25rem 0.5rem; border-radius: 0.25rem; font-weight: 500;">
                                        {{ $promoter->position->position_name }}
                                    </span>
                                @else
                                    <span style="color: #9ca3af; font-style: italic;">No position assigned</span>
                                @endif
                            </div>
                        </div>

                        <div class="info-item">
                            <label>Identity Card No.</label>
                            <div>
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 8px; color: #6b7280;">
                                    <rect x="2" y="3" width="20" height="14" rx="2" ry="2"></rect>
                                    <line x1="8" y1="21" x2="16" y2="21"></line>
                                    <line x1="12" y1="17" x2="12" y2="21"></line>
                                </svg>
                                <span style="font-family: monospace;">{{ $promoter->identity_card_no }}</span>
                            </div>
                        </div>

                        <div class="info-item">
                            <label>Phone Number</label>
                            <div>
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 8px; color: #6b7280;">
                                    <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path>
                                </svg>
                                {{ $promoter->phone_no }}
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
                                {{ $promoter->bank_name }}
                            </div>
                        </div>

                        <div class="info-item">
                            <label>Bank Branch</label>
                            <div>
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 8px; color: #6b7280;">
                                    <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                                    <polyline points="9,22 9,12 15,12 15,22"></polyline>
                                </svg>
                                {{ $promoter->bank_branch_name }}
                            </div>
                        </div>

                        <div class="info-item">
                            <label>Account Number</label>
                            <div>
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 8px; color: #6b7280;">
                                    <rect x="1" y="4" width="22" height="16" rx="2" ry="2"></rect>
                                    <line x1="1" y1="10" x2="23" y2="10"></line>
                                </svg>
                                <span style="font-family: monospace;">{{ $promoter->bank_account_number }}</span>
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
                                {{ $promoter->created_at->format('F d, Y \a\t g:i A') }}
                            </div>
                        </div>

                        <div class="info-item">
                            <label>Last Updated</label>
                            <div>
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 8px; color: #6b7280;">
                                    <circle cx="12" cy="12" r="10"></circle>
                                    <polyline points="12,6 12,12 16,14"></polyline>
                                </svg>
                                {{ $promoter->updated_at->format('F d, Y \a\t g:i A') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </div>

        <!-- Earnings Summary Tab -->
        <div id="earnings-summary" class="tab-content">
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1.5rem; margin-bottom: 2rem;">
            <!-- Total Earnings Card -->
            <div style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white; padding: 1.5rem; border-radius: 0.75rem; text-align: center;">
                <div style="font-size: 2rem; font-weight: bold; margin-bottom: 0.5rem;">
                    Rs. {{ number_format($earningsSummary['total_net_amount'], 2) }}
                </div>
                <div style="font-size: 0.875rem; opacity: 0.9;">Total Net Earnings</div>
            </div>

            <!-- Total Attendance Amount Card -->
            <div style="background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%); color: white; padding: 1.5rem; border-radius: 0.75rem; text-align: center;">
                <div style="font-size: 2rem; font-weight: bold; margin-bottom: 0.5rem;">
                    Rs. {{ number_format($earningsSummary['total_attendance_amount'], 2) }}
                </div>
                <div style="font-size: 0.875rem; opacity: 0.9;">Total Attendance Amount</div>
            </div>

            <!-- Total Salary Sheets Card -->
            <div style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); color: white; padding: 1.5rem; border-radius: 0.75rem; text-align: center;">
                <div style="font-size: 2rem; font-weight: bold; margin-bottom: 0.5rem;">
                    {{ $earningsSummary['total_salary_sheets'] }}
                </div>
                <div style="font-size: 0.875rem; opacity: 0.9;">Total Salary Sheets</div>
            </div>

            <!-- Total Attendance Days Card -->
            <div style="background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%); color: white; padding: 1.5rem; border-radius: 0.75rem; text-align: center;">
                <div style="font-size: 2rem; font-weight: bold; margin-bottom: 0.5rem;">
                    {{ $earningsSummary['total_attendance_days'] }}
                </div>
                <div style="font-size: 0.875rem; opacity: 0.9;">Total Attendance Days</div>
            </div>
        </div>

        <!-- Detailed Breakdown -->
        <div style="background: #f8fafc; padding: 1.5rem; border-radius: 0.75rem; margin-bottom: 2rem;">
            <h4 style="margin-bottom: 1rem; color: #374151;">Earnings Breakdown</h4>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 1rem;">
                <div class="earnings-item">
                    <div style="font-size: 0.875rem; color: #6b7280; margin-bottom: 0.25rem;">Basic Amount</div>
                    <div style="font-weight: 600; color: #059669;">Rs. {{ number_format($earningsSummary['total_basic_amount'], 2) }}</div>
                </div>
                <div class="earnings-item">
                    <div style="font-size: 0.875rem; color: #6b7280; margin-bottom: 0.25rem;">Food Allowance</div>
                    <div style="font-weight: 600; color: #059669;">Rs. {{ number_format($earningsSummary['total_food_allowance'], 2) }}</div>
                </div>
                <div class="earnings-item">
                    <div style="font-size: 0.875rem; color: #6b7280; margin-bottom: 0.25rem;">Accommodation Allowance</div>
                    <div style="font-weight: 600; color: #059669;">Rs. {{ number_format($earningsSummary['total_accommodation_allowance'], 2) }}</div>
                </div>
                <div class="earnings-item">
                    <div style="font-size: 0.875rem; color: #6b7280; margin-bottom: 0.25rem;">Hold Amount</div>
                    <div style="font-weight: 600; color: #f59e0b;">Rs. {{ number_format($earningsSummary['total_hold_amount'], 2) }}</div>
                </div>
                <div class="earnings-item">
                    <div style="font-size: 0.875rem; color: #6b7280; margin-bottom: 0.25rem;">Expenses</div>
                    <div style="font-weight: 600; color: #dc2626;">Rs. {{ number_format($earningsSummary['total_expenses'], 2) }}</div>
                </div>
                <div class="earnings-item">
                    <div style="font-size: 0.875rem; color: #6b7280; margin-bottom: 0.25rem;">Coordination Fee</div>
                    <div style="font-weight: 600; color: #dc2626;">Rs. {{ number_format($earningsSummary['total_coordination_fee'], 2) }}</div>
                </div>
            </div>
        </div>

        <!-- Additional Statistics -->
        <div style="background: #f8fafc; padding: 1.5rem; border-radius: 0.75rem; margin-bottom: 2rem;">
            <h4 style="margin-bottom: 1rem; color: #374151;">Additional Statistics</h4>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 1rem;">
                <div class="earnings-item">
                    <div style="font-size: 0.875rem; color: #6b7280; margin-bottom: 0.25rem;">Unique Jobs</div>
                    <div style="font-weight: 600; color: #3b82f6;">{{ $earningsSummary['unique_jobs'] }}</div>
                </div>
                <div class="earnings-item">
                    <div style="font-size: 0.875rem; color: #6b7280; margin-bottom: 0.25rem;">Unique Positions</div>
                    <div style="font-weight: 600; color: #3b82f6;">{{ $earningsSummary['unique_positions'] }}</div>
                </div>
                <div class="earnings-item">
                    <div style="font-size: 0.875rem; color: #6b7280; margin-bottom: 0.25rem;">Avg. per Day</div>
                    <div style="font-weight: 600; color: #10b981;">Rs. {{ number_format($earningsSummary['average_earnings_per_day'], 2) }}</div>
                </div>
                <div class="earnings-item">
                    <div style="font-size: 0.875rem; color: #6b7280; margin-bottom: 0.25rem;">Avg. per Sheet</div>
                    <div style="font-weight: 600; color: #10b981;">Rs. {{ number_format($earningsSummary['average_earnings_per_sheet'], 2) }}</div>
                </div>
            </div>
        </div>

        <!-- Salary Sheet Status Summary -->
        <div style="background: #f8fafc; padding: 1.5rem; border-radius: 0.75rem; margin-bottom: 2rem;">
            <h4 style="margin-bottom: 1rem; color: #374151;">Salary Sheet Status Summary</h4>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(120px, 1fr)); gap: 1rem;">
                <div class="earnings-item">
                    <div style="font-size: 0.875rem; color: #6b7280; margin-bottom: 0.25rem;">Paid</div>
                    <div style="font-weight: 600; color: #059669;">{{ $earningsSummary['total_salary_sheets_paid'] }}</div>
                </div>
                <div class="earnings-item">
                    <div style="font-size: 0.875rem; color: #6b7280; margin-bottom: 0.25rem;">Approved</div>
                    <div style="font-weight: 600; color: #3b82f6;">{{ $earningsSummary['total_salary_sheets_approved'] }}</div>
                </div>
                <div class="earnings-item">
                    <div style="font-size: 0.875rem; color: #6b7280; margin-bottom: 0.25rem;">Draft</div>
                    <div style="font-weight: 600; color: #f59e0b;">{{ $earningsSummary['total_salary_sheets_draft'] }}</div>
                </div>
            </div>
        </div>
        </div>

        <!-- Salary History Tab -->
        <div id="salary-history" class="tab-content">
        @if($salarySheetItems->count() > 0)
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Sheet No.</th>
                            <th>Job</th>
                            <th>Client</th>
                            <th>Position</th>
                            <th>Attendance Days</th>
                            <th>Attendance Amount</th>
                            <th>Net Amount</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($salarySheetItems as $item)
                        <tr>
                            <td>
                                <span style="background: #1f2937; color: white; padding: 0.25rem 0.5rem; border-radius: 0.25rem; font-weight: bold; font-size: 0.8rem; font-family: monospace;">
                                    {{ $item->salarySheet->sheet_no }}
                                </span>
                            </td>
                            <td>
                                <div>
                                    <div style="font-weight: 500;">{{ $item->job->job_number ?? 'N/A' }}</div>
                                    <div style="color: #6b7280; font-size: 0.8rem;">{{ $item->job->job_title ?? 'N/A' }}</div>
                                </div>
                            </td>
                            <td>
                                <div style="color: #6b7280; font-size: 0.9rem;">
                                    {{ $item->job->client->client_name ?? 'N/A' }}
                                </div>
                            </td>
                            <td>
                                <span style="background: #e0f2fe; color: #0277bd; padding: 0.25rem 0.5rem; border-radius: 0.25rem; font-size: 0.8rem; font-weight: 500;">
                                    {{ $item->position->position_name ?? 'N/A' }}
                                </span>
                            </td>
                            <td>
                                <span style="background: #fef3c7; color: #92400e; padding: 0.25rem 0.5rem; border-radius: 0.25rem; font-weight: bold; font-size: 0.8rem;">
                                    {{ $item->attendance_data['total'] ?? 0 }} days
                                </span>
                            </td>
                            <td>
                                <span style="font-weight: 600; color: #059669;">
                                    Rs. {{ number_format($item->attendance_data['amount'] ?? 0, 2) }}
                                </span>
                            </td>
                            <td>
                                <span style="font-weight: 600; color: #10b981;">
                                    Rs. {{ number_format($item->payment_data['net_amount'] ?? 0, 2) }}
                                </span>
                            </td>
                            <td>
                                <span class="status-badge status-{{ $item->salarySheet->status }}">
                                    {{ ucfirst($item->salarySheet->status) }}
                                </span>
                            </td>
                            <td>{{ $item->salarySheet->created_at->format('M d, Y') }}</td>
                            <td>
                                <div style="display: flex; gap: 8px;">
                                    @can('view salary sheets')
                                        <a href="{{ route('admin.salary-sheets.show', $item->salarySheet) }}" class="btn btn-sm btn-info" title="View Salary Sheet">
                                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                                <circle cx="12" cy="12" r="3"></circle>
                                            </svg>
                                        </a>
                                    @endcan
                                    @can('view salary sheets')
                                        <a href="{{ route('admin.salary-sheets.print', $item->salarySheet) }}" target="_blank" class="btn btn-sm btn-primary" title="Print Full Salary Sheet">
                                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <polyline points="6,9 6,2 18,2 18,9"></polyline>
                                                <path d="M6,18H4a2,2 0 0,1 -2,-2V11a2,2 0 0,1 2,-2H20a2,2 0 0,1 2,2v5a2,2 0 0,1 -2,2H18"></path>
                                                <polyline points="6,14 18,14 18,22 6,22 6,14"></polyline>
                                            </svg>
                                        </a>
                                    @endcan
                                    @can('view salary sheets')
                                        <a href="{{ route('admin.promoters.salary-slip.print', ['promoter' => $promoter->id, 'itemId' => $item->id]) }}" target="_blank" class="btn btn-sm btn-success" title="Print Individual Salary Slip">
                                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path>
                                                <line x1="3" y1="6" x2="21" y2="6"></line>
                                                <path d="M16 10a4 4 0 0 1-8 0"></path>
                                            </svg>
                                        </a>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div style="text-align: center; padding: 2rem;">
                <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="#d1d5db" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" style="margin-bottom: 1rem;">
                    <rect x="2" y="3" width="20" height="14" rx="2" ry="2"></rect>
                    <line x1="8" y1="21" x2="16" y2="21"></line>
                    <line x1="12" y1="17" x2="12" y2="21"></line>
                </svg>
                <h3 style="color: #6b7280; margin-bottom: 0.5rem;">No salary sheets found</h3>
                <p style="color: #9ca3af;">This promoter hasn't been assigned to any salary sheets yet.</p>
            </div>
        @endif
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

/* Tab Navigation Styles */
.tab-navigation {
    display: flex;
    border-bottom: 2px solid #e5e7eb;
    margin-bottom: 2rem;
    background: #f9fafb;
    border-radius: 0.5rem 0.5rem 0 0;
    padding: 0.5rem 0.5rem 0 0.5rem;
}

.tab-button {
    background: none;
    border: none;
    padding: 1rem 1.5rem;
    cursor: pointer;
    font-size: 0.875rem;
    font-weight: 500;
    color: #6b7280;
    border-radius: 0.5rem 0.5rem 0 0;
    display: flex;
    align-items: center;
    transition: all 0.2s ease;
    margin-right: 0.25rem;
}

.tab-button:hover {
    background: #f3f4f6;
    color: #374151;
}

.tab-button.active {
    background: white;
    color: #1f2937;
    border-bottom: 2px solid white;
    margin-bottom: -2px;
    box-shadow: 0 -2px 4px rgba(0, 0, 0, 0.1);
}

.tab-content {
    display: none;
    animation: fadeIn 0.3s ease-in-out;
}

.tab-content.active {
    display: block;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
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

.earnings-item {
    text-align: center;
    padding: 1rem;
    background: white;
    border-radius: 0.5rem;
    border: 1px solid #e5e7eb;
}

.table-responsive {
    overflow-x: auto;
    margin-top: 1rem;
}

.table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 1rem;
    background: white;
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
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
    border-bottom: 2px solid #e5e7eb;
}

.table tbody tr:hover {
    background-color: #f9fafb;
}

.table tbody tr:last-child td {
    border-bottom: none;
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

.btn-success {
    background-color: #10b981;
    color: white;
    border: none;
}

.btn-info:hover,
.btn-primary:hover,
.btn-success:hover {
    opacity: 0.9;
    transform: translateY(-1px);
}

@media (max-width: 768px) {
    div[style*="grid-template-columns"] {
        grid-template-columns: 1fr !important;
    }
    
    .table-responsive {
        font-size: 0.875rem;
    }
    
    .table th,
    .table td {
        padding: 0.5rem;
    }
}
</style>

<script>
function openTab(evt, tabName) {
    // Hide all tab contents
    var tabContents = document.getElementsByClassName("tab-content");
    for (var i = 0; i < tabContents.length; i++) {
        tabContents[i].classList.remove("active");
    }
    
    // Remove active class from all tab buttons
    var tabButtons = document.getElementsByClassName("tab-button");
    for (var i = 0; i < tabButtons.length; i++) {
        tabButtons[i].classList.remove("active");
    }
    
    // Show the selected tab content and mark button as active
    document.getElementById(tabName).classList.add("active");
    evt.currentTarget.classList.add("active");
}

// Initialize tabs on page load
document.addEventListener('DOMContentLoaded', function() {
    // Set the first tab as active by default
    document.getElementById('promoter-details').classList.add('active');
    document.querySelector('.tab-button').classList.add('active');
});
</script>
@endsection
