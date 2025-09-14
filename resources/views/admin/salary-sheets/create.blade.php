@extends('layouts.admin')

@section('title', 'Create Salary Sheet')
@section('page-title', 'Create Salary Sheet')

@section('breadcrumbs')
    <span class="breadcrumb-separator">›</span>
    <a href="{{ route('admin.salary-sheets.index') }}" class="breadcrumb-item">Salary Sheets</a>
    <span class="breadcrumb-separator">›</span>
    <span class="breadcrumb-item active">Create</span>
@endsection

@section('content')
<form action="{{url('admin/salary-sheet-enforce')}}" method="post" id="salarySheetForm">
    {{csrf_field()}}
    <div class="card">
        <div class="card-header">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <h3>Salary Sheet Management</h3>
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
                    <button type="button" class="btn btn-warning" onclick="openJsonImportModal()">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 8px;">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                            <polyline points="14,2 14,8 20,8"></polyline>
                            <line x1="16" y1="13" x2="8" y2="13"></line>
                            <line x1="16" y1="17" x2="8" y2="17"></line>
                            <polyline points="10,9 9,9 8,9"></polyline>
                        </svg>
                        Import JSON Data
                    </button>
                    <button type="button" class="btn btn-primary" onclick="saveSalarySheet()">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 8px;">
                            <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path>
                            <polyline points="17,21 17,13 7,13 7,21"></polyline>
                            <polyline points="7,3 7,8 15,8"></polyline>
                        </svg>
                        Save Salary Sheet
                    </button>
                </div>
            </div>
        </div>
        <div class="card-body">
                <!-- Basic Information -->
                <div style="background: #f8fafc; padding: 1rem; border-radius: 0.5rem; margin-bottom: 1rem;">
                    <div style="display: grid; grid-template-columns: 1fr 1fr 1fr 1fr; gap: 1rem;">
                        <div>
                            <label style="font-size: 0.75rem; color: #6b7280; font-weight: 600;">SHEET NO</label>
                            <input type="text" class="form-control" id="sheet_number" name="sheet_number" readonly style="background: #f9fafb; font-weight: bold;" placeholder="Auto-generated">
                        </div>
                        <div>
                            <label style="font-size: 0.75rem; color: #6b7280; font-weight: 600;">JOB ID</label>
                            <select class="form-control" id="job_id" name="job_id" onchange="updateAttendanceDates()">
                                <option value="">Select Job</option>
                                @foreach($jobs as $job)
                                    <option value="{{ $job->id }}" data-start-date="{{ $job->start_date }}" data-end-date="{{ $job->end_date }}">{{ $job->job_number }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label style="font-size: 0.75rem; color: #6b7280; font-weight: 600;">PULL DATA</label>
                            <button type="button" class="btn btn-success btn-sm" id="pullDataBtn" onclick="pullExistingData()" disabled style="width: 100%;">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 4px;">
                                    <path d="M3 12a9 9 0 0 1 9-9 9.75 9.75 0 0 1 6.74 2.74L21 8"></path>
                                    <path d="M21 3v5h-5"></path>
                                    <path d="M21 12a9 9 0 0 1-9 9 9.75 9.75 0 0 1-6.74-2.74L3 16"></path>
                                    <path d="M3 21v-5h5"></path>
                                </svg>
                                Pull Data
                            </button>
                        </div>
                        <div>
                            <label style="font-size: 0.75rem; color: #6b7280; font-weight: 600;">STATUS</label>
                            <select class="form-control" name="status" required>
                                <option value="">Select Status</option>
                                <option value="draft" selected>Draft</option>
                                <option value="approved">Approved</option>
                                <option value="paid">Paid</option>
                            </select>
                        </div>
                        <div>
                            <label style="font-size: 0.75rem; color: #6b7280; font-weight: 600;">LOCATION</label>
                            <input type="text" class="form-control" name="location" placeholder="Enter location">
                        </div>
                    </div>
                </div>

                <!-- No Job Selected Message -->
                <div id="noJobMessage" style="text-align: center; padding: 3rem; background: #f8fafc; border: 2px dashed #cbd5e1; border-radius: 0.5rem; margin: 1rem 0;">
                    <div style="color: #64748b; font-size: 1.1rem; margin-bottom: 0.5rem;">
                        <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin: 0 auto 1rem auto; display: block;">
                            <circle cx="12" cy="12" r="10"></circle>
                            <path d="M12 6v6l4 2"></path>
                        </svg>
                    </div>
                    <h3 style="color: #475569; margin-bottom: 0.5rem;">Please Select a Job ID</h3>
                    <p style="color: #64748b; margin: 0;">Choose a job from the dropdown above to start creating the salary sheet.</p>
                </div>

                <!-- Salary Sheet Table -->
                <div id="salaryTableContainer" style="display: none;">
                    <!-- Scroll Navigation Panel -->
                    <div class="scroll-navigation">
                        <div class="scroll-controls">
                            <button type="button" class="scroll-btn" id="scrollLeftBtn" title="Scroll Left">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <polyline points="15,18 9,12 15,6"></polyline>
                                </svg>
                            </button>
                            <button type="button" class="scroll-btn" id="scrollRightBtn" title="Scroll Right">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <polyline points="9,18 15,12 9,6"></polyline>
                                </svg>
                            </button>
                            <button type="button" class="scroll-btn" id="scrollToStartBtn" title="Scroll to Start">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <polyline points="11,17 6,12 11,7"></polyline>
                                    <polyline points="18,17 13,12 18,7"></polyline>
                                </svg>
                            </button>
                            <button type="button" class="scroll-btn" id="scrollToEndBtn" title="Scroll to End">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <polyline points="13,17 18,12 13,7"></polyline>
                                    <polyline points="6,17 11,12 6,7"></polyline>
                                </svg>
                            </button>
                        </div>
                        <div class="scroll-indicator">
                            <span id="scrollPosition">0%</span>
                            <div class="scroll-progress">
                                <div class="scroll-progress-bar" id="scrollProgressBar"></div>
                            </div>
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
                            </tr>
                            </thead>
                            <tbody id="promoterRows">
                            <!-- Rows will be added dynamically -->
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Attendance Legend -->
                <div id="attendanceLegend" style="display: none; background: #f0f9ff; padding: 0.75rem; border-radius: 0.5rem; margin: 0.5rem 0; border-left: 4px solid #3b82f6;">
                    <div style="display: flex; align-items: center; gap: 1rem; font-size: 0.875rem; color: #1e40af;">
                        <div style="display: flex; align-items: center; gap: 0.5rem;">
                            <div style="width: 20px; height: 20px; background: #d1fae5; border: 1px solid #059669; border-radius: 3px; display: flex; align-items: center; justify-content: center; font-weight: bold; color: #059669;">1</div>
                            <span>Present</span>
                        </div>
                        <div style="display: flex; align-items: center; gap: 0.5rem;">
                            <div style="width: 20px; height: 20px; background: #fef3c7; border: 1px solid #f59e0b; border-radius: 3px; display: flex; align-items: center; justify-content: center; font-weight: bold; color: #f59e0b;">0</div>
                            <span>Absent</span>
                        </div>
                        <span style="color: #6b7280;">• Only enter 0 or 1 in attendance fields</span>
                        <span style="color: #059669; font-weight: 500;">• Attendance Amount = Position Salary × Present Days</span>
                    </div>
                </div>

                <!-- Summary Section -->
                <div style="background: #f0f9ff; padding: 1rem; border-radius: 0.5rem; margin-top: 1rem;">
                    <h4 style="margin-bottom: 0.5rem; color: #1e40af;">Salary Summary</h4>
                    <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 1rem;">
                        <div>
                            <span style="color: #6b7280;">Total Earnings:</span>
                            <span id="total-earnings" style="font-weight: bold; color: #2563eb; font-size: 1.1rem;">Rs. 0.00</span>
                        </div>
                        <div>
                            <span style="color: #6b7280;">Total Deductions:</span>
                            <span id="total-deductions" style="font-weight: bold; color: #dc2626; font-size: 1.1rem;">Rs. 0.00</span>
                        </div>
                        <div>
                            <span style="color: #6b7280;">Net Salary:</span>
                            <span id="net-salary" style="font-weight: bold; color: #059669; font-size: 1.2rem;">Rs. 0.00</span>
                        </div>
                    </div>
                </div>

                <!-- Notes Section -->
                <div style="margin-top: 1rem;">
                    <label style="font-size: 0.75rem; color: #6b7280; font-weight: 600;">NOTES</label>
                    <textarea class="form-control" name="notes" rows="3" placeholder="Additional notes or comments..."></textarea>
                </div>
        </div>
    </div>
</form>

<!-- Position Wise Salary Rule Modal -->
<div id="salaryRuleModal" class="modal" style="display: none;">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Position Wise Salary Rules</h3>
            <div style="display: flex; align-items: center; gap: 0.5rem;">
                <button type="button" id="refreshModalBtn" class="btn btn-sm btn-outline" title="Refresh Rules">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="23,4 23,10 17,10"></polyline>
                        <polyline points="1,20 1,14 7,14"></polyline>
                        <path d="M20.49,9A9,9,0,0,0,5.64,5.64L1,10m22,4L18.36,18.36A9,9,0,0,1,3.51,15"></path>
                    </svg>
                </button>
                <span class="close" id="modalCloseBtn">&times;</span>
            </div>
        </div>
        <div class="modal-body">
            <!-- Preloader Section -->
            <div id="modalPreloader" class="modal-preloader" style="display: none;">
                <div class="preloader-content">
                    <div class="preloader-spinner"></div>
                    <p class="preloader-text">Loading salary rules...</p>
                </div>
            </div>

            <!-- Main Content -->
            <div id="modalMainContent">
                <div style="margin-bottom: 1rem;">
                    <button type="button" id="addNewRuleBtn" class="btn btn-success">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 8px;">
                            <line x1="12" y1="5" x2="12" y2="19"></line>
                            <line x1="5" y1="12" x2="19" y2="12"></line>
                        </svg>
                        Add New Rule
                    </button>
                </div>

            <!-- Existing Rules Section -->
            <div id="existingRulesSection" style="margin-bottom: 2rem;">
                <h4 style="color: #374151; margin-bottom: 1rem; font-size: 1rem;">Existing Rules for Selected Job</h4>
                <div id="existingRulesContainer">
                    <!-- Existing rules will be loaded here -->
                </div>
            </div>

            <!-- New Rules Section -->
            <div id="newRulesSection">
                <h4 style="color: #374151; margin-bottom: 1rem; font-size: 1rem;">New Rules to Add</h4>
                <div id="salaryRulesContainer">
                    <!-- New salary rule rows will be added here dynamically -->
                </div>
            </div>

            <div style="margin-top: 1rem; padding-top: 1rem; border-top: 1px solid #e5e7eb;">
                <button type="button" id="saveRulesBtn" class="btn btn-primary">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 8px;">
                        <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path>
                        <polyline points="17,21 17,13 7,13 7,21"></polyline>
                        <polyline points="7,3 7,8 15,8"></polyline>
                    </svg>
                    Save New Rules
                </button>
                <button type="button" id="cancelModalBtn" class="btn btn-secondary" style="margin-left: 0.5rem;">Cancel</button>
            </div>
            </div> <!-- End of modalMainContent -->
        </div>
    </div>
</div>

<!-- Job Settings Modal -->
<div id="jobSettingsModal" class="modal" style="display: none;">
    <div class="modal-content" style="max-width: 800px; width: 90%;">
        <div class="modal-header">
            <h3>Job Settings</h3>
            <span class="close" id="jobSettingsCloseBtn">&times;</span>
        </div>
        <div class="modal-body">
            <!-- Tab Navigation -->
            <div class="tab-navigation" style="display: flex; border-bottom: 2px solid #e5e7eb; margin-bottom: 1.5rem;">
                <button type="button" class="tab-btn active" onclick="switchTab('general')" id="generalTab">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 8px;">
                        <circle cx="12" cy="12" r="3"></circle>
                        <path d="M12 1v6m0 6v6m11-7h-6m-6 0H1"></path>
                    </svg>
                    General Settings
                </button>
                <button type="button" class="tab-btn" onclick="switchTab('allowances')" id="allowancesTab">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 8px;">
                        <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"></path>
                    </svg>
                    Allowances
                </button>
                <button type="button" class="tab-btn" onclick="switchTab('location')" id="locationTab">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 8px;">
                        <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                        <circle cx="12" cy="10" r="3"></circle>
                    </svg>
                    Location
                </button>
            </div>

            <!-- General Settings Tab -->
            <div id="generalTabContent" class="tab-content active">
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
                    <div>
                        <label class="form-label">Default Coordinator Fee (Rs.)</label>
                        <input type="number" step="0.01" class="form-control" id="defaultCoordinatorFee" placeholder="0.00">
                        <small class="form-text text-muted">Default fee for coordinators in this job</small>
                    </div>
                    <div>
                        <label class="form-label">Default Hold for 8 Weeks (Rs.)</label>
                        <input type="number" step="0.01" class="form-control" id="defaultHoldFor8Weeks" placeholder="0.00">
                        <small class="form-text text-muted">Default amount to hold for 8 weeks</small>
                    </div>
                </div>
                <div style="margin-top: 1.5rem;">
                    <label class="form-label">Job Description</label>
                    <textarea class="form-control" id="jobDescription" rows="4" placeholder="Enter detailed job description..."></textarea>
                    <small class="form-text text-muted">Detailed description of the job requirements and scope</small>
                </div>
            </div>

            <!-- Allowances Tab -->
            <div id="allowancesTabContent" class="tab-content">
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
                    <div>
                        <label class="form-label">Default Food Allowance (Rs.)</label>
                        <input type="number" step="0.01" class="form-control" id="defaultFoodAllowance" placeholder="0.00">
                        <small class="form-text text-muted">Default daily food allowance for promoters</small>
                    </div>
                    <div>
                        <label class="form-label">Default Accommodation Allowance (Rs.)</label>
                        <input type="number" step="0.01" class="form-control" id="defaultAccommodationAllowance" placeholder="0.00">
                        <small class="form-text text-muted">Default accommodation allowance for promoters</small>
                    </div>
                </div>
                <div style="margin-top: 1.5rem;">
                    <label class="form-label">Default Expenses (Rs.)</label>
                    <input type="number" step="0.01" class="form-control" id="defaultExpenses" placeholder="0.00">
                    <small class="form-text text-muted">Default miscellaneous expenses allowance</small>
                </div>
            </div>

            <!-- Location Tab -->
            <div id="locationTabContent" class="tab-content">
                <div>
                    <label class="form-label">Default Location</label>
                    <input type="text" class="form-control" id="defaultLocation" placeholder="Enter default location...">
                    <small class="form-text text-muted">Default location for this job</small>
                </div>
                <div style="margin-top: 1.5rem;">
                    <label class="form-label">Additional Location Notes</label>
                    <textarea class="form-control" id="locationNotes" rows="3" placeholder="Additional location details or instructions..."></textarea>
                    <small class="form-text text-muted">Additional notes about location requirements</small>
                </div>
            </div>

            <!-- Modal Footer -->
            <div style="margin-top: 2rem; padding-top: 1.5rem; border-top: 1px solid #e5e7eb; display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <button type="button" id="applyToAllRowsBtn" class="btn btn-success">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 8px;">
                            <path d="M9 12l2 2 4-4"></path>
                            <path d="M21 12c-1 0-3-1-3-3s2-3 3-3 3 1 3 3-2 3-3 3"></path>
                            <path d="M3 12c1 0 3-1 3-3s-2-3-3-3-3 1-3 3 2 3 3 3"></path>
                        </svg>
                        Apply to All Rows
                    </button>
                </div>
                <div style="display: flex; gap: 0.5rem;">
                    <button type="button" id="saveJobSettingsBtn" class="btn btn-primary">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 8px;">
                            <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path>
                            <polyline points="17,21 17,13 7,13 7,21"></polyline>
                            <polyline points="7,3 7,8 15,8"></polyline>
                        </svg>
                        Save Settings
                    </button>
                    <button type="button" id="cancelJobSettingsBtn" class="btn btn-secondary">Cancel</button>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.salary-sheet-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 0.875rem;
    background: white;
    border: 1px solid #e5e7eb;
}

