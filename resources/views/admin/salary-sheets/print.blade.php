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
            padding: 15px;
            margin-bottom: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        .job-info h3 {
            font-size: 16px;
            margin-bottom: 10px;
            color: #333;
        }

        .job-details {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 15px;
        }

        .job-detail {
            display: flex;
            flex-direction: column;
        }

        .job-detail label {
            font-weight: bold;
            font-size: 10px;
            color: #666;
            text-transform: uppercase;
            margin-bottom: 2px;
        }

        .job-detail span {
            font-size: 12px;
            color: #333;
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
        <!-- Header -->
        <div class="header">
            <h1>SALARY SHEET</h1>
            <h2>{{ $salarySheet->sheet_no }}</h2>
            <div class="header-info">
                <div>
                    <strong>Period:</strong> {{ $salarySheet->month_name }} {{ $salarySheet->year }}<br>
                    <strong>Location:</strong> {{ $salarySheet->location ?? 'N/A' }}
                </div>
                <div>
                    <strong>Created:</strong> {{ $salarySheet->created_at->format('M d, Y') }}<br>
                    <strong>Status:</strong> <span class="status">{{ strtoupper($salarySheet->status) }}</span>
                </div>
            </div>
        </div>

        <!-- Job Information -->
        @if($salarySheet->job)
        <div class="job-info">
            <h3>Job Information</h3>
            <div class="job-details">
                <div class="job-detail">
                    <label>Job Number</label>
                    <span>{{ $salarySheet->job->job_number ?? 'N/A' }}</span>
                </div>
                <div class="job-detail">
                    <label>Job Title</label>
                    <span>{{ $salarySheet->job->job_title ?? 'N/A' }}</span>
                </div>
                <div class="job-detail">
                    <label>Client</label>
                    <span>{{ $salarySheet->job->client->client_name ?? 'N/A' }}</span>
                </div>
                <div class="job-detail">
                    <label>Location</label>
                    <span>{{ $salarySheet->location ?? 'N/A' }}</span>
                </div>
            </div>
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
