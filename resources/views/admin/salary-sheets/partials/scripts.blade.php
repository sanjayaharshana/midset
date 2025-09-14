<script>
const promoters = @json($promoters);
const coordinators = @json($coordinators);
const jobs = @json($jobs);
let rowCounter = 1;
let currentAttendanceDates = [];

// Essential functions for salary sheet management
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
    
    row.innerHTML = `
        <td style="text-align: center; font-weight: bold;">${rowCounter}</td>
        <td>
            <input type="text" class="table-input" name="rows[${rowCounter}][location]" placeholder="Location">
        </td>
        <td>
            <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 1rem;">
                <select class="table-input-small" name="rows[${rowCounter}][promoter_id]" onchange="updatePromoterDetails(${rowCounter}, this)">
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
                <input type="text" class="table-input-small table-input-readonly promoter-tooltip" name="rows[${rowCounter}][promoter_name]" readonly data-tooltip="">
                <input type="text" class="table-input-small table-input-readonly" name="rows[${rowCounter}][position]" readonly>
            </div>
        </td>
        <td id="attendanceCell-${rowCounter}" style="display: ${currentAttendanceDates.length > 0 ? 'table-cell' : 'none'}; width: ${currentAttendanceDates.length > 0 ? (currentAttendanceDates.length * 80 + 160) + 'px' : 'auto'};">
            <div style="display: grid; grid-template-columns: repeat(${currentAttendanceDates.length || 6}, 1fr) 1fr 1.5fr; gap: 0.75rem; width: ${currentAttendanceDates.length > 0 ? (currentAttendanceDates.length * 80 + 160) + 'px' : 'auto'};">
                ${currentAttendanceDates.length > 0 ? 
                    currentAttendanceDates.map(date => 
                        `<input type="number" class="table-input-small" name="rows[${rowCounter}][attendance][${date}]" min="0" max="1" step="1" onchange="calculateAttendanceTotal(${rowCounter})" placeholder="0/1">`
                    ).join('') :
                    ''
                }
                <input type="number" class="table-input-small calculated-cell" name="rows[${rowCounter}][attendance_total]" readonly>
                <input type="number" step="0.01" class="table-input-small calculated-cell" name="rows[${rowCounter}][attendance_amount]" readonly title="Auto-calculated: Position Salary × Present Days">
            </div>
        </td>
        <td>
            <div style="display: grid; grid-template-columns: repeat(6, 1fr); gap: 0.75rem;">
                <input type="number" step="0.01" class="table-input-small" name="rows[${rowCounter}][amount]" placeholder="Amount" onchange="calculateGrandTotal()">
                <input type="number" step="0.01" class="table-input-small" name="rows[${rowCounter}][food_allowance]" placeholder="Food" onchange="calculateGrandTotal()">
                <input type="number" step="0.01" class="table-input-small" name="rows[${rowCounter}][expenses]" placeholder="Expenses" onchange="calculateGrandTotal()">
                <input type="number" step="0.01" class="table-input-small" name="rows[${rowCounter}][accommodation_allowance]" placeholder="Accommodation" onchange="calculateGrandTotal()">
                <input type="number" step="0.01" class="table-input-small" name="rows[${rowCounter}][hold_for_8_weeks]" placeholder="Hold" onchange="calculateGrandTotal()">
                <input type="number" step="0.01" class="table-input-small calculated-cell" name="rows[${rowCounter}][net_amount]" readonly title="Auto-calculated: Total - Expenses - Hold">
            </div>
        </td>
        <td>
            <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 1rem;">
                <select class="table-input-small" name="rows[${rowCounter}][coordinator_id]" onchange="updateCoordinatorDisplay(${rowCounter}, this)">
                    <option value="">Select</option>
                    ${coordinators.map(coordinator => 
                        `<option value="${coordinator.id}" data-name="${coordinator.coordinator_name}">${coordinator.coordinator_id}</option>`
                    ).join('')}
                </select>
                <input type="text" class="table-input-small table-input-readonly" name="rows[${rowCounter}][coordinator_name]" readonly>
                <input type="number" step="0.01" class="table-input-small" name="rows[${rowCounter}][coordination_fee]" placeholder="Fee" onchange="calculateGrandTotal()">
            </div>
        </td>
        <td>
            <button type="button" class="btn-danger" onclick="removeRow(${rowCounter})">×</button>
        </td>
    `;
    
    tbody.appendChild(row);
    rowCounter++;
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