.salary-sheet-table th {
    background-color: #374151;
    color: white;
    padding: 1rem 1.5rem;
    text-align: center;
    font-weight: 600;
    border: 1px solid #4b5563;
}

.salary-sheet-table .sub-header th {
    background-color: #6b7280;
    padding: 0.75rem 1rem;
    font-size: 0.75rem;
}

.salary-sheet-table td {
    padding: 1rem 1.5rem;
    border: 1px solid #e5e7eb;
    vertical-align: middle;
    background: white;
}

.salary-sheet-table tbody tr:nth-child(even) td {
    background-color: #f8fafc;
}

.salary-sheet-table tbody tr:nth-child(even) td.calculated-cell {
    background-color: #dbeafe;
}

/* Horizontal Scroll Container */
.table-scroll-container {
    overflow-x: auto;
    overflow-y: auto;
    position: relative;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    background: white;
    margin-top: 1rem;
    max-height: 70vh;
}

/* Sticky Headers */
.salary-sheet-table thead {
    position: sticky;
    top: 0;
    z-index: 10;
}

.salary-sheet-table thead th {
    position: sticky;
    top: 0;
    background-color: #374151;
    color: white;
    font-weight: 600;
    border-bottom: 2px solid #4b5563;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

/* Ensure sub-headers are also sticky */
.salary-sheet-table .sub-header th {
    position: sticky;
    top: 48px; /* Height of main header */
    background-color: #6b7280;
    z-index: 9;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    border-bottom: 1px solid #4b5563;
}

/* Ensure sub-header content is properly styled */
.salary-sheet-table .sub-header th div {
    color: white;
    font-weight: 500;
}

/* Add smooth scrolling behavior */
.table-scroll-container {
    scroll-behavior: smooth;
}

/* Ensure proper spacing for sticky headers */
.salary-sheet-table tbody tr:first-child td {
    border-top: none;
}

/* Enhanced scrollbar for vertical scrolling */
.table-scroll-container::-webkit-scrollbar {
    height: 8px;
    width: 8px;
}

.table-scroll-container::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 4px;
}

.table-scroll-container::-webkit-scrollbar-thumb {
    background: #c1c1c1;
    border-radius: 4px;
}

.table-scroll-container::-webkit-scrollbar-thumb:hover {
    background: #a8a8a8;
}

/* Corner scrollbar styling */
.table-scroll-container::-webkit-scrollbar-corner {
    background: #f1f1f1;
}

/* Scroll Navigation Panel */
.scroll-navigation {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.5rem 1rem;
    background: #f8f9fa;
    border-bottom: 1px solid #e5e7eb;
    border-radius: 8px 8px 0 0;
}

.scroll-controls {
    display: flex;
    gap: 0.5rem;
    align-items: center;
}

.scroll-btn {
    background: #3b82f6;
    color: white;
    border: none;
    padding: 0.5rem;
    border-radius: 4px;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.2s ease;
    min-width: 36px;
    height: 36px;
}

.scroll-btn:hover {
    background: #2563eb;
    transform: translateY(-1px);
}

.scroll-btn:disabled {
    background: #9ca3af;
    cursor: not-allowed;
    transform: none;
}

.scroll-indicator {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.875rem;
    color: #6b7280;
}

.scroll-progress {
    width: 200px;
    height: 4px;
    background: #e5e7eb;
    border-radius: 2px;
    position: relative;
    overflow: hidden;
}

.scroll-progress-bar {
    height: 100%;
    background: #3b82f6;
    border-radius: 2px;
    transition: width 0.3s ease;
    width: 0%;
}

/* Draggable Scroll */
.table-scroll-container.dragging {
    cursor: grabbing;
    user-select: none;
}

.table-scroll-container.dragging * {
    pointer-events: none;
}

/* Ensure form controls are always interactive */
.table-scroll-container input,
.table-scroll-container select,
.table-scroll-container textarea,
.table-scroll-container button {
    pointer-events: auto !important;
    cursor: default !important;
}

.table-scroll-container.dragging input,
.table-scroll-container.dragging select,
.table-scroll-container.dragging textarea,
.table-scroll-container.dragging button {
    pointer-events: auto !important;
    cursor: default !important;
}

/* Touch/Swipe Support */
.table-scroll-container {
    touch-action: pan-x;
    -webkit-overflow-scrolling: touch;
}

/* Responsive Scroll Indicators */
@media (max-width: 768px) {
    .scroll-navigation {
        padding: 0.25rem 0.5rem;
    }

    .scroll-progress {
        width: 120px;
    }

    .scroll-btn {
        min-width: 32px;
        height: 32px;
        padding: 0.25rem;
    }
}

/* Smooth scroll animations */
@keyframes scrollSmooth {
    from { transform: translateX(0); }
    to { transform: translateX(-100px); }
}

.table-scroll-container {
    scroll-behavior: smooth;
}

/* Enhanced drag cursor */
.table-scroll-container.dragging {
    cursor: grabbing !important;
}

.table-scroll-container:not(.dragging) {
    cursor: grab;
}

/* Scroll hint animation */
.scroll-hint {
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.5; }
}

.table-input {
    width: 100%;
    padding: 0.75rem;
    border: 1px solid #d1d5db;
    border-radius: 0.375rem;
    font-size: 0.875rem;
    background: white;
    text-align: center;
}

.table-input:focus {
    outline: none;
    border-color: #3b82f6;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

.table-input-small {
    width: 100%;
    padding: 0.5rem;
    border: 1px solid #d1d5db;
    border-radius: 0.375rem;
    font-size: 0.8rem;
    background: white;
    text-align: center;
}

.table-input-small:focus {
    outline: none;
    border-color: #3b82f6;
    box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.1);
}

.table-input-readonly {
    background-color: #f9fafb !important;
    font-weight: bold;
}

.form-control {
    width: 100%;
    padding: 0.5rem;
    border: 1px solid #d1d5db;
    border-radius: 0.375rem;
    font-size: 0.875rem;
    background: white;
}

.form-control:focus {
    outline: none;
    border-color: #3b82f6;
    box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.1);
}

