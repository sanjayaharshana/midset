@extends('layouts.admin')

@section('title', 'Edit Coordinator')
@section('page-title', 'Edit Coordinator')

@section('breadcrumbs')
    <span class="breadcrumb-separator">›</span>
    <a href="{{ route('admin.coordinators.index') }}" class="breadcrumb-item">Coordinators</a>
    <span class="breadcrumb-separator">›</span>
    <span class="breadcrumb-item active">Edit</span>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h3>Edit Coordinator Information</h3>
        <p style="color: #6b7280; margin-top: 0.5rem;">Current Coordinator ID: <strong>{{ $coordinator->coordinator_id }}</strong></p>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.coordinators.update', $coordinator) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 1.5rem;">
                <div class="form-group">
                    <label for="coordinator_name" class="form-label">Coordinator Name *</label>
                    <input type="text" class="form-control @error('coordinator_name') is-invalid @enderror" 
                           id="coordinator_name" name="coordinator_name" value="{{ old('coordinator_name', $coordinator->coordinator_name) }}" required>
                    @error('coordinator_name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="nic_no" class="form-label">NIC No. *</label>
                    <input type="text" class="form-control @error('nic_no') is-invalid @enderror" 
                           id="nic_no" name="nic_no" value="{{ old('nic_no', $coordinator->nic_no) }}" required>
                    @error('nic_no')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 1.5rem;">
                <div class="form-group">
                    <label for="phone_no" class="form-label">Phone No. *</label>
                    <input type="text" class="form-control @error('phone_no') is-invalid @enderror" 
                           id="phone_no" name="phone_no" value="{{ old('phone_no', $coordinator->phone_no) }}" required>
                    @error('phone_no')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="status" class="form-label">Status *</label>
                    <select class="form-control @error('status') is-invalid @enderror" 
                            id="status" name="status" required>
                        <option value="">Select Status</option>
                        <option value="active" {{ old('status', $coordinator->status) == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ old('status', $coordinator->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        <option value="suspended" {{ old('status', $coordinator->status) == 'suspended' ? 'selected' : '' }}>Suspended</option>
                    </select>
                    @error('status')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div style="background: #f8fafc; padding: 1.5rem; border-radius: 0.5rem; margin-bottom: 1.5rem;">
                <h4 style="margin-bottom: 1rem; color: #374151;">Bank Details</h4>
                
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1rem;">
                    <div class="form-group">
                        <label for="bank_name" class="form-label">Bank Name *</label>
                        <input type="text" class="form-control @error('bank_name') is-invalid @enderror" 
                               id="bank_name" name="bank_name" value="{{ old('bank_name', $coordinator->bank_name) }}" required>
                        @error('bank_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="bank_branch_name" class="form-label">Bank Branch Name *</label>
                        <input type="text" class="form-control @error('bank_branch_name') is-invalid @enderror" 
                               id="bank_branch_name" name="bank_branch_name" value="{{ old('bank_branch_name', $coordinator->bank_branch_name) }}" required>
                        @error('bank_branch_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="form-group">
                    <label for="account_number" class="form-label">Account Number *</label>
                    <input type="text" class="form-control @error('account_number') is-invalid @enderror" 
                           id="account_number" name="account_number" value="{{ old('account_number', $coordinator->account_number) }}" required>
                    @error('account_number')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div style="display: flex; gap: 1rem; justify-content: flex-end;">
                <a href="{{ route('admin.coordinators.index') }}" class="btn btn-secondary">Cancel</a>
                <button type="submit" class="btn btn-success">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 8px;">
                        <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path>
                        <polyline points="17,21 17,13 7,13 7,21"></polyline>
                        <polyline points="7,3 7,8 15,8"></polyline>
                    </svg>
                    Update Coordinator
                </button>
            </div>
        </form>
    </div>
</div>

<style>
.form-group {
    margin-bottom: 1rem;
}

.form-label {
    display: block;
    margin-bottom: 0.5rem;
    font-weight: 600;
    color: #374151;
}

.form-control {
    width: 100%;
    padding: 0.75rem;
    border: 1px solid #d1d5db;
    border-radius: 0.375rem;
    font-size: 0.875rem;
    transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
}

.form-control:focus {
    outline: none;
    border-color: #3b82f6;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

.form-control.is-invalid {
    border-color: #ef4444;
}

.invalid-feedback {
    display: block;
    width: 100%;
    margin-top: 0.25rem;
    font-size: 0.875rem;
    color: #ef4444;
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

@media (max-width: 768px) {
    div[style*="grid-template-columns"] {
        grid-template-columns: 1fr !important;
    }
}
</style>
@endsection

