@extends('layouts.admin')

@section('title', 'Promoters')
@section('page-title', 'Promoter Details')

@section('breadcrumbs')
    <span class="breadcrumb-separator">›</span>
    <span class="breadcrumb-item active">Promoters</span>
@endsection

@section('content')

    <!-- Example in your Blade layout file -->
<div class="card">
    <div class="card-header">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <h3>All Promoters</h3>
            <div style="display: flex; gap: 0.5rem;">
                @can('create promoters')
                    <button type="button" class="btn btn-info" onclick="openCsvImportModal()">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 8px;">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                            <polyline points="14,2 14,8 20,8"></polyline>
                            <line x1="16" y1="13" x2="8" y2="13"></line>
                            <line x1="16" y1="17" x2="8" y2="17"></line>
                            <polyline points="10,9 9,9 8,9"></polyline>
                        </svg>
                        Import CSV
                    </button>
                    <a href="{{ route('admin.promoters.create') }}" class="btn btn-success">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 8px;">
                            <line x1="12" y1="5" x2="12" y2="19"></line>
                            <line x1="5" y1="12" x2="19" y2="12"></line>
                        </svg>
                        Add New Promoter
                    </a>
                @endcan
            </div>
        </div>
    </div>
    <div class="card-body">
        @if($promoters->count() > 0)
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Promoter ID</th>
                            <th>Name</th>
                            <th>Position</th>
                            <th>ID Card No.</th>
                            <th>Phone</th>
                            <th>Bank Details</th>
                            <th>Status</th>
                            <th>Created</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($promoters as $promoter)
                        <tr>
                            <td>
                                <span style="background: #1f2937; color: white; padding: 0.25rem 0.5rem; border-radius: 0.25rem; font-weight: bold; font-size: 0.8rem; font-family: monospace;">
                                    {{ $promoter->promoter_id }}
                                </span>
                            </td>
                            <td>
                                <div style="font-weight: 600;">{{ $promoter->promoter_name }}</div>
                            </td>
                            <td>
                                @if($promoter->position)
                                    <span style="background: #e0f2fe; color: #0277bd; padding: 0.25rem 0.5rem; border-radius: 0.25rem; font-size: 0.8rem; font-weight: 500;">
                                        {{ $promoter->position->position_name }}
                                    </span>
                                @else
                                    <span style="color: #9ca3af; font-style: italic;">No position</span>
                                @endif
                            </td>
                            <td>
                                <span style="font-family: monospace; font-size: 0.9rem;">{{ $promoter->identity_card_no }}</span>
                            </td>
                            <td>{{ $promoter->phone_no }}</td>
                            <td>
                                <div style="font-size: 0.85rem;">
                                    <div style="font-weight: 500;">{{ $promoter->bank_name }}</div>
                                    <div style="color: #6b7280;">{{ $promoter->bank_branch_name }}</div>
                                    <div style="color: #6b7280; font-family: monospace;">****{{ substr($promoter->bank_account_number, -4) }}</div>
                                </div>
                            </td>
                            <td>
                                <span class="status-badge status-{{ $promoter->status }}">
                                    {{ ucfirst($promoter->status) }}
                                </span>
                            </td>
                            <td>{{ $promoter->created_at->format('M d, Y') }}</td>
                            <td>
                                <div style="display: flex; gap: 8px;">
                                    @can('view promoters')
                                        <a href="{{ route('admin.promoters.show', $promoter) }}" class="btn btn-sm btn-info">
                                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                                <circle cx="12" cy="12" r="3"></circle>
                                            </svg>
                                        </a>
                                    @endcan
                                    @can('edit promoters')
                                        <a href="{{ route('admin.promoters.edit', $promoter) }}" class="btn btn-sm btn-warning">
                                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                                <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                            </svg>
                                        </a>
                                    @endcan
                                    @can('delete promoters')
                                        <form action="{{ route('admin.promoters.destroy', $promoter) }}" method="POST" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete promoter {{ $promoter->promoter_id }}?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">
                                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                    <polyline points="3,6 5,6 21,6"></polyline>
                                                    <path d="M19,6v14a2,2 0 0,1 -2,2H7a2,2 0 0,1 -2,-2V6m3,0V4a2,2 0 0,1 2,-2h4a2,2 0 0,1 2,2v2"></path>
                                                </svg>
                                            </button>
                                        </form>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="pagination-container">
                {{ $promoters->links() }}
            </div>
        @else
            <div style="text-align: center; padding: 2rem;">
                <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="#d1d5db" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" style="margin-bottom: 1rem;">
                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                    <circle cx="12" cy="7" r="4"></circle>
                </svg>
                <h3 style="color: #6b7280; margin-bottom: 0.5rem;">No promoters found</h3>
                <p style="color: #9ca3af;">Get started by adding your first promoter.</p>
                @can('create promoters')
                    <a href="{{ route('admin.promoters.create') }}" class="btn btn-success" style="margin-top: 1rem;">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 8px;">
                            <line x1="12" y1="5" x2="12" y2="19"></line>
                            <line x1="5" y1="12" x2="19" y2="12"></line>
                        </svg>
                        Add First Promoter
                    </a>
                @endcan
            </div>
        @endif
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