.btn-success {
    background-color: #10b981;
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

.btn-success:hover {
    background-color: #059669;
    transform: translateY(-1px);
}

.btn-primary {
    background-color: #3b82f6;
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

.btn-primary:hover {
    background-color: #2563eb;
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

.btn-danger {
    background-color: #ef4444;
    color: white;
    padding: 0.25rem 0.5rem;
    border: none;
    border-radius: 0.25rem;
    font-size: 0.75rem;
    cursor: pointer;
}

.btn-danger:hover {
    background-color: #dc2626;
}

@media (max-width: 768px) {
    .salary-sheet-table {
        font-size: 0.75rem;
    }

    .salary-sheet-table th,
    .salary-sheet-table td {
        padding: 0.25rem;
    }

    div[style*="grid-template-columns"] {
        grid-template-columns: 1fr !important;
    }
}

/* Modal Styles */
.modal {
    position: fixed;
    z-index: 1000;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
    backdrop-filter: blur(4px);
}

.modal-content {
    background-color: white;
    margin: 5% auto;
    padding: 0;
    border-radius: 0.5rem;
    width: 80%;
    max-width: 800px;
    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
    animation: modalSlideIn 0.3s ease-out;
}

@keyframes modalSlideIn {
    from {
        opacity: 0;
        transform: translateY(-50px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.modal-header {
    padding: 1.5rem;
    border-bottom: 1px solid #e5e7eb;
    display: flex;
    justify-content: space-between;
    align-items: center;
    background-color: #f8fafc;
    border-radius: 0.5rem 0.5rem 0 0;
}

.modal-header h3 {
    margin: 0;
    color: #374151;
    font-size: 1.25rem;
    font-weight: 600;
}

.close {
    color: #6b7280;
    font-size: 2rem;
    font-weight: bold;
    cursor: pointer;
    line-height: 1;
}

.close:hover {
    color: #374151;
}

.modal-body {
    padding: 1.5rem;
    max-height: 70vh;
    overflow-y: auto;
}

.salary-rule-row {
    display: grid;
    grid-template-columns: 2fr 2fr 1fr 1fr auto;
    gap: 1rem;
    align-items: center;
    padding: 1rem;
    margin-bottom: 1rem;
    background-color: #f8fafc;
    border: 1px solid #e5e7eb;
    border-radius: 0.5rem;
}

.salary-rule-row select,
.salary-rule-row input {
    padding: 0.75rem;
    border: 1px solid #d1d5db;
    border-radius: 0.375rem;
    font-size: 0.875rem;
    background: white;
}

.salary-rule-row select:focus,
.salary-rule-row input:focus {
    outline: none;
    border-color: #3b82f6;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

.remove-rule-btn {
    background-color: #dc2626;
    color: white;
    border: none;
    border-radius: 0.375rem;
    padding: 0.75rem;
    cursor: pointer;
    font-size: 1rem;
    font-weight: bold;
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.remove-rule-btn:hover {
    background-color: #b91c1c;
}

.existing-rule-row {
    display: grid;
    grid-template-columns: 2fr 1fr 1fr auto;
    gap: 1rem;
    align-items: center;
    padding: 1rem;
    margin-bottom: 1rem;
    background-color: #f0f9ff;
    border: 1px solid #0ea5e9;
    border-radius: 0.5rem;
}

.existing-rule-row .rule-info {
    padding: 0.75rem;
    background: #f8fafc;
    border: 1px solid #d1d5db;
    border-radius: 0.375rem;
    font-size: 0.875rem;
    color: #374151;
}

.delete-rule-btn {
    background-color: #dc2626;
    color: white;
    border: none;
    border-radius: 0.375rem;
    padding: 0.75rem;
    cursor: pointer;
    font-size: 1rem;
    font-weight: bold;
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.delete-rule-btn:hover {
    background-color: #b91c1c;
}

.btn:disabled {
    opacity: 0.6;
    cursor: not-allowed;
    background-color: #9ca3af !important;
    border-color: #9ca3af !important;
}

.btn:disabled:hover {
    background-color: #9ca3af !important;
    border-color: #9ca3af !important;
}

@keyframes spin {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
}

/* Modal Preloader Styles */
.modal-preloader {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: rgba(255, 255, 255, 0.95);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 1000;
    border-radius: 0.5rem;
}

.preloader-content {
    text-align: center;
    padding: 2rem;
}

.preloader-spinner {
    width: 40px;
    height: 40px;
    border: 4px solid #e5e7eb;
    border-top: 4px solid #3b82f6;
    border-radius: 50%;
    animation: spin 1s linear infinite;
    margin: 0 auto 1rem auto;
}

.preloader-text {
    color: #6b7280;
    font-size: 0.875rem;
    margin: 0;
    font-weight: 500;
}

.btn-sm {
    padding: 0.375rem 0.75rem;
    font-size: 0.75rem;
    border-radius: 0.25rem;
}

.btn-outline {
    background-color: transparent;
    border: 1px solid #d1d5db;
    color: #6b7280;
}

.btn-outline:hover {
    background-color: #f9fafb;
    border-color: #9ca3af;
    color: #374151;
}

.btn:disabled {
    opacity: 0.6;
    cursor: not-allowed;
    background-color: #e5e7eb !important;
    border-color: #d1d5db !important;
    color: #9ca3af !important;
}

.btn:disabled:hover {
    background-color: #e5e7eb !important;
    border-color: #d1d5db !important;
    color: #9ca3af !important;
}

/* Attendance input styling */
.table-input-small[placeholder="0/1"] {
    text-align: center;
    font-weight: bold;
    background-color: #fef3c7;
    border-color: #f59e0b;
}

.table-input-small[placeholder="0/1"]:focus {
    background-color: #fef3c7;
    border-color: #d97706;
    box-shadow: 0 0 0 3px rgba(245, 158, 11, 0.1);
}

/* Attendance amount field styling for better number display */
.table-input-small[name*="[attendance_amount]"] {
    text-align: right;
    font-weight: 500;
    font-family: 'Courier New', monospace;
    letter-spacing: 0.5px;
}

/* Payment amount field styling for auto-calculated values */
.table-input-small[name*="[amount]"][readonly] {
    text-align: right;
    font-weight: 500;
    font-family: 'Courier New', monospace;
    letter-spacing: 0.5px;
    background-color: #f0f9ff;
    border-color: #3b82f6;
}

/* Job Settings Modal Styles */
.tab-navigation {
    display: flex;
    border-bottom: 2px solid #e5e7eb;
    margin-bottom: 1.5rem;
}

.tab-btn {
    background: none;
    border: none;
    padding: 0.75rem 1.5rem;
    cursor: pointer;
    border-bottom: 3px solid transparent;
    color: #6b7280;
    font-weight: 500;
    display: flex;
    align-items: center;
    transition: all 0.2s ease;
}

.tab-btn:hover {
    color: #374151;
    background-color: #f9fafb;
}

.tab-btn.active {
    color: #3b82f6;
    border-bottom-color: #3b82f6;
    background-color: #f0f9ff;
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

.form-text {
    font-size: 0.75rem;
    color: #6b7280;
    margin-top: 0.25rem;
}

.form-label {
    font-weight: 600;
    color: #374151;
    margin-bottom: 0.5rem;
    display: block;
}

/* Professional Tooltip Styles */
.promoter-tooltip {
    position: relative;
    cursor: help;
}

.tooltip-container {
    position: absolute;
    bottom: calc(100% + 12px);
    left: 50%;
    transform: translateX(-50%);
    background: #1f2937;
    color: white;
    padding: 12px 16px;
    border-radius: 8px;
    font-size: 0.875rem;
    line-height: 1.4;
    opacity: 0;
    visibility: hidden;
    transition: all 0.3s ease;
    z-index: 1000;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    min-width: 280px;
    max-width: 400px;
    white-space: normal;
}

.tooltip-container::after {
    content: '';
    position: absolute;
    top: 100%;
    left: 50%;
    transform: translateX(-50%);
    width: 0;
    height: 0;
    border: 6px solid transparent;
    border-top-color: #1f2937;
}

.tooltip-container.show {
    opacity: 1;
    visibility: visible;
}

/* Tooltip content styling */
.tooltip-content {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.tooltip-header {
    font-weight: 600;
    font-size: 0.9rem;
    color: #f9fafb;
    border-bottom: 1px solid #374151;
    padding-bottom: 6px;
    margin-bottom: 6px;
}

.tooltip-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 12px;
}

.tooltip-label {
    color: #d1d5db;
    font-size: 0.8rem;
    font-weight: 500;
    min-width: 80px;
}

.tooltip-value {
    color: #ffffff;
    font-size: 0.8rem;
    font-weight: 400;
    text-align: right;
    flex: 1;
}

.tooltip-status {
    display: inline-block;
    padding: 2px 8px;
    border-radius: 12px;
    font-size: 0.75rem;
    font-weight: 500;
    text-transform: uppercase;
}

.tooltip-status.active {
    background-color: #10b981;
    color: white;
}

.tooltip-status.inactive {
    background-color: #6b7280;
    color: white;
}

.tooltip-status.suspended {
    background-color: #f59e0b;
    color: white;
}

/* Responsive tooltip positioning */
@media (max-width: 768px) {
    .tooltip-container {
        left: 0;
        transform: none;
        min-width: 250px;
    }

    .tooltip-container::after {
        left: 20px;
        transform: none;
    }
}
</style>

<!-- JSON Import Modal -->
<div id="jsonImportModal" class="modal" style="display: none;">
    <div class="modal-content" style="max-width: 800px;">
        <div class="modal-header">
            <h3>Import JSON Data</h3>
            <span class="close" onclick="closeJsonImportModal()">&times;</span>
        </div>
        <div class="modal-body">
            <div style="margin-bottom: 1rem;">
                <label for="jsonDataTextarea" style="display: block; margin-bottom: 0.5rem; font-weight: bold;">
                    Paste JSON Data:
                </label>
                <textarea id="jsonDataTextarea" rows="15" style="width: 100%; padding: 0.75rem; border: 1px solid #ddd; border-radius: 4px; font-family: monospace; font-size: 12px;" placeholder="Paste your JSON data here..."></textarea>
            </div>
            <div style="margin-bottom: 1rem;">
                <button type="button" class="btn btn-primary" onclick="importJsonData()">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 8px;">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                        <polyline points="14,2 14,8 20,8"></polyline>
                        <line x1="16" y1="13" x2="8" y2="13"></line>
                        <line x1="16" y1="17" x2="8" y2="17"></line>
                        <polyline points="10,9 9,9 8,9"></polyline>
                    </svg>
                    Import Data
                </button>
                <button type="button" class="btn btn-secondary" onclick="closeJsonImportModal()" style="margin-left: 0.5rem;">
                    Cancel
                </button>
            </div>
            <div id="jsonImportStatus" style="padding: 0.75rem; border-radius: 4px; display: none;"></div>
        </div>
    </div>
</div>

<script>
const promoters = @json($promoters);
const coordinators = @json($coordinators);
const jobs = @json($jobs);
let rowCounter = 1;

function addPromoterRow() {
    const jobSelect = document.getElementById('job_id');
    if (!jobSelect.value) {
        Swal.fire({
            icon: 'warning',
            title: 'Job Selection Required',
            text: 'Please select a job first before adding promoter rows.',
            confirmButtonText: 'OK'
        });
        return;
    }

    const tbody = document.getElementById('promoterRows');
    const row = document.createElement('tr');
    
    // Calculate the next row number based on existing rows
    const existingRows = tbody.querySelectorAll('tr');
    const nextRowNumber = existingRows.length + 1;

    // Generate attendance inputs based on current dates
    let attendanceInputs = '';
    console.log('addPromoterRow - currentAttendanceDates:', currentAttendanceDates);
    console.log('addPromoterRow - currentAttendanceDates.length:', currentAttendanceDates ? currentAttendanceDates.length : 'undefined');

    if (currentAttendanceDates && currentAttendanceDates.length > 0) {
        attendanceInputs = currentAttendanceDates.map(date =>
            `<input type="number" class="table-input-small" name="rows[${nextRowNumber}][attendance][${date}]" min="0" max="1" step="1" onchange="calculateRowTotal(${nextRowNumber})" placeholder="0/1">`
        ).join('');
        console.log('addPromoterRow - Generated attendance inputs with dates:', attendanceInputs.substring(0, 200));
    } else {
        // Fallback: create 6 default attendance inputs if no dates are available
        attendanceInputs = Array.from({length: 6}, (_, i) =>
            `<input type="number" class="table-input-small" name="rows[${nextRowNumber}][attendance][day${i+1}]" min="0" max="1" step="1" onchange="calculateRowTotal(${nextRowNumber})" placeholder="0/1">`
        ).join('');
        console.log('addPromoterRow - Generated fallback attendance inputs:', attendanceInputs.substring(0, 200));
    }

    row.innerHTML = `
        <td style="text-align: center; font-weight: bold;">${nextRowNumber}</td>
        <td>
            <input type="text" class="table-input" name="rows[${nextRowNumber}][location]" placeholder="Location">
        </td>
        <td>
            <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 1rem;">
                <select class="table-input-small" name="rows[${nextRowNumber}][promoter_id]" onchange="updatePromoterDetails(${nextRowNumber}, this)">
                    <option value="">Select</option>
                    ${promoters.map(promoter => {
                        const positionName = promoter.position ? promoter.position.position_name : 'No Position';
                        return `<option value="${promoter.id}"
                                data-name="${promoter.promoter_name}"
                                data-position="${positionName}"
                                data-phone="${promoter.phone_no || ''}"
                                data-id-card="${promoter.identity_card_no || ''}"
                                data-bank="${promoter.bank_name || ''}"
                                data-account="${promoter.bank_account_number || ''}"
                                data-status="${promoter.status || 'inactive'}"
                                data-position-id="${promoter.position_id || ''}">${promoter.promoter_id}</option>`;
                    }).join('')}
                </select>
                <input type="text" class="table-input-small table-input-readonly promoter-tooltip" name="rows[${nextRowNumber}][promoter_name]" readonly data-tooltip="">
                <input type="text" class="table-input-small table-input-readonly" name="rows[${nextRowNumber}][position]" readonly>
            </div>
        </td>
        <td id="attendanceCell-${nextRowNumber}" style="display: table-cell; width: ${(currentAttendanceDates.length || 6) * 80 + 160}px;">
            <div style="display: grid; grid-template-columns: repeat(${currentAttendanceDates.length || 6}, 1fr) 1fr 1.5fr; gap: 0.75rem; width: ${(currentAttendanceDates.length || 6) * 80 + 160}px;">
                ${attendanceInputs}
                <input type="number" class="table-input-small calculated-cell" name="rows[${nextRowNumber}][attendance_total]" readonly>
                <input type="number" step="0.01" class="table-input-small calculated-cell" name="rows[${nextRowNumber}][attendance_amount]" readonly title="Auto-calculated: Position Salary × Present Days">
            </div>
        </td>
        <td>
            <div style="display: grid; grid-template-columns: repeat(6, 1fr); gap: 0.75rem;">
                <input type="number" step="0.01" class="table-input-small calculated-cell" name="rows[${nextRowNumber}][amount]" readonly title="Auto-calculated from Attendance Amount">
                <input type="number" step="0.01" class="table-input-small" name="rows[${nextRowNumber}][food_allowance]" onchange="calculateRowNet(${nextRowNumber})">
                <input type="number" step="0.01" class="table-input-small" name="rows[${nextRowNumber}][expenses]" onchange="calculateRowNet(${nextRowNumber})">
                <input type="number" step="0.01" class="table-input-small" name="rows[${nextRowNumber}][accommodation_allowance]" onchange="calculateRowNet(${nextRowNumber})">
                <input type="number" step="0.01" class="table-input-small" name="rows[${nextRowNumber}][hold_for_8_weeks]" onchange="calculateRowNet(${nextRowNumber})">
                <input type="number" step="0.01" class="table-input-small calculated-cell" name="rows[${nextRowNumber}][net_amount]" readonly>
            </div>
        </td>
        <td>
            <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 1rem;">
                <select class="table-input-small" name="rows[${nextRowNumber}][coordinator_id]" onchange="updateCoordinatorDisplay(${nextRowNumber}, this)">
                    <option value="">Select</option>
                    ${coordinators.map(coordinator =>
                        `<option value="${coordinator.id}" data-name="${coordinator.coordinator_name}">${coordinator.coordinator_id}</option>`
                    ).join('')}
                </select>
                <input type="text" class="table-input-small table-input-readonly" name="rows[${nextRowNumber}][current_coordinator]" readonly>
                <input type="number" step="0.01" class="table-input-small" name="rows[${nextRowNumber}][coordination_fee]" onchange="calculateRowNet(${nextRowNumber})">
            </div>
        </td>
        <td>
            <button type="button" class="btn-danger" onclick="removeRow(${nextRowNumber})">×</button>
        </td>
    `;

    tbody.appendChild(row);
    
    // Update the global rowCounter to match the actual number of rows
    rowCounter = nextRowNumber;
    
    // Trigger initial calculations for the new row
    setTimeout(() => {
        console.log(`Triggering calculations for new row ${nextRowNumber}`);
        calculateRowTotal(nextRowNumber);
        calculateAttendanceAmount(nextRowNumber);
        calculateRowNet(nextRowNumber);
        calculateGrandTotal();
    }, 100);
    
    console.log(`Added promoter row ${nextRowNumber} successfully`);
}

function updatePromoterTooltip(inputElement, promoter) {
    const positionName = promoter.position ? promoter.position.position_name : 'No Position';
    const statusClass = promoter.status || 'inactive';
    const statusText = (promoter.status || 'inactive').charAt(0).toUpperCase() + (promoter.status || 'inactive').slice(1);

    const tooltipContent = `
        <div class="tooltip-content">
            <div class="tooltip-header">${promoter.promoter_name}</div>
            <div class="tooltip-row">
                <span class="tooltip-label">ID:</span>
                <span class="tooltip-value">${promoter.promoter_id}</span>
            </div>
            <div class="tooltip-row">
                <span class="tooltip-label">Position:</span>
                <span class="tooltip-value">${positionName}</span>
            </div>
            <div class="tooltip-row">
                <span class="tooltip-label">Phone:</span>
                <span class="tooltip-value">${promoter.phone_no || 'N/A'}</span>
            </div>
            <div class="tooltip-row">
                <span class="tooltip-label">ID Card:</span>
                <span class="tooltip-value">${promoter.identity_card_no || 'N/A'}</span>
            </div>
            <div class="tooltip-row">
                <span class="tooltip-label">Bank:</span>
                <span class="tooltip-value">${promoter.bank_name || 'N/A'}</span>
            </div>
            <div class="tooltip-row">
                <span class="tooltip-label">Account:</span>
                <span class="tooltip-value">${promoter.bank_account_number ? '****' + promoter.bank_account_number.slice(-4) : 'N/A'}</span>
            </div>
            <div class="tooltip-row">
                <span class="tooltip-label">Status:</span>
                <span class="tooltip-value">
                    <span class="tooltip-status ${statusClass}">${statusText}</span>
                </span>
            </div>
        </div>
    `;

    inputElement.setAttribute('data-tooltip', tooltipContent);
}

function updatePromoterTooltipFromOption(inputElement, optionElement) {
    const statusClass = optionElement.dataset.status || 'inactive';
    const statusText = (optionElement.dataset.status || 'inactive').charAt(0).toUpperCase() + (optionElement.dataset.status || 'inactive').slice(1);

    const tooltipContent = `
        <div class="tooltip-content">
            <div class="tooltip-header">${optionElement.dataset.name}</div>
            <div class="tooltip-row">
                <span class="tooltip-label">ID:</span>
                <span class="tooltip-value">${optionElement.textContent}</span>
            </div>
            <div class="tooltip-row">
                <span class="tooltip-label">Position:</span>
                <span class="tooltip-value">${optionElement.dataset.position}</span>
            </div>
            <div class="tooltip-row">
                <span class="tooltip-label">Phone:</span>
                <span class="tooltip-value">${optionElement.dataset.phone || 'N/A'}</span>
            </div>
            <div class="tooltip-row">
                <span class="tooltip-label">ID Card:</span>
                <span class="tooltip-value">${optionElement.dataset.idCard || 'N/A'}</span>
            </div>
            <div class="tooltip-row">
                <span class="tooltip-label">Bank:</span>
                <span class="tooltip-value">${optionElement.dataset.bank || 'N/A'}</span>
            </div>
            <div class="tooltip-row">
                <span class="tooltip-label">Account:</span>
                <span class="tooltip-value">${optionElement.dataset.account ? '****' + optionElement.dataset.account.slice(-4) : 'N/A'}</span>
            </div>
            <div class="tooltip-row">
                <span class="tooltip-label">Status:</span>
                <span class="tooltip-value">
                    <span class="tooltip-status ${statusClass}">${statusText}</span>
                </span>
            </div>
        </div>
    `;

    inputElement.setAttribute('data-tooltip', tooltipContent);
}

function updatePromoterDetails(rowNum, selectElement) {
    const selectedOption = selectElement.options[selectElement.selectedIndex];
    const row = selectElement.closest('tr');

    if (selectedOption && selectedOption.dataset.name) {
        const promoterNameInput = row.querySelector('input[name="rows[' + rowNum + '][promoter_name]"]');
        const positionInput = row.querySelector('input[name="rows[' + rowNum + '][position]"]');

        promoterNameInput.value = selectedOption.dataset.name;
        positionInput.value = selectedOption.dataset.position;

        // Update tooltip with promoter details from option data attributes
        updatePromoterTooltipFromOption(promoterNameInput, selectedOption);

        // Recalculate attendance amount when promoter changes
        const totalInput = row.querySelector(`input[name="rows[${rowNum}][attendance_total]"]`);
        const presentDays = totalInput ? parseFloat(totalInput.value) || 0 : 0;
        calculateAttendanceAmount(rowNum, presentDays);
    } else {
        const promoterNameInput = row.querySelector('input[name="rows[' + rowNum + '][promoter_name]"]');
        const positionInput = row.querySelector('input[name="rows[' + rowNum + '][position]"]');

        promoterNameInput.value = '';
        positionInput.value = '';

        // Clear tooltip
        promoterNameInput.setAttribute('data-tooltip', '');

        // Clear attendance amount when no promoter selected
        const attendanceAmountInput = row.querySelector(`input[name="rows[${rowNum}][attendance_amount]"]`);
        if (attendanceAmountInput) {
            attendanceAmountInput.value = '0.00';
        }
    }
}

function updateCoordinatorDisplay(rowNum, selectElement) {
    const selectedOption = selectElement.options[selectElement.selectedIndex];
    const row = selectElement.closest('tr');

    if (selectedOption && selectedOption.dataset.name) {
        row.querySelector('input[name="rows[' + rowNum + '][current_coordinator]"]').value = selectedOption.dataset.name;
    } else {
        row.querySelector('input[name="rows[' + rowNum + '][current_coordinator]"]').value = '';
    }
}

// Global variable to store current attendance dates
let currentAttendanceDates = [];

function validateAttendanceInput(input) {
    const value = parseFloat(input.value);

    // Check if value is valid (only 0 or 1)
    if (isNaN(value) || (value !== 0 && value !== 1)) {
        // Reset to 0 if invalid value
        input.value = '0';

        // Show a more user-friendly message
        const tooltip = document.createElement('div');
        tooltip.style.cssText = `
            position: absolute;
            background: #dc2626;
            color: white;
            padding: 0.5rem;
            border-radius: 0.25rem;
            font-size: 0.75rem;
            z-index: 1000;
            pointer-events: none;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        `;
        tooltip.textContent = 'Only 0 (absent) or 1 (present) allowed';

        // Position tooltip near input
        const rect = input.getBoundingClientRect();
        tooltip.style.left = rect.left + 'px';
        tooltip.style.top = (rect.bottom + 5) + 'px';

        document.body.appendChild(tooltip);

        // Remove tooltip after 3 seconds
        setTimeout(() => {
            if (tooltip.parentNode) {
                tooltip.parentNode.removeChild(tooltip);
            }
        }, 3000);

        // Add visual feedback
        input.style.backgroundColor = '#fecaca';
        input.style.borderColor = '#dc2626';

        setTimeout(() => {
            input.style.backgroundColor = '';
            input.style.borderColor = '';
        }, 1000);

    } else {
        // Ensure it's an integer (0 or 1)
        input.value = Math.floor(value);

        // Add visual feedback for valid input
        input.style.backgroundColor = '#d1fae5';
        input.style.borderColor = '#059669';

        setTimeout(() => {
            input.style.backgroundColor = '';
            input.style.borderColor = '';
        }, 500);
    }
}

function updateAttendanceDates() {
    const jobSelect = document.getElementById('job_id');
    const selectedOption = jobSelect.options[jobSelect.selectedIndex];
    const noJobMessage = document.getElementById('noJobMessage');
    const salaryTableContainer = document.getElementById('salaryTableContainer');
    const addPromoterBtn = document.getElementById('addPromoterBtn');
    const salaryRuleBtn = document.getElementById('salaryRuleBtn');
    const attendanceLegend = document.getElementById('attendanceLegend');

    if (selectedOption.value) {
        const startDate = selectedOption.getAttribute('data-start-date');
        const endDate = selectedOption.getAttribute('data-end-date');

        if (startDate && endDate) {
            const dates = generateDateRange(startDate, endDate);
            updateAttendanceHeaders(dates);
            updateExistingRows(dates);
            currentAttendanceDates = dates;

            // Load position salary rules for the selected job
            loadPositionSalaryRules();

            // Enable Pull Data button when job is selected
            const pullDataBtn = document.getElementById('pullDataBtn');
            if (pullDataBtn) {
                pullDataBtn.disabled = false;
            }

            // Show table and hide message
            noJobMessage.style.display = 'none';
            salaryTableContainer.style.display = 'block';
            attendanceLegend.style.display = 'block';

            // Enable buttons
            addPromoterBtn.disabled = false;
            salaryRuleBtn.disabled = false;

            // Initialize horizontal scroll functionality
            setTimeout(() => {
                initializeHorizontalScroll();
            }, 100);
        }
    } else {
        // Clear dates when no job selected
        currentAttendanceDates = [];
        updateAttendanceHeaders([]);
        updateExistingRows([]);

        // Clear all rows and add one empty row
        clearAllRows();
        addPromoterRow();

        // Hide table and show message
        noJobMessage.style.display = 'block';
        salaryTableContainer.style.display = 'none';
        attendanceLegend.style.display = 'none';

        // Disable buttons
        addPromoterBtn.disabled = true;
        salaryRuleBtn.disabled = true;
        
        // Disable Pull Data button when no job is selected
        const pullDataBtn = document.getElementById('pullDataBtn');
        if (pullDataBtn) {
            pullDataBtn.disabled = true;
        }
    }
}

function generateDateRange(startDate, endDate) {
    const dates = [];
    const start = new Date(startDate);
    const end = new Date(endDate);

    for (let d = new Date(start); d <= end; d.setDate(d.getDate() + 1)) {
        dates.push(d.toISOString().split('T')[0]);
    }

    return dates;
}

function updateAttendanceHeaders(dates) {
    const headersContainer = document.getElementById('attendanceHeaders');
    const attendanceColumn = document.getElementById('attendanceColumn');

    // Always show attendance column (either with dates or fallback)
    attendanceColumn.style.display = 'table-cell';

    // Calculate dynamic width based on number of dates (use 6 as fallback)
    const baseWidth = 160; // Base width for Total and Amount columns (increased for wider amount field)
    const dateWidth = 80; // Width per date column
    const numDates = dates.length || 6; // Use 6 as fallback when no dates
    const totalWidth = (numDates * dateWidth) + baseWidth;

    // Update attendance column width
    attendanceColumn.style.width = `${totalWidth}px`;

    const totalColumns = numDates + 2; // dates + Total + Amount

    // Update grid template columns
    headersContainer.style.gridTemplateColumns = `repeat(${numDates}, 1fr) 1fr 1.5fr`;
    headersContainer.style.width = `${totalWidth}px`;

    // Clear existing headers
    headersContainer.innerHTML = '';

    // Add date headers (either real dates or fallback)
    if (dates.length > 0) {
        // Use real dates
        dates.forEach(date => {
            const dateDiv = document.createElement('div');
            dateDiv.style.textAlign = 'center';
            dateDiv.style.fontSize = '0.7rem';
            dateDiv.textContent = date;
            headersContainer.appendChild(dateDiv);
        });
    } else {
        // Use fallback date headers (Day 1, Day 2, etc.)
        for (let i = 0; i < 6; i++) {
            const dateDiv = document.createElement('div');
            dateDiv.style.textAlign = 'center';
            dateDiv.style.fontSize = '0.7rem';
            dateDiv.textContent = `Day ${i+1}`;
            headersContainer.appendChild(dateDiv);
        }
    }

    // Add Total and Amount headers
    const totalDiv = document.createElement('div');
    totalDiv.style.textAlign = 'center';
    totalDiv.style.fontSize = '0.7rem';
    totalDiv.textContent = 'Total';
    headersContainer.appendChild(totalDiv);

    const amountDiv = document.createElement('div');
    amountDiv.style.textAlign = 'center';
    amountDiv.style.fontSize = '0.7rem';
    amountDiv.textContent = 'Amount';
    headersContainer.appendChild(amountDiv);
}

function updateExistingRows(dates) {
    const rows = document.querySelectorAll('#promoterRows tr');
    rows.forEach(row => {
        const attendanceCell = row.querySelector('td:nth-child(4)');
        if (attendanceCell) {
            // Always show attendance cell (either with dates or fallback)
            attendanceCell.style.display = 'table-cell';

            // Calculate dynamic width based on number of dates (use 6 as fallback)
            const baseWidth = 160; // Base width for Total and Amount columns (increased for wider amount field)
            const dateWidth = 80; // Width per date column
            const numDates = dates.length || 6; // Use 6 as fallback when no dates
            const totalWidth = (numDates * dateWidth) + baseWidth;

            // Update attendance cell width
            attendanceCell.style.width = `${totalWidth}px`;

            const gridContainer = attendanceCell.querySelector('div');
            if (gridContainer) {
                const totalColumns = numDates + 2;
                gridContainer.style.gridTemplateColumns = `repeat(${numDates}, 1fr) 1fr 1.5fr`;
                gridContainer.style.width = `${totalWidth}px`;

                // Clear existing inputs
                gridContainer.innerHTML = '';

                // Add date inputs (either real dates or fallback)
                if (dates.length > 0) {
                    // Use real dates
                    dates.forEach(date => {
                        const input = document.createElement('input');
                        input.type = 'number';
                        input.className = 'table-input-small';
                        input.name = `rows[${getRowNumberFromElement(row)}][attendance][${date}]`;
                        input.min = '0';
                        input.max = '1';
                        input.step = '1';
                        input.placeholder = '0/1';
                        input.onchange = () => {
                            calculateRowTotal(getRowNumberFromElement(row));
                        };
                        gridContainer.appendChild(input);
                    });
                } else {
                    // Use fallback dates (day1, day2, etc.)
                    for (let i = 0; i < 6; i++) {
                        const input = document.createElement('input');
                        input.type = 'number';
                        input.className = 'table-input-small';
                        input.name = `rows[${getRowNumberFromElement(row)}][attendance][day${i+1}]`;
                        input.min = '0';
                        input.max = '1';
                        input.step = '1';
                        input.placeholder = '0/1';
                        input.onchange = () => {
                            calculateRowTotal(getRowNumberFromElement(row));
                        };
                        gridContainer.appendChild(input);
                    }
                }

                // Add Total input
                const totalInput = document.createElement('input');
                totalInput.type = 'number';
                totalInput.className = 'table-input-small calculated-cell';
                totalInput.name = `rows[${getRowNumberFromElement(row)}][attendance_total]`;
                totalInput.readOnly = true;
                gridContainer.appendChild(totalInput);

                // Add Amount input
                const amountInput = document.createElement('input');
                amountInput.type = 'number';
                amountInput.step = '0.01';
                amountInput.className = 'table-input-small calculated-cell';
                amountInput.name = `rows[${getRowNumberFromElement(row)}][attendance_amount]`;
                amountInput.readOnly = true;
                amountInput.title = 'Auto-calculated: Position Salary × Present Days';
                gridContainer.appendChild(amountInput);
            }
        }
    });
}

function getRowNumber(row) {
    const firstInput = row.querySelector('input[name*="[promoter_id]"]');
    if (firstInput) {
        const nameMatch = firstInput.name.match(/rows\[(\d+)\]/);
        return nameMatch ? nameMatch[1] : 1;
    }
    return 1;
}

// Global variable to store position salary rules
let positionSalaryRules = {};

// Function to load position salary rules for the selected job
async function loadPositionSalaryRules() {
    const selectedJobId = document.getElementById('job_id').value;
    if (!selectedJobId) {
        positionSalaryRules = {};
        return;
    }

    try {
        const response = await fetch('{{ route("admin.position-wise-salary-rules.get-rules") }}');
        const data = await response.json();

        // Filter rules for the selected job (job-specific or general rules)
        const relevantRules = data.filter(rule =>
            rule.job_id == selectedJobId || rule.job_id === null
        );

        // Store rules in a lookup object
        positionSalaryRules = {};
        relevantRules.forEach(rule => {
            positionSalaryRules[rule.position_id] = parseFloat(rule.amount);
        });

        console.log('Loaded position salary rules:', positionSalaryRules);
    } catch (error) {
        console.error('Error loading position salary rules:', error);
        positionSalaryRules = {};
    }
}

// Function to get salary amount for a position
function getPositionSalary(positionId) {
    return positionSalaryRules[positionId] || 0;
}

function calculateRowTotal(rowNum) {
    let total = 0;
    
    // Method 1: Use currentAttendanceDates if available
    if (currentAttendanceDates && currentAttendanceDates.length > 0) {
    currentAttendanceDates.forEach(date => {
        const input = document.querySelector(`input[name="rows[${rowNum}][attendance][${date}]"]`);
        if (input && input.value) {
            total += parseFloat(input.value) || 0;
        }
    });
    }
    
    // Method 2: Fallback - find all attendance inputs in the row
    if (total === 0) {
        const row = document.querySelector(`tr:has(input[name="rows[${rowNum}][promoter_id]"])`);
        if (row) {
            const attendanceInputs = row.querySelectorAll('input[name*="[attendance]"]');
            attendanceInputs.forEach(input => {
                const name = input.name;
                // Only count actual attendance inputs, not the total/amount inputs
                if (name.includes('[attendance][') && !name.includes('[attendance_total]') && !name.includes('[attendance_amount]')) {
                    total += parseFloat(input.value) || 0;
                }
            });
        }
    }

    const totalInput = document.querySelector(`input[name="rows[${rowNum}][attendance_total]"]`);
    if (totalInput) {
        totalInput.value = total.toFixed(1);
    }

    // Calculate attendance amount based on position salary
    calculateAttendanceAmount(rowNum, total);

    calculateRowNet(rowNum);
}

// Function to calculate attendance amount based on position salary and present days
function calculateAttendanceAmount(rowNum, presentDays) {
    const row = document.querySelector(`tr:has(input[name="rows[${rowNum}][amount]"])`);
    if (!row) return;

    // Get the selected promoter's position ID
    const promoterSelect = row.querySelector(`select[name="rows[${rowNum}][promoter_id]"]`);
    if (!promoterSelect || !promoterSelect.value) {
        // Clear attendance amount if no promoter selected
        const attendanceAmountInput = row.querySelector(`input[name="rows[${rowNum}][attendance_amount]"]`);
        if (attendanceAmountInput) {
            attendanceAmountInput.value = '0.00';
        }

        // Also clear payment amount field
        const paymentAmountInput = row.querySelector(`input[name="rows[${rowNum}][amount]"]`);
        if (paymentAmountInput) {
            paymentAmountInput.value = '0.00';
            calculateRowNet(rowNum);
        }
        return;
    }

    // Get promoter data to find position ID
    const selectedOption = promoterSelect.options[promoterSelect.selectedIndex];
    const promoterId = selectedOption.value;

    // Find promoter in the promoters array to get position ID
    const promoter = promoters.find(p => p.id == promoterId);
    if (!promoter || !promoter.position_id) {
        const attendanceAmountInput = row.querySelector(`input[name="rows[${rowNum}][attendance_amount]"]`);
        if (attendanceAmountInput) {
            attendanceAmountInput.value = '0.00';
        }

        // Also clear payment amount field
        const paymentAmountInput = row.querySelector(`input[name="rows[${rowNum}][amount]"]`);
        if (paymentAmountInput) {
            paymentAmountInput.value = '0.00';
            calculateRowNet(rowNum);
        }
        return;
    }

    // Get position salary from loaded rules
    const positionSalary = getPositionSalary(promoter.position_id);

    // Calculate attendance amount: position salary × present days
    const attendanceAmount = positionSalary * presentDays;

    // Update the attendance amount field
    const attendanceAmountInput = row.querySelector(`input[name="rows[${rowNum}][attendance_amount]"]`);
    if (attendanceAmountInput) {
        attendanceAmountInput.value = attendanceAmount.toFixed(2);
    }

    // Also update the payment amount field
    const paymentAmountInput = row.querySelector(`input[name="rows[${rowNum}][amount]"]`);
    if (paymentAmountInput) {
        paymentAmountInput.value = attendanceAmount.toFixed(2);
        // Trigger net calculation since amount changed
        calculateRowNet(rowNum);
    }
}

function calculateRowNet(rowNum) {
    const row = document.querySelector(`tr:has(input[name="rows[${rowNum}][amount]"])`);
    const amount = parseFloat(row.querySelector(`input[name="rows[${rowNum}][amount]"]`).value) || 0;
    const foodAllowance = parseFloat(row.querySelector(`input[name="rows[${rowNum}][food_allowance]"]`).value) || 0;
    const expenses = parseFloat(row.querySelector(`input[name="rows[${rowNum}][expenses]"]`).value) || 0;
    const accommodationAllowance = parseFloat(row.querySelector(`input[name="rows[${rowNum}][accommodation_allowance]"]`).value) || 0;
    const holdFor8Weeks = parseFloat(row.querySelector(`input[name="rows[${rowNum}][hold_for_8_weeks]"]`).value) || 0;
    const coordinationFee = parseFloat(row.querySelector(`input[name="rows[${rowNum}][coordination_fee]"]`).value) || 0;

    const netAmount = amount + foodAllowance + accommodationAllowance + coordinationFee - expenses - holdFor8Weeks;
    row.querySelector(`input[name="rows[${rowNum}][net_amount]"]`).value = netAmount.toFixed(2);

    calculateGrandTotal();
}

function calculateGrandTotal() {
    const rows = document.querySelectorAll('#promoterRows tr');
    let totalEarnings = 0;
    let totalDeductions = 0;

    rows.forEach(row => {
        const amount = parseFloat(row.querySelector('input[name$="[amount]"]')?.value) || 0;
        const foodAllowance = parseFloat(row.querySelector('input[name$="[food_allowance]"]')?.value) || 0;
        const accommodationAllowance = parseFloat(row.querySelector('input[name$="[accommodation_allowance]"]')?.value) || 0;
        const coordinationFee = parseFloat(row.querySelector('input[name$="[coordination_fee]"]')?.value) || 0;
        const expenses = parseFloat(row.querySelector('input[name$="[expenses]"]')?.value) || 0;
        const holdFor8Weeks = parseFloat(row.querySelector('input[name$="[hold_for_8_weeks]"]')?.value) || 0;

        totalEarnings += amount + foodAllowance + accommodationAllowance + coordinationFee;
        totalDeductions += expenses + holdFor8Weeks;
    });

    const netSalary = totalEarnings - totalDeductions;

    document.getElementById('total-earnings').textContent = `Rs. ${totalEarnings.toFixed(2)}`;
    document.getElementById('total-deductions').textContent = `Rs. ${totalDeductions.toFixed(2)}`;
    document.getElementById('net-salary').textContent = `Rs. ${netSalary.toFixed(2)}`;
}

function generateSheetNumber() {
    const currentDate = new Date();
    const year = currentDate.getFullYear();
    const month = String(currentDate.getMonth() + 1).padStart(2, '0');
    const sheetNumber = `SAL/${year}/${month}/001`;
    document.getElementById('sheet_number').value = sheetNumber;
}

function removeRow(rowNum) {
    Swal.fire({
        title: 'Delete Promoter Row',
        text: 'Are you sure you want to delete this promoter row?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, delete it!',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            const row = document.querySelector(`tr:has(input[name="rows[${rowNum}][amount]"])`);
            if (row) {
                row.remove();
                calculateGrandTotal();

                // Show success message
                Swal.fire({
                    icon: 'success',
                    title: 'Deleted!',
                    text: 'Promoter row has been deleted successfully.',
                    timer: 2000,
                    showConfirmButton: false
                });
            }
        }
    });
}

// Function to load existing salary sheet data for a job
function loadExistingSalarySheets(jobId) {
    if (!jobId) {
        clearAllRows();
        return;
    }

    // Show loading state
    const tbody = document.getElementById('promoterRows');
    tbody.innerHTML = '<tr><td colspan="6" style="text-align: center; padding: 2rem; color: #6b7280;"><div style="display: flex; align-items: center; justify-content: center; gap: 0.5rem;"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12a9 9 0 11-6.219-8.56"></path></svg>Loading salary sheets...</div></td></tr>';

    // Fetch existing salary sheets for this job
    fetch(`/admin/salary-sheets/by-job/${jobId}`, {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success && data.salarySheets && data.salarySheets.length > 0) {
            // Clear existing rows
            clearAllRows();

            // Load each salary sheet as a row
            data.salarySheets.forEach((sheet, index) => {
                loadSalarySheetAsRow(sheet, index);
            });

            // Update grand total
            calculateGrandTotal();

            Swal.fire({
                icon: 'success',
                title: 'Data Loaded',
                text: `Loaded ${data.salarySheets.length} salary sheet(s) for this job.`,
                timer: 2000,
                showConfirmButton: false
            });
        } else {
            // No existing data, just clear rows
            clearAllRows();
            addPromoterRow(); // Add one empty row
        }
    })
    .catch(error => {
        console.error('Error loading salary sheets:', error);
        clearAllRows();
        addPromoterRow(); // Add one empty row
    });
}

// Function to load a single salary sheet as a table row
function loadSalarySheetAsRow(sheet, index) {
    const tbody = document.getElementById('promoterRows');
    const row = document.createElement('tr');

    // Get promoter data
    const promoter = promoters.find(p => p.id == sheet.promoter_id);
    const promoterName = promoter ? promoter.promoter_name : 'Unknown';
    const positionName = promoter && promoter.position ? promoter.position.position_name : 'No Position';

    // Get coordinator data
    const coordinator = coordinators.find(c => c.id == sheet.current_coordinator_id);
    const coordinatorName = coordinator ? coordinator.coordinator_name : '';

    // Build attendance inputs based on current attendance dates
    let attendanceInputs = '';
    if (currentAttendanceDates && currentAttendanceDates.length > 0) {
        currentAttendanceDates.forEach(date => {
            const attendanceValue = sheet.attendance_data && sheet.attendance_data[date] ? sheet.attendance_data[date] : 0;
            attendanceInputs += `<input type="number" class="table-input-small" name="rows[${index}][attendance][${date}]" value="${attendanceValue}" min="0" max="1" step="0.01" onchange="calculateRowTotal(${index})">`;
        });
    } else {
        // Fallback for when no dates are available
        for (let i = 0; i < 6; i++) {
            attendanceInputs += `<input type="number" class="table-input-small" name="rows[${index}][attendance][day${i+1}]" value="0" min="0" max="1" step="0.01" onchange="calculateRowTotal(${index})">`;
        }
    }

    row.innerHTML = `
        <td style="text-align: center; font-weight: 600; background-color: #f8fafc;">${index + 1}</td>
        <td>
            <input type="text" class="table-input" name="rows[${index}][location]" value="${sheet.location || ''}" placeholder="Location">
        </td>
        <td>
            <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 1rem;">
                <select class="table-input-small" name="rows[${index}][promoter_id]" onchange="updatePromoterDetails(${index}, this)">
                    <option value="">Select</option>
                    ${promoters.map(p => {
                        const positionName = p.position ? p.position.position_name : 'No Position';
                        return `<option value="${p.id}"
                                data-name="${p.promoter_name}"
                                data-position="${positionName}"
                                data-phone="${p.phone_no || ''}"
                                data-id-card="${p.identity_card_no || ''}"
                                data-bank="${p.bank_name || ''}"
                                data-account="${p.bank_account_number || ''}"
                                data-status="${p.status || 'inactive'}"
                                data-position-id="${p.position_id || ''}"
                                ${p.id == sheet.promoter_id ? 'selected' : ''}>${p.promoter_id}</option>`;
                    }).join('')}
                </select>
                <input type="text" class="table-input-small table-input-readonly promoter-tooltip" name="rows[${index}][promoter_name]" readonly value="${promoterName}" data-tooltip="">
                <input type="text" class="table-input-small table-input-readonly" name="rows[${index}][position]" readonly value="${positionName}">
            </div>
        </td>
        <td id="attendanceCell-${index}" style="display: ${currentAttendanceDates.length > 0 ? 'table-cell' : 'none'}; width: ${currentAttendanceDates.length > 0 ? (currentAttendanceDates.length * 80 + 160) + 'px' : 'auto'};">
            <div style="display: grid; grid-template-columns: repeat(${currentAttendanceDates.length || 6}, 1fr) 1fr 1.5fr; gap: 0.75rem; width: ${currentAttendanceDates.length > 0 ? (currentAttendanceDates.length * 80 + 160) + 'px' : 'auto'};">
                ${attendanceInputs}
                <input type="number" class="table-input-small calculated-cell" name="rows[${index}][attendance_total]" value="${sheet.attendance_total || 0}" readonly>
                <input type="number" step="0.01" class="table-input-small calculated-cell" name="rows[${index}][attendance_amount]" readonly value="${sheet.attendance_amount || 0}" title="Auto-calculated: Position Salary × Present Days">
            </div>
        </td>
        <td>
            <div style="display: grid; grid-template-columns: repeat(6, 1fr); gap: 0.75rem;">
                <input type="number" step="0.01" class="table-input-small" name="rows[${index}][amount]" value="${sheet.basic_salary || 0}" placeholder="Amount" onchange="calculateGrandTotal()">
                <input type="number" step="0.01" class="table-input-small" name="rows[${index}][food_allowance]" value="${sheet.food_allowance || 0}" placeholder="Food" onchange="calculateGrandTotal()">
                <input type="number" step="0.01" class="table-input-small" name="rows[${index}][expenses]" value="${sheet.expenses || 0}" placeholder="Expenses" onchange="calculateGrandTotal()">
                <input type="number" step="0.01" class="table-input-small" name="rows[${index}][accommodation_allowance]" value="${sheet.accommodation_allowance || 0}" placeholder="Accommodation" onchange="calculateGrandTotal()">
                <input type="number" step="0.01" class="table-input-small" name="rows[${index}][hold_for_8_weeks]" value="${sheet.hold_for_8_weeks || 0}" placeholder="Hold" onchange="calculateGrandTotal()">
                <input type="number" step="0.01" class="table-input-small calculated-cell" name="rows[${index}][net_amount]" readonly value="${sheet.net_salary || 0}" title="Auto-calculated: Total - Expenses - Hold">
            </div>
        </td>
        <td>
            <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 1rem;">
                <select class="table-input-small" name="rows[${index}][coordinator_id]" onchange="updateCoordinatorDisplay(${index}, this)">
                    <option value="">Select</option>
                    ${coordinators.map(coordinator =>
                        `<option value="${coordinator.id}" data-name="${coordinator.coordinator_name}" ${coordinator.id == sheet.current_coordinator_id ? 'selected' : ''}>${coordinator.coordinator_id}</option>`
                    ).join('')}
                </select>
                <input type="text" class="table-input-small table-input-readonly" name="rows[${index}][coordinator_name]" readonly value="${coordinatorName}">
                <input type="number" step="0.01" class="table-input-small" name="rows[${index}][coordination_fee]" value="${sheet.coordination_fee || 0}" placeholder="Fee" onchange="calculateGrandTotal()">
            </div>
        </td>
        <td>
            <button type="button" class="btn-danger" onclick="removeRow(${index})">×</button>
        </td>
    `;

    tbody.appendChild(row);

    // Update tooltip for promoter name
    if (promoter) {
        const promoterNameInput = row.querySelector('input[name="rows[' + index + '][promoter_name]"]');
        updatePromoterTooltipFromOption(promoterNameInput, row.querySelector('select[name="rows[' + index + '][promoter_id]"] option:checked'));
    }
}

// Function to clear all rows
function clearAllRows() {
    const tbody = document.getElementById('promoterRows');
    tbody.innerHTML = '';
    rowCounter = 1;
}


function saveSalarySheet() {
    console.log('=== SAVE SALARY SHEET FUNCTION CALLED ===');
    console.log('Save function called'); // Debug log

    const form = document.getElementById('salarySheetForm');
    console.log('Form element:', form);
    console.log('Form action:', form ? form.action : 'Form not found');
    console.log('Form method:', form ? form.method : 'Form not found');
    console.log('Expected route: admin.salary.enforce');
    form.submit();
}

// Add first row automatically
document.addEventListener('DOMContentLoaded', function() {
    addPromoterRow();
    generateSheetNumber();
});

// JSON Import Functions
function openJsonImportModal() {
    document.getElementById('jsonImportModal').style.display = 'block';
    document.getElementById('jsonDataTextarea').focus();
}

function closeJsonImportModal() {
    document.getElementById('jsonImportModal').style.display = 'none';
    document.getElementById('jsonDataTextarea').value = '';
    document.getElementById('jsonImportStatus').style.display = 'none';
}

function importJsonData() {
    const jsonText = document.getElementById('jsonDataTextarea').value.trim();
    
    if (!jsonText) {
        showJsonStatus('Please paste JSON data first.', 'error');
        return;
    }
    
    try {
        const jsonData = JSON.parse(jsonText);
        console.log('Parsed JSON data:', jsonData);
        
        // Validate required fields
        if (!jsonData.job_id || !jsonData.status) {
            showJsonStatus('JSON must contain job_id and status fields.', 'error');
            return;
        }
        
        // Update form fields
        updateFormFields(jsonData);
        
        // Update table rows
        updateTableRows(jsonData);
        
        showJsonStatus('JSON data imported successfully!', 'success');
        
        // Close modal after a short delay
        setTimeout(() => {
            closeJsonImportModal();
        }, 1500);
        
    } catch (error) {
        console.error('JSON parsing error:', error);
        showJsonStatus('Invalid JSON format. Please check your data.', 'error');
    }
}

function showJsonStatus(message, type) {
    const statusDiv = document.getElementById('jsonImportStatus');
    statusDiv.style.display = 'block';
    statusDiv.textContent = message;
    statusDiv.style.backgroundColor = type === 'success' ? '#d4edda' : '#f8d7da';
    statusDiv.style.color = type === 'success' ? '#155724' : '#721c24';
    statusDiv.style.border = `1px solid ${type === 'success' ? '#c3e6cb' : '#f5c6cb'}`;
}

function updateFormFields(jsonData) {
    console.log('Updating form fields with:', jsonData);
    
    // Update main form fields
    if (jsonData.sheet_number) {
        document.getElementById('sheet_number').value = jsonData.sheet_number;
    }
    
    if (jsonData.job_id) {
        const jobSelect = document.getElementById('job_id');
        jobSelect.value = jsonData.job_id;
        // Trigger job change to update attendance dates
        updateAttendanceDates();
    }
    
    if (jsonData.status) {
        const statusSelect = document.querySelector('select[name="status"]');
        if (statusSelect) {
            statusSelect.value = jsonData.status;
        }
    }
    
    if (jsonData.location) {
        const locationInput = document.querySelector('input[name="location"]');
        if (locationInput) {
            locationInput.value = jsonData.location;
        }
    }
    
    if (jsonData.notes) {
        const notesTextarea = document.querySelector('textarea[name="notes"]');
        if (notesTextarea) {
            notesTextarea.value = jsonData.notes;
        }
    }
    
    // Add hidden input for salary sheet ID if it exists (for updates)
    if (jsonData.salary_sheet_id) {
        let existingInput = document.getElementById('salary_sheet_id');
        if (existingInput) {
            existingInput.value = jsonData.salary_sheet_id;
        } else {
            let hiddenInput = document.createElement('input');
                hiddenInput.type = 'hidden';
            hiddenInput.id = 'salary_sheet_id';
            hiddenInput.name = 'salary_sheet_id';
            hiddenInput.value = jsonData.salary_sheet_id;
            document.querySelector('form').appendChild(hiddenInput);
        }
    }
}

function updateTableRows(jsonData) {
    console.log('Updating table rows with:', jsonData.rows);
    
    if (!jsonData.rows || typeof jsonData.rows !== 'object') {
        console.log('No rows data found in JSON');
        return;
    }

    // Clear existing rows
    clearAllRows();
    
    // Add rows based on JSON data
    let rowIndex = 0;
    for (const [rowKey, rowData] of Object.entries(jsonData.rows)) {
        console.log(`Processing row ${rowKey}:`, rowData);
        
        if (rowData.promoter_id) {
            addPromoterRowFromJson(rowData, rowIndex);
            rowIndex++;
        }
    }
    
    // Update grand total after all rows are processed and calculations are done
    setTimeout(() => {
        console.log('Updating grand total after all rows processed...');
        calculateGrandTotal();
        console.log('Grand total updated after pulling data');
    }, 200);
}

function addPromoterRowFromJson(rowData, index) {
    console.log(`Adding promoter row ${index} with data:`, rowData);
    
    const tbody = document.getElementById('promoterRows');
    const row = document.createElement('tr');
    
    // Generate attendance inputs based on JSON dates
    let attendanceInputs = '';
    const attendanceDates = Object.keys(rowData.attendance || {});
    
    if (attendanceDates.length > 0) {
        // Use dates from JSON
        attendanceInputs = attendanceDates.map(date =>
            `<input type="number" class="table-input-small" name="rows[${index + 1}][attendance][${date}]" min="0" max="1" step="1" onchange="calculateRowTotal(${index + 1})" placeholder="0/1" value="${rowData.attendance[date] || 0}">`
        ).join('');
    } else {
        // Fallback: create 6 default attendance inputs
        attendanceInputs = Array.from({length: 6}, (_, i) =>
            `<input type="number" class="table-input-small" name="rows[${index + 1}][attendance][day${i+1}]" min="0" max="1" step="1" onchange="calculateRowTotal(${index + 1})" placeholder="0/1">`
        ).join('');
    }
    
    row.innerHTML = `
        <td style="text-align: center; font-weight: bold;">${index + 1}</td>
        <td>
            <input type="text" class="table-input" name="rows[${index + 1}][location]" placeholder="Location" value="${rowData.location || ''}">
        </td>
        <td>
            <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 1rem;">
                <select class="table-input-small" name="rows[${index + 1}][promoter_id]" onchange="updatePromoterDetails(${index + 1}, this)">
                    <option value="">Select</option>
                    ${promoters.map(promoter => {
                        const positionName = promoter.position ? promoter.position.position_name : 'No Position';
                        const selected = promoter.id == rowData.promoter_id ? 'selected' : '';
                        return `<option value="${promoter.id}"
                                data-name="${promoter.promoter_name}"
                                data-position="${positionName}"
                                data-phone="${promoter.phone_no || ''}"
                                data-id-card="${promoter.identity_card_no || ''}"
                                data-bank="${promoter.bank_name || ''}"
                                data-account="${promoter.bank_account_number || ''}"
                                data-status="${promoter.status || 'inactive'}"
                                data-position-id="${promoter.position_id || ''}"
                                ${selected}>${promoter.promoter_id}</option>`;
                    }).join('')}
                </select>
                <input type="text" class="table-input-small table-input-readonly promoter-tooltip" name="rows[${index + 1}][promoter_name]" readonly data-tooltip="" value="${rowData.promoter_name || ''}">
                <input type="text" class="table-input-small table-input-readonly" name="rows[${index + 1}][position]" readonly value="${rowData.position || ''}">
            </div>
        </td>
        <td id="attendanceCell-${index + 1}" style="display: table-cell; width: ${(attendanceDates.length || 6) * 80 + 160}px;">
            <div style="display: grid; grid-template-columns: repeat(${attendanceDates.length || 6}, 1fr) 1fr 1.5fr; gap: 0.75rem; width: ${(attendanceDates.length || 6) * 80 + 160}px;">
                ${attendanceInputs}
                <input type="number" class="table-input-small calculated-cell" name="rows[${index + 1}][attendance_total]" readonly value="${rowData.attendance_total || 0}">
                <input type="number" step="0.01" class="table-input-small calculated-cell" name="rows[${index + 1}][attendance_amount]" readonly title="Auto-calculated: Position Salary × Present Days" value="${rowData.attendance_amount || 0}">
            </div>
        </td>
        <td>
            <div style="display: grid; grid-template-columns: repeat(6, 1fr); gap: 0.75rem;">
                <input type="number" step="0.01" class="table-input-small calculated-cell" name="rows[${index + 1}][amount]" readonly title="Auto-calculated from Attendance Amount" value="${rowData.amount || 0}">
                <input type="number" step="0.01" class="table-input-small" name="rows[${index + 1}][food_allowance]" onchange="calculateRowNet(${index + 1})" value="${rowData.food_allowance || 0}">
                <input type="number" step="0.01" class="table-input-small" name="rows[${index + 1}][accommodation_allowance]" onchange="calculateRowNet(${index + 1})" value="${rowData.accommodation_allowance || 0}">
                <input type="number" step="0.01" class="table-input-small" name="rows[${index + 1}][expenses]" onchange="calculateRowNet(${index + 1})" value="${rowData.expenses || 0}">
                <input type="number" step="0.01" class="table-input-small" name="rows[${index + 1}][hold_for_8_weeks]" onchange="calculateRowNet(${index + 1})" value="${rowData.hold_for_8_weeks || 0}">
                <input type="number" step="0.01" class="table-input-small calculated-cell" name="rows[${index + 1}][net_amount]" readonly value="${rowData.net_amount || 0}">
            </div>
        </td>
        <td>
            <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 1rem;">
                <select class="table-input-small" name="rows[${index + 1}][coordinator_id]" onchange="updateCoordinatorDisplay(${index + 1}, this)">
                    <option value="">Select</option>
                    ${coordinators.map(coordinator => {
                        const selected = (coordinator.id == rowData.coordinator_id || coordinator.id == parseInt(rowData.coordinator_id)) ? 'selected' : '';
                        console.log(`Coordinator comparison: coordinator.id=${coordinator.id}, rowData.coordinator_id=${rowData.coordinator_id}, selected=${selected}`);
                        return `<option value="${coordinator.id}" data-name="${coordinator.coordinator_name}" ${selected}>${coordinator.coordinator_id}</option>`;
                    }).join('')}
                </select>
                <input type="text" class="table-input-small table-input-readonly" name="rows[${index + 1}][current_coordinator]" readonly value="${rowData.current_coordinator || ''}">
                <input type="number" step="0.01" class="table-input-small" name="rows[${index + 1}][coordination_fee]" onchange="calculateRowNet(${index + 1})" value="${rowData.coordination_fee || 0}">
            </div>
        </td>
        <td>
            <button type="button" class="btn-danger" onclick="removeRow(${index + 1})">×</button>
        </td>
    `;
    
    tbody.appendChild(row);
    
    // Update promoter details after adding the row
    setTimeout(() => {
        const promoterSelect = row.querySelector(`select[name="rows[${index + 1}][promoter_id]"]`);
        if (promoterSelect && promoterSelect.value) {
            updatePromoterDetails(index + 1, promoterSelect);
        }
        
        const coordinatorSelect = row.querySelector(`select[name="rows[${index + 1}][coordinator_id]"]`);
        if (coordinatorSelect && coordinatorSelect.value) {
            updateCoordinatorDisplay(index + 1, coordinatorSelect);
        }
        
        // Trigger all calculations after data is loaded
        calculateRowTotal(index + 1);
        
        // Get present days for attendance amount calculation
        const totalInput = row.querySelector(`input[name="rows[${index + 1}][attendance_total]"]`);
        const presentDays = totalInput ? parseFloat(totalInput.value) || 0 : 0;
        calculateAttendanceAmount(index + 1, presentDays);
        
        calculateRowNet(index + 1);
        
        // Ensure amount field is set from attendance amount
        const attendanceAmountInput = row.querySelector(`input[name="rows[${index + 1}][attendance_amount]"]`);
        const amountInput = row.querySelector(`input[name="rows[${index + 1}][amount]"]`);
        if (attendanceAmountInput && amountInput) {
            amountInput.value = attendanceAmountInput.value;
        }
    }, 100);
    
    console.log(`Row ${index + 1} added successfully`);
}

// Pull Data Functions
function pullExistingData() {
    const jobSelect = document.getElementById('job_id');
    const selectedJobId = jobSelect.value;
    
    if (!selectedJobId) {
        showPullDataStatus('Please select a job first.', 'error');
        return;
    }
    
    console.log('Pulling existing data for job:', selectedJobId);
    
    // Show loading state
    const pullDataBtn = document.getElementById('pullDataBtn');
    const originalText = pullDataBtn.innerHTML;
    pullDataBtn.disabled = true;
    pullDataBtn.innerHTML = '<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 4px;"><path d="M21 12a9 9 0 11-6.219-8.56"></path></svg>Pulling...';
    
    // First, get the most recent salary sheet for this job
    fetch(`/admin/salary-sheets/by-job/${selectedJobId}`)
        .then(response => response.json())
    .then(data => {
            console.log('Salary sheets for job:', data);
            
            if (data.success && data.salarySheets && data.salarySheets.length > 0) {
                // Get the most recent salary sheet (first in the array)
                const mostRecentSheet = data.salarySheets[0];
                console.log('Most recent salary sheet:', mostRecentSheet);
                
                // Now fetch the JSON data for this salary sheet
                return fetch(`/admin/salary-sheets/${mostRecentSheet.id}/json`);
        } else {
                throw new Error('No salary sheets found for this job');
            }
        })
        .then(response => response.json())
        .then(jsonData => {
            console.log('Fetched JSON data:', jsonData);
            
            // Update form fields
            updateFormFields(jsonData);
            
            // Update table rows
            updateTableRows(jsonData);
            
            // Trigger all calculations after data is loaded
            setTimeout(() => {
                console.log('Triggering all calculations after pull data...');
                
                // Calculate attendance totals and amounts for each row
                const rows = document.querySelectorAll('#promoterRows tr');
                rows.forEach((row, index) => {
                    const rowNum = index + 1;
                    console.log(`Calculating for row ${rowNum}`);
                    
                    // Trigger attendance calculations
                    calculateRowTotal(rowNum);
                    
                    // Get present days for attendance amount calculation
                    const totalInput = row.querySelector(`input[name="rows[${rowNum}][attendance_total]"]`);
                    const presentDays = totalInput ? parseFloat(totalInput.value) || 0 : 0;
                    calculateAttendanceAmount(rowNum, presentDays);
                    
                    // Trigger net amount calculations
                    calculateRowNet(rowNum);
                    
                    // Ensure amount field is set from attendance amount
                    const attendanceAmountInput = row.querySelector(`input[name="rows[${rowNum}][attendance_amount]"]`);
                    const amountInput = row.querySelector(`input[name="rows[${rowNum}][amount]"]`);
                    if (attendanceAmountInput && amountInput) {
                        amountInput.value = attendanceAmountInput.value;
                    }
                });
                
                // Update grand total
                calculateGrandTotal();
                
                console.log('All calculations completed after pull data');
            }, 300);
            
            showPullDataStatus('Data pulled successfully!', 'success');
            
        })
    .catch(error => {
            console.error('Error pulling data:', error);
            showPullDataStatus('No existing data found for this job.', 'error');
    })
    .finally(() => {
        // Reset button state
            pullDataBtn.disabled = false;
            pullDataBtn.innerHTML = originalText;
        });
}

function showPullDataStatus(message, type) {
    // Create or update status div
    let statusDiv = document.getElementById('pullDataStatus');
    if (!statusDiv) {
        statusDiv = document.createElement('div');
        statusDiv.id = 'pullDataStatus';
        statusDiv.style.cssText = 'position: fixed; top: 20px; right: 20px; padding: 1rem; border-radius: 4px; z-index: 9999; max-width: 300px;';
        document.body.appendChild(statusDiv);
    }
    
    statusDiv.style.display = 'block';
    statusDiv.textContent = message;
    statusDiv.style.backgroundColor = type === 'success' ? '#d4edda' : '#f8d7da';
    statusDiv.style.color = type === 'success' ? '#155724' : '#721c24';
    statusDiv.style.border = `1px solid ${type === 'success' ? '#c3e6cb' : '#f5c6cb'}`;
    
    // Auto-hide after 3 seconds
    setTimeout(() => {
        statusDiv.style.display = 'none';
    }, 3000);
}

// Position Wise Salary Rule Modal Functions
let salaryRuleCounter = 0;
let selectedPositions = new Set();

function showModalPreloader() {
    document.getElementById('modalPreloader').style.display = 'flex';
    document.getElementById('modalMainContent').style.display = 'none';
}

function hideModalPreloader() {
    document.getElementById('modalPreloader').style.display = 'none';
    document.getElementById('modalMainContent').style.display = 'block';
}

function updatePreloaderText(text) {
    const preloaderText = document.querySelector('.preloader-text');
    if (preloaderText) {
        preloaderText.textContent = text;
    }
}

function reloadModalContent() {
    // Clear existing content
    clearSalaryRules();

    // Show preloader
    updatePreloaderText('Reloading salary rules...');
    showModalPreloader();

    // Reload existing rules
    loadExistingRules();
}

function openSalaryRuleModal() {
    const selectedJobId = document.getElementById('job_id').value;
    if (!selectedJobId) {
        Swal.fire({
            icon: 'warning',
            title: 'Job Selection Required',
            text: 'Please select a job first before adding salary rules.',
            confirmButtonText: 'OK'
        });
        return;
    }

    document.getElementById('salaryRuleModal').style.display = 'block';
    document.body.style.overflow = 'hidden';

    // Show preloader
    showModalPreloader();

    // Load existing rules
    loadExistingRules();
}

function closeSalaryRuleModal() {
    document.getElementById('salaryRuleModal').style.display = 'none';
    document.body.style.overflow = 'auto';
    clearSalaryRules();
    hideModalPreloader();
}

function getAvailablePositions() {
    // Get all unique positions from promoters
    const allPositions = promoters
        .filter(p => p.position)
        .map(p => p.position)
        .filter((position, index, self) =>
            index === self.findIndex(p => p.id === position.id)
        );

    // Get positions used in existing rules
    const existingRulePositions = new Set();
    const existingRuleElements = document.querySelectorAll('.existing-rule-row');
    existingRuleElements.forEach(element => {
        const positionElement = element.querySelector('.rule-info strong');
        if (positionElement) {
            const positionName = positionElement.textContent;
            const position = allPositions.find(p => p.position_name === positionName);
            if (position) {
                existingRulePositions.add(position.id);
            }
        }
    });

    // Get positions used in new rules
    const newRulePositions = new Set();
    const newRuleElements = document.querySelectorAll('.salary-rule-row');
    newRuleElements.forEach(element => {
        const select = element.querySelector('select[name*="[position_id]"]');
        if (select && select.value) {
            newRulePositions.add(parseInt(select.value));
        }
    });

    // Combine both sets
    const usedPositions = new Set([...existingRulePositions, ...newRulePositions]);

    // Filter out positions that are already used
    return allPositions.filter(position => !usedPositions.has(position.id));
}

function updateAddButtonState() {
    const addBtn = document.getElementById('addNewRuleBtn');
    const availablePositions = getAvailablePositions();

    // Get total positions count
    const totalPositions = promoters
        .filter(p => p.position)
        .map(p => p.position)
        .filter((position, index, self) =>
            index === self.findIndex(p => p.id === position.id)
        ).length;

    // Get used positions count
    const usedPositionsCount = totalPositions - availablePositions.length;

    if (availablePositions.length === 0) {
        addBtn.disabled = true;
        addBtn.title = `All ${totalPositions} positions have been used (${usedPositionsCount} existing + new rules)`;
    } else {
        addBtn.disabled = false;
        addBtn.title = `Add new rule (${availablePositions.length} of ${totalPositions} positions available)`;
    }
}

function addSalaryRuleRow() {
    // Check if there are available positions
    const availablePositions = getAvailablePositions();

    if (availablePositions.length === 0) {
        const totalPositions = promoters
            .filter(p => p.position)
            .map(p => p.position)
            .filter((position, index, self) =>
                index === self.findIndex(p => p.id === position.id)
            ).length;

        Swal.fire({
            icon: 'info',
            title: 'All Positions Added',
            text: `All ${totalPositions} positions have already been added as rules for this job.`,
            confirmButtonText: 'OK'
        });
        return;
    }

    salaryRuleCounter++;
    const container = document.getElementById('salaryRulesContainer');

    const row = document.createElement('div');
    row.className = 'salary-rule-row';
    row.id = `salaryRuleRow-${salaryRuleCounter}`;

    // Get the selected job ID from the top form
    const selectedJobId = document.getElementById('job_id').value;
    const selectedJob = jobs.find(job => job.id == selectedJobId);

    const positionOptions = availablePositions.map(position =>
        `<option value="${position.id}">${position.position_name}</option>`
    ).join('');

    row.innerHTML = `
        <select name="rules[${salaryRuleCounter}][position_id]" data-row-id="${salaryRuleCounter}" required>
            <option value="">Select Position</option>
            ${positionOptions}
        </select>
        <input type="hidden" name="rules[${salaryRuleCounter}][job_id]" value="${selectedJobId}">
        <div style="padding: 0.75rem; background: #f3f4f6; border: 1px solid #d1d5db; border-radius: 0.375rem; font-size: 0.875rem; color: #374151;">
            ${selectedJob ? selectedJob.job_number + ' - ' + selectedJob.job_name : 'No Job Selected'}
        </div>
        <input type="number" step="0.01" name="rules[${salaryRuleCounter}][amount]" placeholder="Amount" min="0" required>
        <input type="text" name="rules[${salaryRuleCounter}][description]" placeholder="Description (optional)">
        <button type="button" class="remove-rule-btn" data-row-id="${salaryRuleCounter}">×</button>
    `;

    container.appendChild(row);
    updateSelectedPositions();
    updateAddButtonState();
}

function removeSalaryRuleRow(rowId) {
    const row = document.getElementById(`salaryRuleRow-${rowId}`);
    if (row) {
        row.remove();
        updateSelectedPositions();
        updateAddButtonState();
    }
}

function updateSelectedPositions() {
    selectedPositions.clear();
    const rows = document.querySelectorAll('.salary-rule-row');
    rows.forEach(row => {
        const select = row.querySelector('select[name*="[position_id]"]');
        if (select && select.value) {
            selectedPositions.add(parseInt(select.value));
        }
    });

    // Update all position selects to show/hide options
    rows.forEach(row => {
        const select = row.querySelector('select[name*="[position_id]"]');
        if (select) {
            const currentValue = select.value;
            const availablePositions = promoters
                .filter(p => p.position && !selectedPositions.has(p.position.id))
                .map(p => p.position)
                .filter((position, index, self) =>
                    index === self.findIndex(p => p.id === position.id)
                );

            // Add current selection back if it exists
            if (currentValue) {
                const currentPosition = promoters.find(p => p.position && p.position.id == currentValue)?.position;
                if (currentPosition && !availablePositions.find(p => p.id == currentValue)) {
                    availablePositions.push(currentPosition);
                }
            }

            const positionOptions = availablePositions.map(position =>
                `<option value="${position.id}" ${position.id == currentValue ? 'selected' : ''}>${position.position_name}</option>`
            ).join('');

            select.innerHTML = `<option value="">Select Position</option>${positionOptions}`;
            if (currentValue) {
                select.value = currentValue;
            }
        }
    });

    updateAddButtonState();
}

function clearSalaryRules() {
    document.getElementById('salaryRulesContainer').innerHTML = '';
    document.getElementById('existingRulesContainer').innerHTML = '';
    salaryRuleCounter = 0;
    selectedPositions.clear();
    updateAddButtonState();
}

function deleteExistingRule(ruleId) {
    Swal.fire({
        title: 'Delete Salary Rule',
        text: 'Are you sure you want to delete this salary rule?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, delete it!',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            // Continue with deletion
            console.log('Deleting rule with ID:', ruleId);

            // Send delete request via AJAX using Laravel's method spoofing
            const formData = new FormData();
            formData.append('_method', 'DELETE');
            formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));

            fetch(`/admin/position-wise-salary-rules/${ruleId}`, {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                },
                body: formData
            })
            .then(response => {
                console.log('Delete response status:', response.status);
                console.log('Delete response headers:', response.headers);
                if (response.ok) {
                    // Try to parse as JSON, fallback to success if not JSON
                    return response.text().then(text => {
                        console.log('Delete response text:', text);
                        try {
                            return JSON.parse(text);
                        } catch (e) {
                            console.log('Failed to parse JSON, treating as success');
                            return { success: true };
                        }
                    });
                } else {
                    return response.text().then(text => {
                        console.log('Error response text:', text);
                        throw new Error(`Delete request failed with status: ${response.status} - ${text}`);
                    });
                }
            })
            .then(data => {
                console.log('Delete response data:', data);
                if (data.success !== false) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: 'Salary rule deleted successfully.',
                        confirmButtonText: 'OK'
                    });
                    // Reload modal content to get fresh data
                    reloadModalContent();
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Failed to delete salary rule: ' + (data.message || 'Unknown error'),
                        confirmButtonText: 'OK'
                    });
                }
            })
            .catch(error => {
                console.error('Delete error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Failed to delete salary rule: ' + error.message,
                    confirmButtonText: 'OK'
                });
            });
        }
    });
}

