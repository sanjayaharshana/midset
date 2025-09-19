<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Salary Slip - {{ $item->no }}</title>
    <style>
        @page {
            size: A4 portrait;
            margin: 0.5in;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', sans-serif;
            font-size: 14px;
            line-height: 1.6;
            color: #333;
            background: white;
        }

        .print-container {
            width: 100%;
            max-width: 100%;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 3px solid #333;
            padding-bottom: 20px;
        }

        .header h1 {
            font-size: 28px;
            font-weight: bold;
            margin-bottom: 10px;
            color: #1f2937;
        }

        .header h2 {
            font-size: 20px;
            color: #6b7280;
            margin-bottom: 15px;
        }

        .header-info {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 15px;
        }

        .header-info div {
            text-align: left;
        }

        .header-info .status {
            background: #f0f0f0;
            padding: 8px 15px;
            border-radius: 5px;
            font-weight: bold;
            font-size: 12px;
        }

        .status-paid {
            background: #d1fae5;
            color: #065f46;
        }

        .status-approved {
            background: #dbeafe;
            color: #1e40af;
        }

        .status-draft {
            background: #fef3c7;
            color: #92400e;
        }

        .employee-section {
            background: #f8fafc;
            padding: 20px;
            margin-bottom: 25px;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
        }

        .employee-section h3 {
            font-size: 18px;
            margin-bottom: 15px;
            color: #1f2937;
            border-bottom: 2px solid #e5e7eb;
            padding-bottom: 8px;
        }

        .employee-details {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }

        .detail-item {
            display: flex;
            flex-direction: column;
        }

        .detail-label {
            font-size: 12px;
            font-weight: 600;
            color: #6b7280;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: 5px;
        }

        .detail-value {
            font-size: 14px;
            font-weight: 500;
            color: #1f2937;
        }

        .salary-breakdown {
            background: white;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            margin-bottom: 25px;
            overflow: hidden;
        }

        .salary-breakdown h3 {
            background: #1f2937;
            color: white;
            padding: 15px 20px;
            margin: 0;
            font-size: 16px;
        }

        .breakdown-content {
            padding: 20px;
        }

        .breakdown-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 8px 0;
            border-bottom: 1px solid #f3f4f6;
        }

        .breakdown-row:last-child {
            border-bottom: none;
            border-top: 2px solid #1f2937;
            margin-top: 10px;
            padding-top: 15px;
            font-weight: bold;
            font-size: 16px;
        }

        .breakdown-label {
            font-size: 14px;
            color: #374151;
        }

        .breakdown-value {
            font-size: 14px;
            font-weight: 500;
            color: #1f2937;
        }

        .positive {
            color: #059669;
        }

        .negative {
            color: #dc2626;
        }

        .attendance-section {
            background: #f8fafc;
            padding: 20px;
            margin-bottom: 25px;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
        }

        .attendance-section h3 {
            font-size: 18px;
            margin-bottom: 15px;
            color: #1f2937;
            border-bottom: 2px solid #e5e7eb;
            padding-bottom: 8px;
        }

        .attendance-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
            gap: 10px;
            margin-bottom: 15px;
        }

        .attendance-day {
            text-align: center;
            padding: 10px;
            border-radius: 5px;
            font-weight: 500;
        }

        .attendance-day.present {
            background: #d1fae5;
            color: #065f46;
            border: 1px solid #a7f3d0;
        }

        .attendance-day.absent {
            background: #fee2e2;
            color: #991b1b;
            border: 1px solid #fecaca;
        }

        .attendance-summary {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            margin-top: 15px;
        }

        .summary-item {
            text-align: center;
            padding: 15px;
            background: white;
            border-radius: 5px;
            border: 1px solid #e5e7eb;
        }

        .summary-label {
            font-size: 12px;
            color: #6b7280;
            margin-bottom: 5px;
        }

        .summary-value {
            font-size: 18px;
            font-weight: bold;
            color: #1f2937;
        }

        .job-info {
            background: #f0f9ff;
            padding: 20px;
            margin-bottom: 25px;
            border: 1px solid #bae6fd;
            border-radius: 8px;
        }

        .job-info h3 {
            font-size: 18px;
            margin-bottom: 15px;
            color: #0c4a6e;
            border-bottom: 2px solid #bae6fd;
            padding-bottom: 8px;
        }

        .job-details {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }

        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e5e7eb;
            color: #6b7280;
            font-size: 12px;
        }

        .print-button {
            position: fixed;
            top: 20px;
            right: 20px;
            background: #3b82f6;
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 6px;
            cursor: pointer;
            font-weight: 500;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            z-index: 1000;
        }

        .print-button:hover {
            background: #2563eb;
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
            
            .print-button {
                display: none;
            }
            
            .employee-section,
            .salary-breakdown,
            .attendance-section,
            .job-info {
                page-break-inside: avoid;
            }
        }

        @media screen and (max-width: 768px) {
            .employee-details,
            .job-details,
            .attendance-summary {
                grid-template-columns: 1fr;
            }
            
            .attendance-grid {
                grid-template-columns: repeat(auto-fit, minmax(80px, 1fr));
            }
        }
    </style>