.btn-warning {
    background-color: #f59e0b;
    color: white;
    border: none;
}

.btn-danger {
    background-color: #ef4444;
    color: white;
    border: none;
}

.btn-info:hover,
.btn-warning:hover,
.btn-danger:hover {
    opacity: 0.9;
    transform: translateY(-1px);
}

/* Modal Styles */
.modal {
    position: fixed;
    z-index: 1050;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.6);
    backdrop-filter: blur(2px);
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 1rem;
}

.modal-content {
    background-color: #ffffff;
    border-radius: 8px;
    width: 100%;
    max-width: 600px;
    max-height: 90vh;
    box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
    animation: modalSlideIn 0.2s ease-out;
    overflow: hidden;
    position: relative;
}

@keyframes modalSlideIn {
    from {
        opacity: 0;
        transform: scale(0.95) translateY(-20px);
    }
    to {
        opacity: 1;
        transform: scale(1) translateY(0);
    }
}

.modal-header {
    padding: 1.5rem 2rem;
    border-bottom: 1px solid #e5e7eb;
    display: flex;
    justify-content: space-between;
    align-items: center;
    background-color: #ffffff;
}

.modal-header h3 {
    margin: 0;
    color: #1f2937;
    font-size: 1.375rem;
    font-weight: 600;
    letter-spacing: -0.025em;
}

.close {
    color: #6b7280;
    font-size: 1.5rem;
    font-weight: 400;
    cursor: pointer;
    line-height: 1;
    padding: 0.25rem;
    border-radius: 4px;
    transition: all 0.15s ease;
    width: 2rem;
    height: 2rem;
    display: flex;
    align-items: center;
    justify-content: center;
}

.close:hover {
    color: #374151;
    background-color: #f3f4f6;
}

.modal-body {
    padding: 2rem;
    max-height: calc(90vh - 140px);
    overflow-y: auto;
}

/* Alert Styles */
.alert {
    padding: 1rem 1.25rem;
    margin-bottom: 1.5rem;
    border: 1px solid transparent;
    border-radius: 6px;
    font-size: 0.875rem;
    line-height: 1.5;
}

.alert h6 {
    margin: 0 0 0.75rem 0;
    font-weight: 600;
    font-size: 0.875rem;
}

.alert ul {
    margin: 0.5rem 0 0 0;
    padding-left: 1.25rem;
}

.alert li {
    margin-bottom: 0.25rem;
}

.alert-info {
    color: #1e40af;
    background-color: #eff6ff;
    border-color: #dbeafe;
}

.alert-warning {
    color: #92400e;
    background-color: #fffbeb;
    border-color: #fed7aa;
}

.alert-success {
    color: #166534;
    background-color: #f0fdf4;
    border-color: #bbf7d0;
}

.alert-danger {
    color: #991b1b;
    background-color: #fef2f2;
    border-color: #fecaca;
}

/* Form Styles */
.form-label {
    display: block;
    margin-bottom: 0.5rem;
    font-weight: 500;
    color: #374151;
    font-size: 0.875rem;
}