function loadExistingRules() {
    const selectedJobId = document.getElementById('job_id').value;
    if (!selectedJobId) {
        document.getElementById('existingRulesContainer').innerHTML = '<p style="color: #6b7280; text-align: center; padding: 2rem;">Please select a job first</p>';
        hideModalPreloader();
        return;
    }

    // Load existing salary rules via AJAX
    fetch('{{ route("admin.position-wise-salary-rules.get-rules") }}')
        .then(response => response.json())
        .then(data => {
            // Filter rules for the selected job or general rules (no job_id)
            const relevantRules = data.filter(rule =>
                rule.job_id == selectedJobId || rule.job_id === null
            );

            const container = document.getElementById('existingRulesContainer');

            if (relevantRules.length === 0) {
                container.innerHTML = '<p style="color: #6b7280; text-align: center; padding: 2rem;">No existing rules found for this job</p>';
                updateAddButtonState();
                hideModalPreloader();
                return;
            }

            container.innerHTML = '';

            relevantRules.forEach(rule => {
                const ruleRow = document.createElement('div');
                ruleRow.className = 'existing-rule-row';
                ruleRow.id = `existingRule-${rule.id}`;

                ruleRow.innerHTML = `
                    <div class="rule-info">
                        <strong>${rule.position.position_name}</strong>
                    </div>
                    <div class="rule-info">
                        Rs. ${parseFloat(rule.amount).toFixed(2)}
                    </div>
                    <div class="rule-info">
                        ${rule.description || 'No description'}
                    </div>
                    <button type="button" class="delete-rule-btn" data-rule-id="${rule.id}" title="Delete Rule">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="3,6 5,6 21,6"></polyline>
                            <path d="M19,6v14a2,2 0 0,1 -2,2H7a2,2 0 0,1 -2,-2V6m3,0V4a2,2 0 0,1 2,-2h4a2,2 0 0,1 2,2v2"></path>
                        </svg>
                    </button>
                `;

                container.appendChild(ruleRow);
            });

            updateAddButtonState();

            // Hide preloader after data is loaded
            hideModalPreloader();
        })
        .catch(error => {
            console.error('Error loading existing rules:', error);
            document.getElementById('existingRulesContainer').innerHTML = '<p style="color: #dc2626; text-align: center; padding: 2rem;">Error loading rules</p>';
            updateAddButtonState();

            // Hide preloader even on error
            hideModalPreloader();
        });
}

