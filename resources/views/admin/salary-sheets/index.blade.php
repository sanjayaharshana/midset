@extends('layouts.admin')

@section('title', __('salary_sheets.salary_sheets'))
@section('page-title', __('salary_sheets.salary_sheets'))

@section('breadcrumbs')
    <span class="breadcrumb-separator">›</span>
    <span class="breadcrumb-item active">{{ __('salary_sheets.salary_sheets') }}</span>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <h3>{{ __('salary_sheets.salary_sheets') }}</h3>
            @can('create salary sheets')
                <a href="{{ route('admin.salary-sheets.create') }}" class="btn btn-success">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 8px;">
                        <line x1="12" y1="5" x2="12" y2="19"></line>
                        <line x1="5" y1="12" x2="19" y2="12"></line>
                    </svg>
                    {{ __('salary_sheets.create_salary_sheet') }}
                </a>
            @endcan
        </div>
    </div>
    <div class="card-body">
        @if($salarySheets->count() > 0)
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>{{ __('salary_sheets.sheet_number') }}</th>
                            <th>{{ __('salary_sheets.period') }}</th>
                            <th>{{ __('salary_sheets.job_title') }}</th>
                            <th>{{ __('common.count') }}</th>
                            <th>{{ __('salary_sheets.total_promoters') }}</th>
                            <th>{{ __('salary_sheets.status') }}</th>
                            <th>{{ __('salary_sheets.created') }}</th>
                            <th>{{ __('common.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($salarySheets as $sheet)
                        <tr>
                            <td>
                                <span style="background: #1f2937; color: white; padding: 0.25rem 0.5rem; border-radius: 0.25rem; font-weight: bold; font-size: 0.8rem; font-family: monospace;">
                                    {{ $sheet->sheet_no }}
                                </span>
                            </td>
                            <td>
                                <div style="font-weight: 600;">{{ $sheet->created_at->format('M Y') }}</div>
                            </td>
                            <td>
                                <div>
                                    @if($sheet->job)
                                        <div style="font-weight: 500;">{{ $sheet->job->job_number ?? 'N/A' }}</div>
                                        <div style="color: #6b7280; font-size: 0.8rem;">{{ $sheet->job->job_title ?? 'N/A' }}</div>
                                        @if($sheet->job->client)
                                            <div style="color: #6b7280; font-size: 0.75rem;">{{ $sheet->job->client->client_name ?? 'N/A' }}</div>
                                        @endif
                                    @else
                                        <div style="color: #6b7280;">No job assigned</div>
                                    @endif
                                </div>
                            </td>
                            <td>
                                <span style="background: #3b82f6; color: white; padding: 0.25rem 0.5rem; border-radius: 0.25rem; font-weight: bold; font-size: 0.8rem;">
                                    {{ $sheet->items_count }}
                                </span>
                            </td>
                            <td>
                                <span style="background: #10b981; color: white; padding: 0.25rem 0.5rem; border-radius: 0.25rem; font-weight: bold; font-size: 0.8rem;">
                                    {{ $sheet->promoters_count }}
                                </span>
                            </td>
                            <td>
                                @php
                                    $statusDisplay = ucfirst($sheet->status);
                                    $statusClass = $sheet->status;

                                    // For reporter role, show "Pending Approval" for "complete" status
                                    if (auth()->check() && auth()->user()->hasRole('reporter') && $sheet->status === 'complete') {
                                        $statusDisplay = 'Pending Approval';
                                        $statusClass = 'pending-approval';
                                    }
                                @endphp
                                <span class="status-badge status-{{ $statusClass }}">
                                    {{ $statusDisplay }}
                                </span>
                            </td>
                            <td>{{ $sheet->created_at->format('M d, Y') }}</td>
                            <td>
                                <div style="display: flex; gap: 8px;">
                                    @can('view salary sheets')
                                        <a href="{{ route('admin.salary-sheets.show', $sheet) }}" class="btn btn-sm btn-info">
                                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                                <circle cx="12" cy="12" r="3"></circle>
                                            </svg>
                                        </a>
                                    @endcan
                                    @if(auth()->check() && auth()->user()->hasRole('reporter') && $sheet->status === 'complete')
                                        <button type="button" class="btn btn-sm btn-success" onclick="openApprovalModal({{ $sheet->id }}, '{{ $sheet->sheet_no }}')" title="Approve Salary Sheet">
                                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <polyline points="20 6 9 17 4 12"></polyline>
                                            </svg>
                                        </button>
                                    @endif
                                    @can('view salary sheets')
                                        <a href="{{ route('admin.salary-sheets.export', $sheet) }}" class="btn btn-sm btn-success" title="Export to Excel">
                                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                                                <polyline points="7 10 12 15 17 10"></polyline>
                                                <line x1="12" y1="15" x2="12" y2="3"></line>
                                            </svg>
                                        </a>
                                        <a href="{{ route('admin.salary-sheets.print', $sheet) }}" target="_blank" class="btn btn-sm btn-primary" title="Print">
                                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <polyline points="6,9 6,2 18,2 18,9"></polyline>
                                                <path d="M6,18H4a2,2 0 0,1 -2,-2V11a2,2 0 0,1 2,-2H20a2,2 0 0,1 2,2v5a2,2 0 0,1 -2,2H18"></path>
                                                <polyline points="6,14 18,14 18,22 6,22 6,14"></polyline>
                                            </svg>
                                        </a>
                                    @endcan
                                    @can('delete salary sheets')
                                        @if($sheet->job && $sheet->job->status !== 'completed')
                                            <form action="{{ route('admin.salary-sheets.destroy', $sheet) }}" method="POST" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete salary sheet {{ $sheet->sheet_no }}?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger">
                                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                        <polyline points="3,6 5,6 21,6"></polyline>
                                                        <path d="M19,6v14a2,2 0 0,1 -2,2H7a2,2 0 0,1 -2,-2V6m3,0V4a2,2 0 0,1 2,-2h4a2,2 0 0,1 2,2v2"></path>
                                                    </svg>
                                                </button>
                                            </form>
                                        @else
                                            <button type="button" class="btn btn-sm btn-danger" style="opacity: 0.5; cursor: not-allowed;" title="Cannot delete salary sheets for completed jobs">
                                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                    <polyline points="3,6 5,6 21,6"></polyline>
                                                    <path d="M19,6v14a2,2 0 0,1 -2,2H7a2,2 0 0,1 -2,-2V6m3,0V4a2,2 0 0,1 2,-2h4a2,2 0 0,1 2,2v2"></path>
                                                </svg>
                                            </button>
                                        @endif
                                    @endcan
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="pagination-container">
                {{ $salarySheets->links() }}
            </div>
        @else
            <div style="text-align: center; padding: 2rem;">
                <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="#d1d5db" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" style="margin-bottom: 1rem;">
                    <rect x="2" y="3" width="20" height="14" rx="2" ry="2"></rect>
                    <line x1="8" y1="21" x2="16" y2="21"></line>
                    <line x1="12" y1="17" x2="12" y2="21"></line>
                </svg>
                <h3 style="color: #6b7280; margin-bottom: 0.5rem;">No salary sheets found</h3>
                <p style="color: #9ca3af;">Get started by creating your first salary sheet.</p>
                @can('create salary sheets')
                    <a href="{{ route('admin.salary-sheets.create') }}" class="btn btn-success" style="margin-top: 1rem;">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 8px;">
                            <line x1="12" y1="5" x2="12" y2="19"></line>
                            <line x1="5" y1="12" x2="19" y2="12"></line>
                        </svg>
                        Create First Salary Sheet
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

.status-draft {
    background-color: #fef3c7;
    color: #92400e;
}

.status-approved {
    background-color: #d1fae5;
    color: #065f46;
}

.status-complete {
    background-color: #d1fae5;
    color: #065f46;
}

.status-reject {
    background-color: #fee2e2;
    color: #991b1b;
}

.status-approve {
    background-color: #d1fae5;
    color: #065f46;
}

.status-paid {
    background-color: #dbeafe;
    color: #1e40af;
}

.status-pending-approval {
    background-color: #fef3c7;
    color: #92400e;
    font-weight: 600;
}

.table-responsive {
    overflow-x: auto;
}

.table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 1rem;
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
}

.table tbody tr:hover {
    background-color: #f9fafb;
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
.btn-primary:hover,
.btn-warning:hover,
.btn-danger:hover {
    opacity: 0.9;
    transform: translateY(-1px);
}

.btn-success {
    background-color: #10b981;
    color: white;
    border: none;
}

.btn-success:hover {
    opacity: 0.9;
    transform: translateY(-1px);
}
</style>

<!-- Approval Modal -->
<div id="approvalModal" class="modal" style="display: none;">
    <div class="modal-content" style="max-width: 500px; width: 90%;">
        <div class="modal-header">
            <h3>Approve Salary Sheet</h3>
            <span class="close" id="approvalModalClose">&times;</span>
        </div>
        <div class="modal-body">
            <p style="margin-bottom: 1rem;">Are you sure you want to approve this salary sheet?</p>
            <div style="background: #f8fafc; padding: 1rem; border-radius: 0.5rem; margin-bottom: 1rem;">
                <strong>Sheet Number:</strong> <span id="approvalSheetNo"></span>
            </div>
            <p style="color: #6b7280; font-size: 0.9rem; margin-bottom: 0;">This action will change the status from "Complete" to "Approve" and cannot be undone.</p>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" onclick="closeApprovalModal()">Cancel</button>
            <button type="button" class="btn btn-success" onclick="confirmApproval()">Confirm Approval</button>
        </div>
    </div>
</div>

<style>
.modal {
    display: none;
    position: fixed;
    z-index: 1000;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    overflow: auto;
    background-color: rgba(0,0,0,0.5);
}

.modal-content {
    background-color: #fefefe;
    margin: 5% auto;
    padding: 0;
    border-radius: 0.5rem;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
    position: relative;
    max-height: 90vh;
    overflow-y: auto;
}

.modal-header {
    padding: 1.5rem;
    border-bottom: 1px solid #e5e7eb;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.modal-header h3 {
    margin: 0;
    color: #374151;
}

.modal-body {
    padding: 1.5rem;
}

.modal-footer {
    padding: 1rem 1.5rem;
    border-top: 1px solid #e5e7eb;
    display: flex;
    justify-content: flex-end;
    gap: 1rem;
    background-color: #f9fafb;
}

.close {
    color: #9ca3af;
    font-size: 28px;
    font-weight: bold;
    cursor: pointer;
    line-height: 1;
}

.close:hover,
.close:focus {
    color: #374151;
}

.btn-secondary {
    background-color: #6b7280;
    color: white;
    border: none;
    padding: 0.625rem 1.25rem;
    border-radius: 0.375rem;
    cursor: pointer;
    font-size: 0.875rem;
    font-weight: 500;
    transition: all 0.2s;
}

.btn-secondary:hover {
    background-color: #4b5563;
    transform: translateY(-1px);
}

.modal-footer .btn-success {
    background-color: #10b981;
    color: white;
    border: none;
    padding: 0.625rem 1.25rem;
    border-radius: 0.375rem;
    cursor: pointer;
    font-size: 0.875rem;
    font-weight: 500;
    transition: all 0.2s;
}

.modal-footer .btn-success:hover {
    background-color: #059669;
    transform: translateY(-1px);
}
</style>

<script>
let approvalSheetId = null;

function openApprovalModal(sheetId, sheetNo) {
    approvalSheetId = sheetId;
    document.getElementById('approvalSheetNo').textContent = sheetNo;
    document.getElementById('approvalModal').style.display = 'block';
    document.body.style.overflow = 'hidden';
}

function closeApprovalModal() {
    document.getElementById('approvalModal').style.display = 'none';
    document.body.style.overflow = 'auto';
    approvalSheetId = null;
}

function confirmApproval() {
    if (!approvalSheetId) {
        return;
    }

    // Show loading state
    const confirmBtn = event.target;
    const originalText = confirmBtn.textContent;
    confirmBtn.disabled = true;
    confirmBtn.textContent = 'Approving...';

    // Get CSRF token
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') ||
                     document.querySelector('input[name="_token"]')?.value;

    // Send approval request
    const approveUrl = `{{ route('admin.salary-sheets.approve', ':id') }}`.replace(':id', approvalSheetId);
    fetch(approveUrl, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json'
        },
        body: JSON.stringify({
            _token: csrfToken
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Show success message
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: 'success',
                    title: 'Approved!',
                    text: data.message || 'Salary sheet has been approved successfully.',
                    confirmButtonText: 'OK'
                }).then(() => {
                    location.reload();
                });
            } else {
                alert('Salary sheet approved successfully!');
                location.reload();
            }
        } else {
            throw new Error(data.message || 'Failed to approve salary sheet');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: error.message || 'Failed to approve salary sheet. Please try again.',
                confirmButtonText: 'OK'
            });
        } else {
            alert('Error: ' + (error.message || 'Failed to approve salary sheet'));
        }
        confirmBtn.disabled = false;
        confirmBtn.textContent = originalText;
    });
}

// Close modal when clicking outside
document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('approvalModal');
    const closeBtn = document.getElementById('approvalModalClose');

    if (closeBtn) {
        closeBtn.addEventListener('click', closeApprovalModal);
    }

    if (modal) {
        modal.addEventListener('click', function(e) {
            if (e.target === modal) {
                closeApprovalModal();
            }
        });
    }

    // Close on ESC key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && modal && modal.style.display === 'block') {
            closeApprovalModal();
        }
    });
});
</script>
@endsection