function updateCoordinatorDisplay(rowNum, selectElement) {
    const selectedOption = selectElement.options[selectElement.selectedIndex];
    const row = selectElement.closest('tr');
    
    if (selectedOption && selectedOption.dataset.name) {
        row.querySelector('input[name="rows[' + rowNum + '][coordinator_name]"]').value = selectedOption.dataset.name;
    } else {
        row.querySelector('input[name="rows[' + rowNum + '][coordinator_name]"]').value = '';
    }
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

function calculateAttendanceTotal(rowNum) {
    const row = document.querySelector(`tr:has(input[name="rows[${rowNum}][amount]"])`);
    if (!row) return;
    
    let total = 0;
    if (currentAttendanceDates && currentAttendanceDates.length > 0) {
        currentAttendanceDates.forEach(date => {
            const input = row.querySelector(`input[name="rows[${rowNum}][attendance][${date}]"]`);
            if (input) {
                total += parseFloat(input.value) || 0;
            }
        });
    }
    
    const totalInput = row.querySelector(`input[name="rows[${rowNum}][attendance_total]"]`);
    if (totalInput) {
        totalInput.value = total;
    }
    
    // Calculate attendance amount
    calculateAttendanceAmount(rowNum, total);
}

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
        
        // Clear amount field
        const amountInput = row.querySelector(`input[name="rows[${rowNum}][amount]"]`);
        if (amountInput) {
            amountInput.value = '0.00';
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
    
    // Update the amount field
    const amountInput = row.querySelector(`input[name="rows[${rowNum}][amount]"]`);
    if (amountInput) {
        amountInput.value = attendanceAmount.toFixed(2);
    }
    
    // Recalculate grand total
    calculateGrandTotal();
}

function getPositionSalary(positionId) {
    // This would typically fetch from salary rules
    // For now, return a default value
    return 1000; // Default salary amount
}

function calculateGrandTotal() {
    const rows = document.querySelectorAll('#promoterRows tr');
    let totalAmount = 0;
    let totalNetAmount = 0;
    
    rows.forEach(row => {
        const amount = parseFloat(row.querySelector('input[name*="[amount]"]')?.value || 0);
        const foodAllowance = parseFloat(row.querySelector('input[name*="[food_allowance]"]')?.value || 0);
        const accommodationAllowance = parseFloat(row.querySelector('input[name*="[accommodation_allowance]"]')?.value || 0);
        const coordinationFee = parseFloat(row.querySelector('input[name*="[coordination_fee]"]')?.value || 0);
        const expenses = parseFloat(row.querySelector('input[name*="[expenses]"]')?.value || 0);
        const holdFor8Weeks = parseFloat(row.querySelector('input[name*="[hold_for_8_weeks]"]')?.value || 0);
        
        const rowTotal = amount + foodAllowance + accommodationAllowance + coordinationFee;
        const rowNet = rowTotal - expenses - holdFor8Weeks;
        
        totalAmount += rowTotal;
        totalNetAmount += rowNet;
        
        // Update net amount field
        const netAmountInput = row.querySelector('input[name*="[net_amount]"]');
        if (netAmountInput) {
            netAmountInput.value = rowNet.toFixed(2);
        }
    });
    
    // Update grand total display
    document.getElementById('grandTotalAmount').textContent = `Rs. ${totalAmount.toFixed(2)}`;
    document.getElementById('grandNetAmount').textContent = `Rs. ${totalNetAmount.toFixed(2)}`;
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
            
            // Load existing salary sheets for this job
            loadExistingSalarySheets(selectedOption.value);
            
            // Show table and hide message
            if (noJobMessage) noJobMessage.style.display = 'none';
            if (salaryTableContainer) salaryTableContainer.style.display = 'block';
            if (attendanceLegend) attendanceLegend.style.display = 'block';
            
            // Enable buttons
            if (addPromoterBtn) addPromoterBtn.disabled = false;
            if (salaryRuleBtn) salaryRuleBtn.disabled = false;
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
        if (noJobMessage) noJobMessage.style.display = 'block';
        if (salaryTableContainer) salaryTableContainer.style.display = 'none';
        if (attendanceLegend) attendanceLegend.style.display = 'none';
        
        // Disable buttons
        if (addPromoterBtn) addPromoterBtn.disabled = true;
        if (salaryRuleBtn) salaryRuleBtn.disabled = true;
    }
}