// Event delegation for all modal buttons
document.addEventListener('click', function(e) {
    // Handle delete buttons
    if (e.target.closest('.delete-rule-btn')) {
        const button = e.target.closest('.delete-rule-btn');
        const ruleId = button.getAttribute('data-rule-id');
        if (ruleId) {
            deleteExistingRule(ruleId);
        }
    }

    // Handle modal close button
    if (e.target.id === 'modalCloseBtn' || e.target.closest('#modalCloseBtn')) {
        closeSalaryRuleModal();
    }

    // Handle refresh button
    if (e.target.id === 'refreshModalBtn' || e.target.closest('#refreshModalBtn')) {
        reloadModalContent();
    }

    // Handle add new rule button
    if (e.target.id === 'addNewRuleBtn' || e.target.closest('#addNewRuleBtn')) {
        addSalaryRuleRow();
    }

    // Handle save rules button
    if (e.target.id === 'saveRulesBtn' || e.target.closest('#saveRulesBtn')) {
        saveSalaryRules();
    }

    // Handle cancel button
    if (e.target.id === 'cancelModalBtn' || e.target.closest('#cancelModalBtn')) {
        closeSalaryRuleModal();
    }

    // Handle modal background click to close
    if (e.target.id === 'salaryRuleModal') {
        closeSalaryRuleModal();
    }

    // Handle remove rule buttons
    if (e.target.closest('.remove-rule-btn')) {
        const button = e.target.closest('.remove-rule-btn');
        const rowId = button.getAttribute('data-row-id');
        if (rowId) {
            removeSalaryRuleRow(rowId);
        }
    }
});