.form-control {
    display: block;
    width: 100%;
    padding: 0.75rem 1rem;
    font-size: 0.875rem;
    font-weight: 400;
    line-height: 1.5;
    color: #374151;
    background-color: #ffffff;
    background-clip: padding-box;
    border: 1px solid #d1d5db;
    border-radius: 6px;
    transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
}

.form-control:focus {
    color: #374151;
    background-color: #ffffff;
    border-color: #3b82f6;
    outline: 0;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

.form-text {
    margin-top: 0.25rem;
    font-size: 0.75rem;
    color: #6b7280;
}

/* Button Styles */
.btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 0.75rem 1.5rem;
    font-size: 0.875rem;
    font-weight: 500;
    line-height: 1.25;
    border-radius: 6px;
    border: 1px solid transparent;
    cursor: pointer;
    transition: all 0.15s ease-in-out;
    text-decoration: none;
    min-height: 2.5rem;
}

.btn:disabled {
    opacity: 0.6;
    cursor: not-allowed;
}

.btn-primary {
    background-color: #3b82f6;
    border-color: #3b82f6;
    color: #ffffff;
}

.btn-primary:hover:not(:disabled) {
    background-color: #2563eb;
    border-color: #2563eb;
    transform: translateY(-1px);
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
}

.btn-secondary {
    background-color: #6b7280;
    border-color: #6b7280;
    color: #ffffff;
}

.btn-secondary:hover:not(:disabled) {
    background-color: #4b5563;
    border-color: #4b5563;
    transform: translateY(-1px);
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
}

.btn-outline-info {
    background-color: transparent;
    border-color: #3b82f6;
    color: #3b82f6;
}

.btn-outline-info:hover {
    background-color: #3b82f6;
    border-color: #3b82f6;
    color: #ffffff;
}

.btn-sm {
    padding: 0.5rem 1rem;
    font-size: 0.75rem;
    min-height: 2rem;
}

/* Modal Footer */
.modal-footer {
    padding: 1.5rem 2rem;
    border-top: 1px solid #e5e7eb;
    background-color: #f9fafb;
    display: flex;
    justify-content: flex-end;
    gap: 0.75rem;
}

/* Responsive Design */
@media (max-width: 768px) {
    .modal {
        padding: 0.5rem;
    }
    
    .modal-content {
        max-width: 100%;
        margin: 0;
    }
    
    .modal-header {
        padding: 1rem 1.5rem;
    }
    
    .modal-header h3 {
        font-size: 1.125rem;
    }
    
    .modal-body {
        padding: 1.5rem;
    }
    
    .modal-footer {
        padding: 1rem 1.5rem;
        flex-direction: column;
        gap: 0.5rem;
    }
    
    .modal-footer .btn {
        width: 100%;
        justify-content: center;
    }
    
    .alert {
        padding: 0.875rem 1rem;
        margin-bottom: 1rem;
    }
    
    .alert ul {
        padding-left: 1rem;
    }
}
</style>