function generateDateRange(startDate, endDate) {
    const dates = [];
    const start = new Date(startDate);
    const end = new Date(endDate);
    
    for (let date = new Date(start); date <= end; date.setDate(date.getDate() + 1)) {
        dates.push(date.toISOString().split('T')[0]);
    }
    
    return dates;
}

function updateAttendanceHeaders(dates) {
    const attendanceColumn = document.getElementById('attendanceColumn');
    const attendanceHeaders = document.getElementById('attendanceHeaders');
    
    if (dates.length > 0) {
        attendanceColumn.style.display = 'table-cell';
        
        const headerHTML = dates.map(date => {
            const dateObj = new Date(date);
            const dayName = dateObj.toLocaleDateString('en-US', { weekday: 'short' });
            const dayNumber = dateObj.getDate();
            return `<div style="text-align: center; font-size: 0.7rem;">${dayName}<br>${dayNumber}</div>`;
        }).join('');
        
        attendanceHeaders.innerHTML = headerHTML + 
            '<div style="text-align: center; font-size: 0.7rem;">Total</div>' +
            '<div style="text-align: center; font-size: 0.7rem;">Amount</div>';
    } else {
        attendanceColumn.style.display = 'none';
    }
}

function updateExistingRows(dates) {
    const rows = document.querySelectorAll('#promoterRows tr');
    rows.forEach(row => {
        const attendanceCell = row.querySelector('td[id^="attendanceCell-"]');
        if (attendanceCell) {
            if (dates.length > 0) {
                attendanceCell.style.display = 'table-cell';
                attendanceCell.style.width = (dates.length * 80 + 160) + 'px';
                
                const gridContainer = attendanceCell.querySelector('div');
                if (gridContainer) {
                    gridContainer.style.gridTemplateColumns = `repeat(${dates.length}, 1fr) 1fr 1.5fr`;
                    gridContainer.style.width = (dates.length * 80 + 160) + 'px';
                    
                    // Update attendance inputs
                    const existingInputs = gridContainer.querySelectorAll('input[name*="[attendance]"]');
                    existingInputs.forEach(input => input.remove());
                    
                    dates.forEach(date => {
                        const input = document.createElement('input');
                        input.type = 'number';
                        input.className = 'table-input-small';
                        input.name = input.name.replace(/\[attendance\]\[\d+\]/, `[attendance][${date}]`);
                        input.min = '0';
                        input.max = '1';
                        input.step = '1';
                        input.placeholder = '0/1';
                        input.onchange = () => calculateAttendanceTotal(getRowNumberFromElement(input));
                        gridContainer.insertBefore(input, gridContainer.querySelector('input[name*="[attendance_total]"]'));
                    });
                }
            } else {
                attendanceCell.style.display = 'none';
            }
        }
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

function clearAllRows() {
    const tbody = document.getElementById('promoterRows');
    tbody.innerHTML = '';
    rowCounter = 1;
}

// Initialize when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    // Add first row automatically
    addPromoterRow();
});
</script>