// Handle position select changes
document.addEventListener('change', function(e) {
    if (e.target.matches('select[name*="[position_id]"]')) {
        updateSelectedPositions();
    }
});

// Handle keyboard events
document.addEventListener('keydown', function(e) {
    // Close modal on ESC key
    if (e.key === 'Escape') {
        const modal = document.getElementById('salaryRuleModal');
        if (modal && modal.style.display === 'block') {
            closeSalaryRuleModal();
        }
    }
});

function saveSalaryRules() {
    const rows = document.querySelectorAll('.salary-rule-row');
    const rules = [];

    rows.forEach(row => {
        const positionId = row.querySelector('select[name*="[position_id]"]').value;
        const jobId = row.querySelector('input[name*="[job_id]"]').value;
        const amount = row.querySelector('input[name*="[amount]"]').value;
        const description = row.querySelector('input[name*="[description]"]').value;

        if (positionId && amount) {
            rules.push({
                position_id: positionId,
                job_id: jobId || null,
                amount: parseFloat(amount),
                description: description
            });
        }
    });

    if (rules.length === 0) {
        Swal.fire({
            icon: 'warning',
            title: 'No Salary Rules',
            text: 'Please add at least one salary rule.',
            confirmButtonText: 'OK'
        });
        return;
    }

    // Show preloader for saving
    updatePreloaderText('Saving salary rules...');
    showModalPreloader();

    // Send data via AJAX
    fetch('{{ route("admin.position-wise-salary-rules.store-multiple") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ rules: rules })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: data.message,
                confirmButtonText: 'OK'
            });
            // Reload modal content instead of closing
            reloadModalContent();
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Error: ' + data.message,
                confirmButtonText: 'OK'
            });
            hideModalPreloader();
        }
    })
    .catch(error => {
        console.error('Error:', error);
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Failed to save salary rules. Please try again.',
            confirmButtonText: 'OK'
        });
        hideModalPreloader();
    });
}