<!-- CSV Import Modal -->
<div id="csvImportModal" class="modal" style="display: none;">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Import Promoters from CSV</h3>
            <span class="close" onclick="closeCsvImportModal()">&times;</span>
        </div>
        <form id="csvImportForm" action="{{ route('admin.promoters.import-csv') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="modal-body">
                <div style="margin-bottom: 1.5rem;">
                    <label for="csvFile" class="form-label">Select CSV File</label>
                    <input type="file" class="form-control" id="csvFile" name="csv_file" accept=".csv" required>
                    <div class="form-text">Please select a CSV file containing promoter data.</div>
                </div>
                
                <div class="alert alert-info">
                    <h6>CSV Format Requirements</h6>
                    <p style="margin-bottom: 0.75rem;">Your CSV file should contain the following columns (in any order):</p>
                    <ul>
                        <li><strong>promoter_name</strong> - Full name of the promoter</li>
                        <li><strong>position_name</strong> - Position name (must match existing positions)</li>
                        <li><strong>identity_card_no</strong> - ID card number</li>
                        <li><strong>phone_no</strong> - Phone number</li>
                        <li><strong>bank_name</strong> - Bank name</li>
                        <li><strong>bank_branch_name</strong> - Bank branch name</li>
                        <li><strong>bank_account_number</strong> - Bank account number</li>
                        <li><strong>status</strong> - Status (active, inactive, suspended)</li>
                    </ul>
                    <div style="margin-top: 1rem;">
                        <a href="{{ asset('sample-promoters.csv') }}" class="btn btn-sm btn-outline-info" download>
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 6px;">
                                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                                <polyline points="7,10 12,15 17,10"></polyline>
                                <line x1="12" y1="15" x2="12" y2="3"></line>
                            </svg>
                            Download Sample CSV
                        </a>
                    </div>
                </div>
                
                <div class="alert alert-warning">
                    <h6>Important Notes</h6>
                    <ul>
                        <li>Promoter IDs will be automatically generated</li>
                        <li>Position names must exactly match existing positions</li>
                        <li>Duplicate phone numbers or ID card numbers will be skipped</li>
                        <li>Status must be one of: active, inactive, suspended</li>
                    </ul>
                </div>
                
                <div id="importStatus" class="alert" style="display: none;"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeCsvImportModal()">Cancel</button>
                <button type="submit" class="btn btn-primary" id="importBtn">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 8px;">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                        <polyline points="14,2 14,8 20,8"></polyline>
                        <line x1="16" y1="13" x2="8" y2="13"></line>
                        <line x1="16" y1="17" x2="8" y2="17"></line>
                        <polyline points="10,9 9,9 8,9"></polyline>
                    </svg>
                    Import CSV
                </button>
            </div>
        </form>
    </div>
</div>

<script>
// Global modal functions
function openCsvImportModal() {
    const modal = document.getElementById('csvImportModal');
    if (modal) {
        modal.style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }
}

function closeCsvImportModal() {
    const modal = document.getElementById('csvImportModal');
    if (modal) {
        modal.style.display = 'none';
        document.body.style.overflow = 'auto';
        // Reset form
        const form = document.getElementById('csvImportForm');
        const statusDiv = document.getElementById('importStatus');
        if (form) form.reset();
        if (statusDiv) statusDiv.style.display = 'none';
    }
}

document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('csvImportForm');
    const importBtn = document.getElementById('importBtn');
    const statusDiv = document.getElementById('importStatus');
    
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(form);
        const fileInput = document.getElementById('csvFile');
        
        if (!fileInput.files[0]) {
            showStatus('Please select a CSV file.', 'danger');
            return;
        }
        
        // Show loading state
        importBtn.disabled = true;
        importBtn.innerHTML = '<span style="display: inline-block; width: 16px; height: 16px; border: 2px solid #ffffff; border-radius: 50%; border-top-color: transparent; animation: spin 1s linear infinite; margin-right: 8px;"></span>Importing...';
        
        fetch(form.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showStatus(`Successfully imported ${data.imported_count} promoters. ${data.skipped_count} rows were skipped.`, 'success');
                setTimeout(() => {
                    location.reload();
                }, 2000);
            } else {
                showStatus(data.message || 'Import failed. Please check your CSV file.', 'danger');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showStatus('An error occurred during import. Please try again.', 'danger');
        })
        .finally(() => {
            importBtn.disabled = false;
            importBtn.innerHTML = '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 8px;"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14,2 14,8 20,8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10,9 9,9 8,9"></polyline></svg>Import CSV';
        });
    });
    
    function showStatus(message, type) {
        statusDiv.style.display = 'block';
        statusDiv.textContent = message;
        statusDiv.style.backgroundColor = type === 'success' ? '#d4edda' : '#f8d7da';
        statusDiv.style.color = type === 'success' ? '#155724' : '#721c24';
        statusDiv.style.border = `1px solid ${type === 'success' ? '#c3e6cb' : '#f5c6cb'}`;
        statusDiv.style.padding = '0.75rem';
        statusDiv.style.borderRadius = '4px';
    }
    
    // Close modal when clicking outside
    document.getElementById('csvImportModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeCsvImportModal();
        }
    });
});

// Add CSS for spinner animation
const style = document.createElement('style');
style.textContent = `
    @keyframes spin {
        to { transform: rotate(360deg); }
    }
`;
document.head.appendChild(style);
</script>
@endsection
