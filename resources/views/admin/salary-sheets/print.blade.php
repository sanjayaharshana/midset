<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Salary Sheet - {{ $salarySheet->sheet_no }}</title>
    <style>
        @page {
            size: A4 landscape;
            margin: 0.5in;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
            background: white;
        }

        .print-container {
            width: 100%;
            max-width: 100%;
        }

        /* Letterhead Styles */
        .letterhead {
            margin-bottom: 30px;
            border-bottom: 3px solid #2c3e50;
            padding-bottom: 20px;
        }

        .letterhead-content {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 20px;
        }

        .letterhead-left {
            flex-shrink: 0;
        }

        .company-logo {
            max-height: 80px;
            max-width: 120px;
            object-fit: contain;
        }

        .letterhead-right {
            flex: 1;
            text-align: right;
        }

        .company-name {
            font-size: 28px;
            font-weight: bold;
            color: #2c3e50;
            margin-bottom: 10px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .company-details {
            font-size: 11px;
            color: #555;
            line-height: 1.6;
        }

        .company-address {
            margin-bottom: 8px;
            font-weight: 500;
        }

        .company-contact {
            margin-bottom: 4px;
        }

        .contact-label {
            font-weight: 600;
            color: #2c3e50;
        }

        /* Responsive letterhead */
        @media print {
            .letterhead {
                page-break-inside: avoid;
            }
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #333;
            padding-bottom: 15px;
        }

        .header h1 {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .header h2 {
            font-size: 18px;
            color: #666;
            margin-bottom: 10px;
        }

        .header-info {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 10px;
        }

        .header-info div {
            text-align: left;
        }

        .header-info .status {
            background: #f0f0f0;
            padding: 5px 10px;
            border-radius: 3px;
            font-weight: bold;
        }

        .job-info {
            background: #f8f9fa;
            padding: 12px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        .job-info h3 {
            font-size: 14px;
            margin-bottom: 8px;
            color: #333;
            font-weight: bold;
        }

        .job-info-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 15px;
            margin-bottom: 8px;
        }

        .job-column {
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 10px;
            line-height: 1.3;
        }

        .info-row .label {
            font-weight: bold;
            color: #666;
            min-width: 50px;
            text-transform: uppercase;
        }

        .info-row .value {
            color: #333;
            text-align: right;
            flex: 1;
            margin-left: 8px;
            word-break: break-word;
        }

        .status-badge {
            display: inline-block;
            padding: 1px 4px;
            border-radius: 2px;
            font-size: 8px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .status-badge.pending {
            background: #fff3cd;
            color: #856404;
        }

        .status-badge.in_progress {
            background: #d1ecf1;
            color: #0c5460;
        }

        .status-badge.completed {
            background: #d4edda;
            color: #155724;
        }

        .status-badge.cancelled {
            background: #f8d7da;
            color: #721c24;
        }

        .additional-details {
            margin-top: 8px;
            padding-top: 8px;
            border-top: 1px solid #e0e0e0;
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
        }

        .detail-item {
            display: flex;
            align-items: center;
            font-size: 9px;
            line-height: 1.3;
        }

        .detail-item .label {
            font-weight: bold;
            color: #666;
            margin-right: 5px;
            text-transform: uppercase;
        }

        .detail-item .value {
            color: #333;
            word-break: break-word;
        }

        /* Responsive adjustments for print */
        @media print {
            .job-info {
                page-break-inside: avoid;
                margin-bottom: 10px;
            }
            
            .job-info-grid {
                gap: 10px;
            }
            
            .info-row {
                font-size: 9px;
            }
            
            .additional-details {
                gap: 15px;
            }
        }

        .summary-section {
            background: #e8f4fd;
            padding: 15px;
            margin-bottom: 20px;
            border: 1px solid #b3d9ff;
            border-radius: 5px;
        }

        .summary-section h3 {
            font-size: 16px;
            margin-bottom: 10px;
            color: #333;
        }

        .summary-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 15px;
        }

        .summary-item {
            text-align: center;
        }

        .summary-item label {
            display: block;
            font-weight: bold;
            font-size: 10px;
            color: #666;
            text-transform: uppercase;
            margin-bottom: 5px;
        }

        .summary-item .amount {
            font-size: 14px;
            font-weight: bold;
            color: #2563eb;
        }

        .summary-item .total-amount {
            font-size: 16px;
            font-weight: bold;
            color: #dc2626;
        }

        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .items-table th,
        .items-table td {
            border: 1px solid #333;
            padding: 8px;
            text-align: center;
            font-size: 10px;
        }

        .items-table th {
            background: #f0f0f0;
            font-weight: bold;
            text-transform: uppercase;
        }

        .items-table tbody tr:nth-child(even) {
            background: #f9f9f9;
        }

        .attendance-dates {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 2px;
            margin-bottom: 5px;
        }

        .attendance-date {
            padding: 2px;
            font-size: 8px;
            text-align: center;
            border: 1px solid #ddd;
            background: #f0f0f0;
        }

        .attendance-date.present {
            background: #d1fae5;
            border-color: #10b981;
        }

        .attendance-date.absent {
            background: #fee2e2;
            border-color: #ef4444;
        }

        .amount {
            font-weight: bold;
        }

        .amount.positive {
            color: #059669;
        }

        .amount.negative {
            color: #dc2626;
        }

        .notes-section {
            margin-top: 20px;
            padding: 15px;
            background: #f8f9fa;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        .notes-section h3 {
            font-size: 14px;
            margin-bottom: 10px;
            color: #333;
        }

        .notes-content {
            font-size: 11px;
            line-height: 1.5;
            white-space: pre-wrap;
        }

        .footer {
            margin-top: 30px;
            padding-top: 15px;
            border-top: 1px solid #ddd;
            font-size: 10px;
            color: #666;
            text-align: center;
        }

        @media print {
            body {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            
            .print-container {
                width: 100%;
                margin: 0;
                padding: 0;
            }
            
            .items-table {
                page-break-inside: avoid;
            }
            
            .items-table tbody tr {
                page-break-inside: avoid;
            }
        }
    </style>
</head>
<body>
    <div class="print-container">
        <!-- Letterhead -->
        <div class="letterhead">
            <div class="letterhead-content">
                <div class="letterhead-left">
                    @if(file_exists(public_path('logo.png')))
                        <img src="{{ asset('logo.png') }}" alt="Company Logo" class="company-logo">
                    @endif
                </div>
                <div class="letterhead-right">
                    <h1 class="company-name">{{ get_setting('company_name', __('common.company_name')) }}</h1>
                    <div class="company-details">
                        @if(get_setting('company_address'))
                            <div class="company-address">{{ get_setting('company_address') }}</div>
                        @endif
                        @if(get_setting('company_phone'))
                            <div class="company-contact">
                                <span class="contact-label">{{ __('common.phone') }}:</span> {{ get_setting('company_phone') }}
                            </div>
                        @endif
                        @if(get_setting('company_email'))
                            <div class="company-contact">
                                <span class="contact-label">{{ __('common.email') }}:</span> {{ get_setting('company_email') }}
                            </div>
                        @endif
                        @if(get_setting('company_website'))
                            <div class="company-contact">
                                <span class="contact-label">{{ __('common.website') }}:</span> {{ get_setting('company_website') }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Header -->
        <div class="header">
            <h1>{{ __('salary_sheets.salary_sheet') }}</h1>
            <h2>{{ $salarySheet->sheet_no }}</h2>
            <div class="header-info">
                <div>
                    <strong>{{ __('salary_sheets.period') }}:</strong> {{ $salarySheet->month_name }} {{ $salarySheet->year }}<br>
                    <strong>{{ __('salary_sheets.location') }}:</strong> {{ $salarySheet->location ?? 'N/A' }}
                </div>
                <div>
                    <strong>{{ __('salary_sheets.created') }}:</strong> {{ $salarySheet->created_at->format('M d, Y') }}<br>
                    <strong>{{ __('salary_sheets.status') }}:</strong> 
                    @php
                        $statusDisplay = strtoupper($salarySheet->status);
                        // For reporter role, show "PENDING APPROVAL" for "complete" status
                        if (auth()->check() && auth()->user()->hasRole('reporter') && $salarySheet->status === 'complete') {
                            $statusDisplay = 'PENDING APPROVAL';
                        }
                    @endphp
                    <span class="status">{{ $statusDisplay }}</span>
                </div>
            </div>
        </div>

        <!-- Job Information -->
        @if($salarySheet->job)
        <div class="job-info">
            <h3>Job Information</h3>
            <div class="job-info-grid">
                <!-- Left Column - Job Details -->
                <div class="job-column">
                    <div class="info-row">
                        <span class="label">Job Number:</span>
                        <span class="value">{{ $salarySheet->job->job_number ?? 'N/A' }}</span>
                    </div>
                    <div class="info-row">
                        <span class="label">Job Title:</span>
                        <span class="value">{{ $salarySheet->job->job_name ?? 'N/A' }}</span>
                    </div>
                    <div class="info-row">
                        <span class="label">Status:</span>
                        <span class="status-badge {{ $salarySheet->job->status }}">{{ strtoupper($salarySheet->job->status ?? 'N/A') }}</span>
                    </div>
                    <div class="info-row">
                        <span class="label">Period:</span>
                        <span class="value">
                            @if($salarySheet->job->start_date && $salarySheet->job->end_date)
                                {{ $salarySheet->job->start_date->format('M d') }} - {{ $salarySheet->job->end_date->format('M d, Y') }}
                            @else
                                N/A
                            @endif
                        </span>
                    </div>
                </div>
                
                <!-- Middle Column - Client Details -->
                <div class="job-column">
                    <div class="info-row">
                        <span class="label">Client:</span>
                        <span class="value">{{ $salarySheet->job->client->name ?? 'N/A' }}</span>
                    </div>
                    <div class="info-row">
                        <span class="label">Code:</span>
                        <span class="value">{{ $salarySheet->job->client->short_code ?? 'N/A' }}</span>
                    </div>
                    <div class="info-row">
                        <span class="label">Company:</span>
                        <span class="value">{{ $salarySheet->job->client->company_name ?? 'N/A' }}</span>
                    </div>
                    <div class="info-row">
                        <span class="label">Contact:</span>
                        <span class="value">{{ $salarySheet->job->client->contact_person ?? 'N/A' }}</span>
                    </div>
                </div>
                
                <!-- Right Column - Assignment & Contact -->
                <div class="job-column">
                    <div class="info-row">
                        <span class="label">Officer:</span>
                        <span class="value">{{ $salarySheet->job->officer->name ?? ($salarySheet->job->officer_name ?? 'N/A') }}</span>
                    </div>
                    <div class="info-row">
                        <span class="label">Reporter:</span>
                        <span class="value">{{ $salarySheet->job->reporter->name ?? ($salarySheet->job->reporter_officer_name ?? 'N/A') }}</span>
                    </div>
                    <div class="info-row">
                        <span class="label">Email:</span>
                        <span class="value">{{ $salarySheet->job->client->email ?? 'N/A' }}</span>
                    </div>
                    <div class="info-row">
                        <span class="label">Phone:</span>
                        <span class="value">{{ $salarySheet->job->client->phone ?? 'N/A' }}</span>
                    </div>
                </div>
            </div>
            
            <!-- Additional Details (if needed) -->
            @if($salarySheet->job->client->company_address || $salarySheet->job->client->bank_name)
            <div class="additional-details">
                @if($salarySheet->job->client->company_address)
                <div class="detail-item">
                    <span class="label">Address:</span>
                    <span class="value">{{ $salarySheet->job->client->company_address }}</span>
                </div>
                @endif
                @if($salarySheet->job->client->bank_name)
                <div class="detail-item">
                    <span class="label">Bank:</span>
                    <span class="value">{{ $salarySheet->job->client->bank_name }} - ****{{ substr($salarySheet->job->client->bank_account_number, -4) }}</span>
                </div>
                @endif
            </div>
            @endif
        </div>
        @endif

        <!-- Summary Section -->
        <div class="summary-section">
            <h3>Summary</h3>
            <div class="summary-grid">
                <div class="summary-item">
                    <label>Total Items</label>
                    <div>{{ $salarySheet->items->count() }}</div>
                </div>
                <div class="summary-item">
                    <label>Total Amount</label>
                    <div class="amount">Rs. {{ number_format($salarySheet->total_amount, 2) }}</div>
                </div>
                <div class="summary-item">
                    <label>Total Attendance Amount</label>
                    <div class="amount">Rs. {{ number_format($salarySheet->total_attendance_amount, 2) }}</div>
                </div>
                <div class="summary-item">
                    <label>Net Total</label>
                    <div class="amount total-amount">Rs. {{ number_format($salarySheet->total_amount, 2) }}</div>
                </div>
            </div>
        </div>

        <!-- Salary Sheet Items Table -->
        @if($salarySheet->items && $salarySheet->items->count() > 0)
        @php
            // Extract all unique attendance dates from all items
            $allAttendanceDates = [];
            foreach($salarySheet->items as $item) {
                if(isset($item->attendance_data['attendance']) && is_array($item->attendance_data['attendance'])) {
                    $dates = array_keys($item->attendance_data['attendance']);
                    $allAttendanceDates = array_merge($allAttendanceDates, $dates);
                }
            }
            $allAttendanceDates = array_unique($allAttendanceDates);
            sort($allAttendanceDates);
            $attendanceColumnsCount = count($allAttendanceDates);
            
            // Extract dynamic allowances from job
            $dynamicAllowances = [];
            if($salarySheet->job && isset($salarySheet->job->allowance) && is_array($salarySheet->job->allowance)) {
                $dynamicAllowances = $salarySheet->job->allowance;
            }
            $allowanceColumnsCount = count($dynamicAllowances);
        @endphp
        <table class="items-table">
            <thead>
                <tr>
                    <th rowspan="2">Item #</th>
                    <th rowspan="2">Location</th>
                    <th rowspan="2">Position</th>
                    <th rowspan="2">Promoter</th>
                    <th colspan="{{ $attendanceColumnsCount }}">Daily Attendance</th>
                    <th rowspan="2">Total Days</th>
                    <th rowspan="2">Attendance Amount</th>
                    <th rowspan="2">Base Amount</th>
                    @if($allowanceColumnsCount > 0)
                        <th colspan="{{ $allowanceColumnsCount }}">Dynamic Allowances</th>
                    @endif
                    <th rowspan="2">Expenses</th>
                    <th rowspan="2">Hold for Weeks</th>
                    <th rowspan="2">Net Amount</th>
                    <th rowspan="2">Coordinator</th>
                    <th rowspan="2">Coordination Fee</th>
                </tr>
                <tr>
                    @foreach($allAttendanceDates as $date)
                        <th>{{ \Carbon\Carbon::parse($date)->format('M d') }}</th>
                    @endforeach
                    @foreach($dynamicAllowances as $allowance)
                        <th>{{ $allowance['allowance_name'] ?? 'Allowance' }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach($salarySheet->items as $item)
                <tr>
                    <td><strong>{{ $item->no }}</strong></td>
                    <td>{{ $item->location ?? 'N/A' }}</td>
                    <td>{{ $item->position->position_name ?? 'N/A' }}</td>
                    <td>{{ $item->attendance_data['promoter_name'] ?? 'N/A' }}</td>
                    
                    <!-- Daily Attendance -->
                    @foreach($allAttendanceDates as $date)
                        @php
                            $attendanceValue = isset($item->attendance_data['attendance'][$date]) ? $item->attendance_data['attendance'][$date] : 0;
                        @endphp
                        <td class="attendance-date {{ $attendanceValue > 0 ? 'present' : 'absent' }}">
                            {{ $attendanceValue > 0 ? 'P' : 'A' }}
                        </td>
                    @endforeach
                    
                    <td><strong>{{ $item->attendance_data['total'] ?? 0 }}</strong></td>
                    <td class="amount positive">Rs. {{ number_format($item->attendance_data['amount'] ?? 0, 2) }}</td>
                    <td class="amount positive">Rs. {{ number_format($item->payment_data['amount'] ?? 0, 2) }}</td>
                    
                    <!-- Dynamic Allowances -->
                    @foreach($dynamicAllowances as $allowance)
                        @php
                            $allowanceName = $allowance['allowance_name'] ?? '';
                            $allowanceValue = 0;
                            if(isset($item->allowances_data) && is_array($item->allowances_data) && isset($item->allowances_data[$allowanceName])) {
                                $allowanceValue = $item->allowances_data[$allowanceName];
                            }
                        @endphp
                        <td class="amount positive">Rs. {{ number_format($allowanceValue, 2) }}</td>
                    @endforeach
                    
                    <td class="amount negative">Rs. {{ number_format($item->payment_data['expenses'] ?? 0, 2) }}</td>
                    <td class="amount negative">Rs. {{ number_format($item->payment_data['hold_for_weeks'] ?? 0, 2) }}</td>
                    <td class="amount"><strong>Rs. {{ number_format($item->payment_data['net_amount'] ?? 0, 2) }}</strong></td>
                    <td>{{ $item->coordinator_details['current_coordinator'] ?? 'N/A' }}</td>
                    <td class="amount positive">Rs. {{ number_format($item->coordinator_details['amount'] ?? 0, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @endif

        <!-- Notes Section -->
        @if($salarySheet->notes)
        <div class="notes-section">
            <h3>Notes</h3>
            <div class="notes-content">{{ $salarySheet->notes }}</div>
        </div>
        @endif

        <!-- Footer -->
        <div class="footer">
            <p>Generated on {{ now()->format('F d, Y \a\t g:i A') }} | Salary Sheet Management System</p>
        </div>
    </div>

    <script>
        // Auto-print when page loads
        window.onload = function() {
            window.print();
        };
    </script>
</body>
</html>