// Job Settings Modal Functions
function openJobSettingsModal() {
    const selectedJobId = document.getElementById('job_id').value;
    if (!selectedJobId) {
        Swal.fire({
            icon: 'warning',
            title: 'Job Selection Required',
            text: 'Please select a job first before opening job settings.',
            confirmButtonText: 'OK'
        });
        return;
    }

    document.getElementById('jobSettingsModal').style.display = 'block';
    document.body.style.overflow = 'hidden';

    // Load current job settings
    loadJobSettings(selectedJobId);
}

function closeJobSettingsModal() {
    document.getElementById('jobSettingsModal').style.display = 'none';
    document.body.style.overflow = 'auto';
}

function switchTab(tabName) {
    // Remove active class from all tabs and content
    document.querySelectorAll('.tab-btn').forEach(btn => btn.classList.remove('active'));
    document.querySelectorAll('.tab-content').forEach(content => content.classList.remove('active'));

    // Add active class to selected tab and content
    document.getElementById(tabName + 'Tab').classList.add('active');
    document.getElementById(tabName + 'TabContent').classList.add('active');
}

function loadJobSettings(jobId) {
    // Find the selected job from the jobs array
    const selectedJob = jobs.find(job => job.id == jobId);
    if (selectedJob) {
        // Load job data into form fields
        document.getElementById('defaultCoordinatorFee').value = selectedJob.default_coordinator_fee || '';
        document.getElementById('defaultHoldFor8Weeks').value = selectedJob.default_hold_for_8_weeks || '';
        document.getElementById('jobDescription').value = selectedJob.description || '';
        document.getElementById('defaultFoodAllowance').value = selectedJob.default_food_allowance || '';
        document.getElementById('defaultAccommodationAllowance').value = selectedJob.default_accommodation_allowance || '';
        document.getElementById('defaultExpenses').value = selectedJob.default_expenses || '';
        document.getElementById('defaultLocation').value = selectedJob.default_location || '';
        document.getElementById('locationNotes').value = selectedJob.location_notes || '';
    }
}