</head>
<body>
    <button class="print-button" onclick="window.print()">🖨️ Print Salary Slip</button>
    
    <div class="print-container">
        <!-- Header -->
        <div class="header">
            <h1>SALARY SLIP</h1>
            <h2>{{ $item->no }}</h2>
            <div class="header-info">
                <div>
                    <strong>Period:</strong> {{ $salarySheet->month_name }} {{ $salarySheet->year }}<br>
                    <strong>Location:</strong> {{ $item->location ?? 'N/A' }}
                </div>
                <div>
                    <strong>Generated:</strong> {{ now()->format('M d, Y \a\t g:i A') }}<br>
                    <strong>Status:</strong> <span class="status status-{{ $salarySheet->status }}">{{ strtoupper($salarySheet->status) }}</span>
                </div>
            </div>
        </div>

        <!-- Employee Information -->
        <div class="employee-section">
            <h3>Employee Information</h3>
            <div class="employee-details">
                <div class="detail-item">
                    <div class="detail-label">Employee Name</div>
                    <div class="detail-value">{{ $item->attendance_data['promoter_name'] ?? 'N/A' }}</div>
                </div>
                <div class="detail-item">
                    <div class="detail-label">Employee ID</div>
                    <div class="detail-value">{{ $item->attendance_data['promoter_id'] ?? 'N/A' }}</div>
                </div>
                <div class="detail-item">
                    <div class="detail-label">Position</div>
                    <div class="detail-value">{{ $item->position->position_name ?? 'N/A' }}</div>
                </div>
                <div class="detail-item">
                    <div class="detail-label">Identity Card No.</div>
                    <div class="detail-value">{{ $promoter->identity_card_no ?? 'N/A' }}</div>
                </div>
                <div class="detail-item">
                    <div class="detail-label">Phone Number</div>
                    <div class="detail-value">{{ $promoter->phone_no ?? 'N/A' }}</div>
                </div>
                <div class="detail-item">
                    <div class="detail-label">Bank Account</div>
                    <div class="detail-value">{{ $promoter->bank_account_number ?? 'N/A' }}</div>
                </div>
            </div>
        </div>

        <!-- Job Information -->
        @if($salarySheet->job)
        <div class="job-info">
            <h3>Job Information</h3>
            <div class="job-details">
                <div class="detail-item">
                    <div class="detail-label">Job Number</div>
                    <div class="detail-value">{{ $salarySheet->job->job_number ?? 'N/A' }}</div>
                </div>
                <div class="detail-item">
                    <div class="detail-label">Job Title</div>
                    <div class="detail-value">{{ $salarySheet->job->job_title ?? 'N/A' }}</div>
                </div>
                <div class="detail-item">
                    <div class="detail-label">Client</div>
                    <div class="detail-value">{{ $salarySheet->job->client->client_name ?? 'N/A' }}</div>
                </div>
                <div class="detail-item">
                    <div class="detail-label">Job Period</div>
                    <div class="detail-value">{{ $salarySheet->month_name }} {{ $salarySheet->year }}</div>
                </div>
            </div>
        </div>
        @endif

        <!-- Attendance Section -->
        <div class="attendance-section">
            <h3>Attendance Details</h3>
            
            @if(isset($item->attendance_data['attendance']) && is_array($item->attendance_data['attendance']))
                <div class="attendance-grid">
                    @foreach($item->attendance_data['attendance'] as $date => $value)
                        <div class="attendance-day {{ $value > 0 ? 'present' : 'absent' }}">
                            <div style="font-size: 12px; margin-bottom: 5px;">{{ \Carbon\Carbon::parse($date)->format('M d') }}</div>
                            <div style="font-size: 16px; font-weight: bold;">{{ $value > 0 ? 'P' : 'A' }}</div>
                        </div>
                    @endforeach
                </div>
            @endif

            <div class="attendance-summary">
                <div class="summary-item">
                    <div class="summary-label">Total Days Worked</div>
                    <div class="summary-value">{{ $item->attendance_data['total'] ?? 0 }} days</div>
                </div>
                <div class="summary-item">
                    <div class="summary-label">Attendance Amount</div>
                    <div class="summary-value positive">Rs. {{ number_format($item->attendance_data['amount'] ?? 0, 2) }}</div>
                </div>
            </div>
        </div>

        <!-- Salary Breakdown -->
        <div class="salary-breakdown">
            <h3>Salary Breakdown</h3>
            <div class="breakdown-content">
                <div class="breakdown-row">
                    <div class="breakdown-label">Basic Amount</div>
                    <div class="breakdown-value positive">Rs. {{ number_format($item->payment_data['amount'] ?? 0, 2) }}</div>
                </div>
                
                <div class="breakdown-row">
                    <div class="breakdown-label">Food Allowance</div>
                    <div class="breakdown-value positive">Rs. {{ number_format($item->payment_data['food_allowance'] ?? 0, 2) }}</div>
                </div>
                
                <div class="breakdown-row">
                    <div class="breakdown-label">Accommodation Allowance</div>
                    <div class="breakdown-value positive">Rs. {{ number_format($item->payment_data['accommodation_allowance'] ?? 0, 2) }}</div>
                </div>
                
                <div class="breakdown-row">
                    <div class="breakdown-label">Coordination Fee</div>
                    <div class="breakdown-value positive">Rs. {{ number_format($item->coordinator_details['amount'] ?? 0, 2) }}</div>
                </div>
                
                <div class="breakdown-row">
                    <div class="breakdown-label">Expenses</div>
                    <div class="breakdown-value negative">Rs. {{ number_format($item->payment_data['expenses'] ?? 0, 2) }}</div>
                </div>
                
                <div class="breakdown-row">
                    <div class="breakdown-label">Hold Amount (8 weeks)</div>
                    <div class="breakdown-value negative">Rs. {{ number_format($item->payment_data['hold_for_weeks'] ?? 0, 2) }}</div>
                </div>
                
                <div class="breakdown-row">
                    <div class="breakdown-label">NET AMOUNT</div>
                    <div class="breakdown-value">Rs. {{ number_format($item->payment_data['net_amount'] ?? 0, 2) }}</div>
                </div>
            </div>
        </div>

        <!-- Coordinator Information -->
        @if($item->coordinator_details && isset($item->coordinator_details['current_coordinator']))
        <div class="employee-section">
            <h3>Coordinator Information</h3>
            <div class="employee-details">
                <div class="detail-item">
                    <div class="detail-label">Coordinator Name</div>
                    <div class="detail-value">{{ $item->coordinator_details['current_coordinator'] ?? 'N/A' }}</div>
                </div>
                <div class="detail-item">
                    <div class="detail-label">Coordinator ID</div>
                    <div class="detail-value">{{ $item->coordinator_details['coordinator_id'] ?? 'N/A' }}</div>
                </div>
                <div class="detail-item">
                    <div class="detail-label">Coordination Fee</div>
                    <div class="detail-value positive">Rs. {{ number_format($item->coordinator_details['amount'] ?? 0, 2) }}</div>
                </div>
            </div>
        </div>
        @endif

        <!-- Footer -->
        <div class="footer">
            <p>This is a computer-generated salary slip. No signature required.</p>
            <p>Generated on {{ now()->format('F d, Y \a\t g:i A') }} | Salary Management System</p>
        </div>
    </div>

    <script>
        // Auto-print when page loads (optional)
        // window.onload = function() {
        //     window.print();
        // };
    </script>
</body>
</html>
