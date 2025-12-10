@extends('layouts.admin')

@section('title', 'Edit Salary Sheet')
@section('page-title', 'Edit Salary Sheet')

@section('breadcrumbs')
    <span class="breadcrumb-separator">›</span>
    <a href="{{ route('admin.salary-sheets.index') }}" class="breadcrumb-item">Salary Sheets</a>
    <span class="breadcrumb-separator">›</span>
    <span class="breadcrumb-item active">Edit</span>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <h3>Edit Salary Sheet - {{ $salarySheet->sheet_number }}</h3>
            <div style="display: flex; gap: 1rem;">
                <button type="button" id="addPromoterBtn" class="btn btn-success" onclick="addPromoterRow()" disabled>
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 8px;">
                        <line x1="12" y1="5" x2="12" y2="19"></line>
                        <line x1="5" y1="12" x2="19" y2="12"></line>
                    </svg>
                    Add Promoter Row
                </button>
                <button type="button" id="salaryRuleBtn" class="btn btn-info" onclick="openSalaryRuleModal()" disabled>
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 8px;">
                        <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                        <line x1="9" y1="9" x2="15" y2="15"></line>
                        <line x1="15" y1="9" x2="9" y2="15"></line>
                    </svg>
                    Position Wise Salary Rule
                </button>
                <button type="button" class="btn btn-info" onclick="openJobSettingsModal()">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 8px;">
                        <circle cx="12" cy="12" r="3"></circle>
                        <path d="M12 1v6m0 6v6m11-7h-6m-6 0H1"></path>
                    </svg>
                    Job Settings
                </button>
                <button type="button" class="btn btn-primary" onclick="updateSalarySheet()">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 8px;">
                        <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path>
                        <polyline points="17,21 17,13 7,13 7,21"></polyline>
                        <polyline points="7,3 7,8 15,8"></polyline>
                    </svg>
                    Update Salary Sheet
                </button>
                <a href="{{ route('admin.salary-sheets.index') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </div>
    </div>
    <div class="card-body">
        <form id="salarySheetForm" action="{{ route('admin.salary-sheets.update', $salarySheet) }}" method="POST">
            @csrf
            @method('PUT')
            
            <!-- Basic Information -->
            <div style="background: #f8fafc; padding: 1rem; border-radius: 0.5rem; margin-bottom: 1rem;">
                <div style="display: grid; grid-template-columns: 1fr 1fr 1fr 1fr; gap: 1rem;">
                    <div>
                        <label style="font-size: 0.75rem; color: #6b7280; font-weight: 600;">SHEET NO</label>
                        <input type="text" class="form-control" value="{{ $salarySheet->sheet_number }}" readonly style="background: #f9fafb; font-weight: bold;">
                    </div>
                    <div>
                        <label style="font-size: 0.75rem; color: #6b7280; font-weight: 600;">JOB ID</label>
                        <select class="form-control" id="job_id" name="job_id" onchange="updateAttendanceDates()">
                            <option value="">Select Job</option>
                            @foreach($jobs as $job)
                                <option value="{{ $job->id }}" 
                                        data-start-date="{{ $job->start_date }}" 
                                        data-end-date="{{ $job->end_date }}"
                                        {{ $job->id == $salarySheet->job_id ? 'selected' : '' }}>{{ $job->job_number }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label style="font-size: 0.75rem; color: #6b7280; font-weight: 600;">STATUS</label>
                        <select class="form-control" name="status" required>
                            <option value="">Select Status</option>
                            <option value="draft" {{ $salarySheet->status == 'draft' ? 'selected' : '' }}>Draft</option>
                            <option value="complete" {{ $salarySheet->status == 'complete' ? 'selected' : '' }}>Complete</option>
                            <option value="reject" {{ $salarySheet->status == 'reject' ? 'selected' : '' }}>Reject</option>
                            <option value="approve" {{ $salarySheet->status == 'approve' ? 'selected' : '' }}>Approve</option>
                            <option value="paid" {{ $salarySheet->status == 'paid' ? 'selected' : '' }}>Paid</option>
                        </select>
                    </div>
                    <div>
                        <label style="font-size: 0.75rem; color: #6b7280; font-weight: 600;">LOCATION</label>
                        <input type="text" class="form-control" name="location" value="{{ $salarySheet->location }}" placeholder="Enter location">
                    </div>
                </div>
            </div>

            <!-- Salary Sheet Table Container -->
            <div id="salaryTableContainer" style="display: block;">
                <!-- Scroll Navigation Panel -->
                <div class="scroll-navigation">
                    <div style="display: flex; align-items: center; gap: 1rem;">
                        <button type="button" id="scrollLeftBtn" class="btn btn-sm btn-outline-secondary" title="Scroll Left">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="15,18 9,12 15,6"></polyline>
                            </svg>
                        </button>
                        <button type="button" id="scrollToStartBtn" class="btn btn-sm btn-outline-secondary" title="Scroll to Start">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="11,17 6,12 11,7"></polyline>
                                <polyline points="18,17 13,12 18,7"></polyline>
                            </svg>
                        </button>
                        <div style="display: flex; align-items: center; gap: 0.5rem;">
                            <span id="scrollPosition" style="font-size: 0.8rem; color: #6b7280;">0%</span>
                            <div style="width: 100px; height: 4px; background: #e5e7eb; border-radius: 2px; overflow: hidden;">
                                <div id="scrollProgressBar" style="height: 100%; background: #3b82f6; transition: width 0.3s ease; width: 0%;"></div>
                            </div>
                            <span id="scrollInfo" style="font-size: 0.8rem; color: #6b7280;">No scroll needed</span>
                        </div>
                        <button type="button" id="scrollToEndBtn" class="btn btn-sm btn-outline-secondary" title="Scroll to End">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="13,17 18,12 13,7"></polyline>
                                <polyline points="6,17 11,12 6,7"></polyline>
                            </svg>
                        </button>
                        <button type="button" id="scrollRightBtn" class="btn btn-sm btn-outline-secondary" title="Scroll Right">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="9,18 15,12 9,6"></polyline>
                            </svg>
                        </button>
                        <span id="scrollInfo" title="Use mouse drag, touch swipe, arrow keys, or buttons to scroll horizontally">Scroll to navigate</span>
                    </div>
                </div>
                
                <!-- Table Scroll Container -->
                <div class="table-scroll-container" id="tableScrollContainer">
                    <table class="salary-sheet-table" id="salaryTable">
                    <thead>
                        <tr>
                            <th style="width: 60px;">No</th>
                            <th style="width: 150px;">Location</th>
                            <th style="width: 400px;">Promoter Details</th>
                            <th style="width: 700px;">Attendance</th>
                            <th style="width: 600px;">Payments</th>
                            <th style="width: 400px;">Coordinator Details</th>
                            <th style="width: 60px;">Action</th>
                        </tr>
                        <tr class="sub-header">
                            <th></th>
                            <th></th>
                            <th>
                                <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 1rem;width: 700px;">
                                    <div style="text-align: center; font-size: 0.7rem;">Promoter ID</div>
                                    <div style="text-align: center; font-size: 0.7rem;">Promoter Name</div>
                                    <div style="text-align: center; font-size: 0.7rem;">Position</div>
                                </div>
                            </th>
                            <th id="attendanceColumn" style="display: none;">
                                <div id="attendanceHeaders" style="display: grid;grid-template-columns: repeat(6, 1fr) 1fr 1.5fr;gap: 0.75rem;width: 839px;">
                                    <div style="text-align: center; font-size: 0.7rem;">Select Job First</div>
                                    <div style="text-align: center; font-size: 0.7rem;">Select Job First</div>
                                    <div style="text-align: center; font-size: 0.7rem;">Select Job First</div>
                                    <div style="text-align: center; font-size: 0.7rem;">Select Job First</div>
                                    <div style="text-align: center; font-size: 0.7rem;">Select Job First</div>
                                    <div style="text-align: center; font-size: 0.7rem;">Select Job First</div>
                                    <div style="text-align: center; font-size: 0.7rem;">Total</div>
                                    <div style="text-align: center; font-size: 0.7rem;">Amount</div>
                                </div>
                            </th>
                            <th>
                                <div style="display: grid; grid-template-columns: repeat(6, 1fr); gap: 0.75rem;width: 799px;">
                                    <div style="text-align: center; font-size: 0.7rem;">Amount</div>
                                    <div style="text-align: center; font-size: 0.7rem;">Food Allowance</div>
                                    <div style="text-align: center; font-size: 0.7rem;">Expenses</div>
                                    <div style="text-align: center; font-size: 0.7rem;">Accommodation Allowance</div>
                                    <div style="text-align: center; font-size: 0.7rem;">Hold For 8 weeks</div>
                                    <div style="text-align: center; font-size: 0.7rem;">Net Amount</div>
                                </div>
                            </th>
                            <th>
                                <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 1rem;width: 500px;">
                                    <div style="text-align: center; font-size: 0.7rem;">Coordinator ID</div>
                                    <div style="text-align: center; font-size: 0.7rem;">Current Coordinator</div>
                                    <div style="text-align: center; font-size: 0.7rem;">Coordination Fee</div>
                                </div>
                            </th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody id="promoterRows">
                        <!-- Rows will be loaded dynamically -->
                    </tbody>
                </table>
                </div>
            </div>

            <!-- Grand Total -->
            <div style="background: #f0f9ff; padding: 1rem; border-radius: 0.5rem; margin-top: 1rem; border-left: 4px solid #3b82f6;">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <h4 style="color: #1e40af; margin: 0;">Grand Total</h4>
                    <div style="display: flex; gap: 2rem;">
                        <div style="text-align: center;">
                            <div style="font-size: 0.8rem; color: #6b7280;">Total Amount</div>
                            <div id="grandTotalAmount" style="font-size: 1.2rem; font-weight: 600; color: #1e40af;">Rs. 0.00</div>
                        </div>
                        <div style="text-align: center;">
                            <div style="font-size: 0.8rem; color: #6b7280;">Net Amount</div>
                            <div id="grandNetAmount" style="font-size: 1.2rem; font-weight: 600; color: #059669;">Rs. 0.00</div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Include all the modals and JavaScript from create view -->
@include('admin.salary-sheets.partials.modals')
@include('admin.salary-sheets.partials.scripts')

<script>
// Initialize with existing data
document.addEventListener('DOMContentLoaded', function() {
    // Initialize Select2 for job dropdown
    $('#job_id').select2({
        placeholder: 'Select Job',
        allowClear: true,
        width: '100%',
        dropdownParent: $('body') // Ensure dropdown appears above other elements
    });
    
    // Set up the job and load existing data
    const jobSelect = document.getElementById('job_id');
    if (jobSelect.value) {
        updateAttendanceDates();
    }
    
    // Load existing salary sheets for this job
    const existingSheets = @json($jobSalarySheets);
    if (existingSheets && existingSheets.length > 0) {
        loadExistingSalarySheetsFromData(existingSheets);
    }
});

// Function to load existing salary sheets from passed data
function loadExistingSalarySheetsFromData(sheets) {
    clearAllRows();
    
    sheets.forEach((sheet, index) => {
        loadSalarySheetAsRow(sheet, index);
    });
    
    calculateGrandTotal();
}

// Override the update function for edit mode
function updateSalarySheet() {
    const form = document.getElementById('salarySheetForm');
    const rows = document.querySelectorAll('#promoterRows tr');
    
    // Clear any existing hidden inputs
    const existingHiddenInputs = form.querySelectorAll('input[name^="rows["]');
    existingHiddenInputs.forEach(input => input.remove());
    
    let hasValidRows = false;
    
    rows.forEach((row, index) => {
        const promoterId = row.querySelector('select[name*="[promoter_id]"]')?.value;
        if (promoterId) {
            hasValidRows = true;
            
            // Collect attendance data dynamically
            const attendanceData = {};
            if (currentAttendanceDates && currentAttendanceDates.length > 0) {
                currentAttendanceDates.forEach(date => {
                    const input = row.querySelector(`input[name*="[attendance][${date}]"]`);
                    if (input) {
                        attendanceData[date] = input.value || 0;
                    }
                });
            }
            
            // Create hidden inputs for each field
            const fields = {
                'promoter_id': promoterId,
                'location': row.querySelector('input[name*="[location]"]')?.value || '',
                'attendance_total': row.querySelector('input[name*="[attendance_total]"]')?.value || 0,
                'attendance_amount': row.querySelector('input[name*="[attendance_amount]"]')?.value || 0,
                'amount': row.querySelector('input[name*="[amount]"]')?.value || 0,
                'food_allowance': row.querySelector('input[name*="[food_allowance]"]')?.value || 0,
                'expenses': row.querySelector('input[name*="[expenses]"]')?.value || 0,
                'accommodation_allowance': row.querySelector('input[name*="[accommodation_allowance]"]')?.value || 0,
                'hold_for_8_weeks': row.querySelector('input[name*="[hold_for_8_weeks]"]')?.value || 0,
                'coordinator_id': row.querySelector('select[name*="[coordinator_id]"]')?.value || '',
                'coordination_fee': row.querySelector('input[name*="[coordination_fee]"]')?.value || 0,
            };
            
            // Add each field as hidden input
            Object.keys(fields).forEach(fieldName => {
                const hiddenInput = document.createElement('input');
                hiddenInput.type = 'hidden';
                hiddenInput.name = `rows[${index}][${fieldName}]`;
                hiddenInput.value = fields[fieldName];
                form.appendChild(hiddenInput);
            });
            
            // Add attendance data as hidden inputs
            Object.keys(attendanceData).forEach(date => {
                const hiddenInput = document.createElement('input');
                hiddenInput.type = 'hidden';
                hiddenInput.name = `rows[${index}][attendance][${date}]`;
                hiddenInput.value = attendanceData[date];
                form.appendChild(hiddenInput);
            });
        }
    });
    
    if (!hasValidRows) {
        Swal.fire({
            icon: 'warning',
            title: 'No Promoter Rows',
            text: 'Please add at least one promoter row.',
            confirmButtonText: 'OK'
        });
        return;
    }
    
    // Show loading state
    const updateBtn = document.querySelector('button[onclick="updateSalarySheet()"]');
    const originalText = updateBtn.innerHTML;
    updateBtn.disabled = true;
    updateBtn.innerHTML = '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 8px;"><path d="M21 12a9 9 0 11-6.219-8.56"></path></svg>Updating...';
    
    // Submit form
    form.submit();
}
</script>
@endsection