function saveJobSettings() {
    const selectedJobId = document.getElementById('job_id').value;
    if (!selectedJobId) {
        Swal.fire({
            icon: 'warning',
            title: 'Job Selection Required',
            text: 'Please select a job first.',
            confirmButtonText: 'OK'
        });
        return;
    }

    const saveBtn = document.getElementById('saveJobSettingsBtn');
    const originalText = saveBtn.innerHTML;

    // Show loading state
    saveBtn.disabled = true;
    saveBtn.innerHTML = `
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 8px; animation: spin 1s linear infinite;">
            <path d="M21 12a9 9 0 11-6.219-8.56"></path>
        </svg>
        Saving...
    `;

    const settings = {
        default_coordinator_fee: document.getElementById('defaultCoordinatorFee').value,
        default_hold_for_8_weeks: document.getElementById('defaultHoldFor8Weeks').value,
        description: document.getElementById('jobDescription').value,
        default_food_allowance: document.getElementById('defaultFoodAllowance').value,
        default_accommodation_allowance: document.getElementById('defaultAccommodationAllowance').value,
        default_expenses: document.getElementById('defaultExpenses').value,
        default_location: document.getElementById('defaultLocation').value,
        location_notes: document.getElementById('locationNotes').value
    };

    // Send data to server
    fetch(`/admin/jobs/${selectedJobId}/update-settings`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json'
        },
        body: JSON.stringify(settings)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: 'Job settings saved successfully!',
                confirmButtonText: 'OK'
            });
            closeJobSettingsModal();

            // Update the jobs array with the new data
            const jobIndex = jobs.findIndex(job => job.id == selectedJobId);
            if (jobIndex !== -1) {
                jobs[jobIndex] = { ...jobs[jobIndex], ...data.job };
            }
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Error: ' + data.message,
                confirmButtonText: 'OK'
            });
        }
    })
    .catch(error => {
        console.error('Error:', error);
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Failed to save job settings. Please try again.',
            confirmButtonText: 'OK'
        });
    })
    .finally(() => {
        // Restore button state
        saveBtn.disabled = false;
        saveBtn.innerHTML = originalText;
    });
}

function applySettingsToAllRows() {
    const coordinatorFee = document.getElementById('defaultCoordinatorFee').value;
    const holdFor8Weeks = document.getElementById('defaultHoldFor8Weeks').value;
    const foodAllowance = document.getElementById('defaultFoodAllowance').value;
    const accommodationAllowance = document.getElementById('defaultAccommodationAllowance').value;
    const expenses = document.getElementById('defaultExpenses').value;
    const location = document.getElementById('defaultLocation').value;

    // Apply to all existing rows
    const rows = document.querySelectorAll('#promoterRows tr');
    rows.forEach(row => {
        const rowNum = getRowNumberFromElement(row);

        if (coordinatorFee) {
            const coordinatorFeeInput = row.querySelector(`input[name="rows[${rowNum}][coordination_fee]"]`);
            if (coordinatorFeeInput) coordinatorFeeInput.value = coordinatorFee;
        }

        if (holdFor8Weeks) {
            const holdInput = row.querySelector(`input[name="rows[${rowNum}][hold_for_8_weeks]"]`);
            if (holdInput) holdInput.value = holdFor8Weeks;
        }

        if (foodAllowance) {
            const foodInput = row.querySelector(`input[name="rows[${rowNum}][food_allowance]"]`);
            if (foodInput) foodInput.value = foodAllowance;
        }

        if (accommodationAllowance) {
            const accommodationInput = row.querySelector(`input[name="rows[${rowNum}][accommodation_allowance]"]`);
            if (accommodationInput) accommodationInput.value = accommodationAllowance;
        }

        if (expenses) {
            const expensesInput = row.querySelector(`input[name="rows[${rowNum}][expenses]"]`);
            if (expensesInput) expensesInput.value = expenses;
        }

        if (location) {
            const locationInput = row.querySelector(`input[name="rows[${rowNum}][location]"]`);
            if (locationInput) locationInput.value = location;
        }

        // Recalculate net amount for this row
        calculateRowNet(rowNum);
    });

    // Recalculate grand total
    calculateGrandTotal();

    Swal.fire({
        icon: 'success',
        title: 'Success!',
        text: 'Settings applied to all rows successfully!',
        confirmButtonText: 'OK'
    });
}

function getRowNumberFromElement(element) {
    const firstInput = element.querySelector('input[name*="[promoter_id]"]');
    if (firstInput) {
        const nameMatch = firstInput.name.match(/rows\[(\d+)\]/);
        return nameMatch ? nameMatch[1] : 1;
    }
    return 1;
}

// Event delegation for Job Settings Modal
document.addEventListener('click', function(e) {
    // Handle job settings modal buttons
    if (e.target.id === 'jobSettingsCloseBtn' || e.target.closest('#jobSettingsCloseBtn')) {
        closeJobSettingsModal();
    }

    if (e.target.id === 'saveJobSettingsBtn' || e.target.closest('#saveJobSettingsBtn')) {
        saveJobSettings();
    }

    if (e.target.id === 'cancelJobSettingsBtn' || e.target.closest('#cancelJobSettingsBtn')) {
        closeJobSettingsModal();
    }

    if (e.target.id === 'applyToAllRowsBtn' || e.target.closest('#applyToAllRowsBtn')) {
        applySettingsToAllRows();
    }

    // Handle modal background click to close
    if (e.target.id === 'jobSettingsModal') {
        closeJobSettingsModal();
    }
});

        // Handle keyboard events for Job Settings Modal and Scroll
        document.addEventListener('keydown', function(e) {
            // Close modal on ESC key
            if (e.key === 'Escape') {
                const modal = document.getElementById('jobSettingsModal');
                if (modal && modal.style.display === 'block') {
                    closeJobSettingsModal();
                }
            }

            // Horizontal scroll keyboard shortcuts
            if (scrollContainer && !e.ctrlKey && !e.altKey && !e.metaKey) {
                switch(e.key) {
                    case 'ArrowLeft':
                        e.preventDefault();
                        scrollContainer.scrollBy({ left: -100, behavior: 'smooth' });
                        break;
                    case 'ArrowRight':
                        e.preventDefault();
                        scrollContainer.scrollBy({ left: 100, behavior: 'smooth' });
                        break;
                    case 'Home':
                        if (e.ctrlKey) {
                            e.preventDefault();
                            scrollContainer.scrollTo({ left: 0, behavior: 'smooth' });
                        }
                        break;
                    case 'End':
                        if (e.ctrlKey) {
                            e.preventDefault();
                            scrollContainer.scrollTo({ left: scrollContainer.scrollWidth, behavior: 'smooth' });
                        }
                        break;
                }
            }
        });

        // Horizontal Scroll Functionality
        let isDragging = false;
        let startX = 0;
        let scrollLeft = 0;
        let scrollContainer = null;

        function initializeHorizontalScroll() {
            scrollContainer = document.getElementById('tableScrollContainer');
            if (!scrollContainer) return;

            // Scroll button event listeners
            document.getElementById('scrollLeftBtn').addEventListener('click', () => {
                scrollContainer.scrollBy({ left: -200, behavior: 'smooth' });
            });

            document.getElementById('scrollRightBtn').addEventListener('click', () => {
                scrollContainer.scrollBy({ left: 200, behavior: 'smooth' });
            });

            document.getElementById('scrollToStartBtn').addEventListener('click', () => {
                scrollContainer.scrollTo({ left: 0, behavior: 'smooth' });
            });

            document.getElementById('scrollToEndBtn').addEventListener('click', () => {
                scrollContainer.scrollTo({ left: scrollContainer.scrollWidth, behavior: 'smooth' });
            });

            // Mouse drag functionality
            scrollContainer.addEventListener('mousedown', (e) => {
                // Don't start dragging if clicking on form controls or interactive elements
                if (e.target.tagName === 'INPUT' ||
                    e.target.tagName === 'SELECT' ||
                    e.target.tagName === 'TEXTAREA' ||
                    e.target.tagName === 'BUTTON' ||
                    e.target.tagName === 'A' ||
                    e.target.closest('input, select, textarea, button, a, .dropdown, .select2-container') ||
                    e.target.classList.contains('select2-container') ||
                    e.target.closest('.select2-container')) {
                    return;
                }

                isDragging = true;
                scrollContainer.classList.add('dragging');
                startX = e.pageX - scrollContainer.offsetLeft;
                scrollLeft = scrollContainer.scrollLeft;
                e.preventDefault();
            });

            document.addEventListener('mousemove', (e) => {
                if (!isDragging) return;
                e.preventDefault();
                const x = e.pageX - scrollContainer.offsetLeft;
                const walk = (x - startX) * 2; // Scroll speed multiplier
                scrollContainer.scrollLeft = scrollLeft - walk;
            });

            document.addEventListener('mouseup', () => {
                isDragging = false;
                scrollContainer.classList.remove('dragging');
            });

            // Touch/swipe functionality
            let touchStartX = 0;
            let touchScrollLeft = 0;

            scrollContainer.addEventListener('touchstart', (e) => {
                // Don't start touch scrolling if touching form controls or interactive elements
                if (e.target.tagName === 'INPUT' ||
                    e.target.tagName === 'SELECT' ||
                    e.target.tagName === 'TEXTAREA' ||
                    e.target.tagName === 'BUTTON' ||
                    e.target.tagName === 'A' ||
                    e.target.closest('input, select, textarea, button, a, .dropdown, .select2-container') ||
                    e.target.classList.contains('select2-container') ||
                    e.target.closest('.select2-container')) {
                    return;
                }

                touchStartX = e.touches[0].pageX;
                touchScrollLeft = scrollContainer.scrollLeft;
            });

            scrollContainer.addEventListener('touchmove', (e) => {
                if (!touchStartX) return;
                const touchX = e.touches[0].pageX;
                const diff = touchStartX - touchX;
                scrollContainer.scrollLeft = touchScrollLeft + diff;
            });

            scrollContainer.addEventListener('touchend', () => {
                touchStartX = 0;
            });

            // Scroll progress indicator
            scrollContainer.addEventListener('scroll', updateScrollProgress);

            // Initial progress update
            updateScrollProgress();
        }

        function updateScrollProgress() {
            if (!scrollContainer) return;

            const scrollLeft = scrollContainer.scrollLeft;
            const scrollWidth = scrollContainer.scrollWidth;
            const clientWidth = scrollContainer.clientWidth;
            const maxScroll = scrollWidth - clientWidth;

            if (maxScroll <= 0) {
                document.getElementById('scrollPosition').textContent = '0%';
                document.getElementById('scrollProgressBar').style.width = '0%';
                document.getElementById('scrollInfo').textContent = 'No scroll needed';

                // Disable scroll buttons
                document.getElementById('scrollLeftBtn').disabled = true;
                document.getElementById('scrollRightBtn').disabled = true;
                document.getElementById('scrollToStartBtn').disabled = true;
                document.getElementById('scrollToEndBtn').disabled = true;
                return;
            }

            const scrollPercentage = Math.round((scrollLeft / maxScroll) * 100);
            document.getElementById('scrollPosition').textContent = scrollPercentage + '%';
            document.getElementById('scrollProgressBar').style.width = scrollPercentage + '%';
            document.getElementById('scrollInfo').textContent = `${scrollLeft}/${maxScroll}px`;

            // Enable/disable scroll buttons based on position
            document.getElementById('scrollLeftBtn').disabled = scrollLeft <= 0;
            document.getElementById('scrollRightBtn').disabled = scrollLeft >= maxScroll;
            document.getElementById('scrollToStartBtn').disabled = scrollLeft <= 0;
            document.getElementById('scrollToEndBtn').disabled = scrollLeft >= maxScroll;
        }

        // Initialize horizontal scroll when DOM is ready
        document.addEventListener('DOMContentLoaded', function() {
            initializeHorizontalScroll();
        });
</script>
@endsection
