<!-- Salary Rule Modal -->
<div id="salaryRuleModal" class="modal" style="display: none;">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Position Wise Salary Rules</h3>
            <span class="close" onclick="closeSalaryRuleModal()">&times;</span>
        </div>
        <div class="modal-body">
            <div id="modalPreloader" style="display: none; text-align: center; padding: 2rem;">
                <div style="display: flex; align-items: center; justify-content: center; gap: 0.5rem;">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M21 12a9 9 0 11-6.219-8.56"></path>
                    </svg>
                    <span id="preloaderText">Loading...</span>
                </div>
            </div>
            
            <div id="modalContent">
                <!-- Content will be loaded dynamically -->
            </div>
        </div>
    </div>
</div>

<!-- Job Settings Modal -->
<div id="jobSettingsModal" class="modal" style="display: none;">
    <div class="modal-content" style="max-width: 800px;">
        <div class="modal-header">
            <h3>Job Settings</h3>
            <span class="close" onclick="closeJobSettingsModal()">&times;</span>
        </div>
        <div class="modal-body">
            <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 1rem; margin-bottom: 1.5rem;">
                <div>
                    <label class="form-label">Default Coordinator Fee</label>
                    <input type="number" step="0.01" class="form-control" id="defaultCoordinatorFee" placeholder="Enter default coordinator fee">
                </div>
                <div>
                    <label class="form-label">Default Hold For 8 Weeks</label>
                    <input type="number" step="0.01" class="form-control" id="defaultHoldFor8Weeks" placeholder="Enter default hold amount">
                </div>
                <div>
                    <label class="form-label">Default Food Allowance</label>
                    <input type="number" step="0.01" class="form-control" id="defaultFoodAllowance" placeholder="Enter default food allowance">
                </div>
                <div>
                    <label class="form-label">Default Accommodation Allowance</label>
                    <input type="number" step="0.01" class="form-control" id="defaultAccommodationAllowance" placeholder="Enter default accommodation allowance">
                </div>
                <div>
                    <label class="form-label">Default Expenses</label>
                    <input type="number" step="0.01" class="form-control" id="defaultExpenses" placeholder="Enter default expenses">
                </div>
            </div>
            <div style="margin-top: 1.5rem;">
                <label class="form-label">Job Description</label>
                <textarea class="form-control" id="jobDescription" rows="4" placeholder="Enter detailed job description..."></textarea>
                <small class="form-text text-muted">Detailed description of the job requirements and scope</small>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" id="applyToAllRowsBtn" class="btn btn-success" onclick="applySettingsToAllRows()">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 8px;">
                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                    <polyline points="7,10 12,15 17,10"></polyline>
                    <line x1="12" y1="15" x2="12" y2="3"></line>
                </svg>
                Apply to All Rows
            </button>
            <button type="button" id="saveJobSettingsBtn" class="btn btn-primary" onclick="saveJobSettings()">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 8px;">
                    <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path>
                    <polyline points="17,21 17,13 7,13 7,21"></polyline>
                    <polyline points="7,3 7,8 15,8"></polyline>
                </svg>
                Save Settings
            </button>
            <button type="button" id="cancelJobSettingsBtn" class="btn btn-secondary" onclick="closeJobSettingsModal()">Cancel</button>
        </div>
    </div>
</div>
