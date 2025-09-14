@extends('layouts.admin')

@section('title', 'Edit Promoter')
@section('page-title', 'Edit Promoter')

@section('breadcrumbs')
    <span class="breadcrumb-separator">›</span>
    <a href="{{ route('admin.promoters.index') }}" class="breadcrumb-item">Promoters</a>
    <span class="breadcrumb-separator">›</span>
    <span class="breadcrumb-item active">Edit</span>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h3>Edit Promoter Information</h3>
        <p style="color: #6b7280; margin-top: 0.5rem;">Current Promoter ID: <strong>{{ $promoter->promoter_id }}</strong></p>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.promoters.update', $promoter) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 1.5rem;">
                <div class="form-group">
                    <label for="promoter_name" class="form-label">Promoter Name *</label>
                    <input type="text" class="form-control @error('promoter_name') is-invalid @enderror" 
                           id="promoter_name" name="promoter_name" value="{{ old('promoter_name', $promoter->promoter_name) }}" required>
                    @error('promoter_name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="position_id" class="form-label">Position</label>
                    <select class="form-control @error('position_id') is-invalid @enderror" 
                            id="position_id" name="position_id">
                        <option value="">Select Position (Optional)</option>
                        @foreach($positions as $position)
                            <option value="{{ $position->id }}" {{ old('position_id', $promoter->position_id) == $position->id ? 'selected' : '' }}>
                                {{ $position->position_name }}
                            </option>
                        @endforeach
                    </select>
                    @error('position_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 1.5rem;">
                <div class="form-group">
                    <label for="identity_card_no" class="form-label">Identity Card No. *</label>
                    <input type="text" class="form-control @error('identity_card_no') is-invalid @enderror" 
                           id="identity_card_no" name="identity_card_no" value="{{ old('identity_card_no', $promoter->identity_card_no) }}" required>
                    @error('identity_card_no')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="phone_no" class="form-label">Phone No. *</label>
                    <input type="text" class="form-control @error('phone_no') is-invalid @enderror" 
                           id="phone_no" name="phone_no" value="{{ old('phone_no', $promoter->phone_no) }}" required>
                    @error('phone_no')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 1.5rem;">
                <div class="form-group">
                    <label for="status" class="form-label">Status *</label>
                    <select class="form-control @error('status') is-invalid @enderror" 
                            id="status" name="status" required>
                        <option value="">Select Status</option>
                        <option value="active" {{ old('status', $promoter->status) == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ old('status', $promoter->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        <option value="suspended" {{ old('status', $promoter->status) == 'suspended' ? 'selected' : '' }}>Suspended</option>
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
                               id="bank_name" name="bank_name" value="{{ old('bank_name', $promoter->bank_name) }}" required>
                        @error('bank_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="bank_branch_name" class="form-label">Bank Branch Name *</label>
                        <input type="text" class="form-control @error('bank_branch_name') is-invalid @enderror" 
                               id="bank_branch_name" name="bank_branch_name" value="{{ old('bank_branch_name', $promoter->bank_branch_name) }}" required>
                        @error('bank_branch_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="form-group">
                    <label for="bank_account_number" class="form-label">Bank Account Number *</label>
                    <input type="text" class="form-control @error('bank_account_number') is-invalid @enderror" 
                           id="bank_account_number" name="bank_account_number" value="{{ old('bank_account_number', $promoter->bank_account_number) }}" required>
                    @error('bank_account_number')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div style="display: flex; gap: 1rem; justify-content: flex-end;">
                <a href="{{ route('admin.promoters.index') }}" class="btn btn-secondary">Cancel</a>
                <button type="submit" class="btn btn-success">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 8px;">
                        <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path>
                        <polyline points="17,21 17,13 7,13 7,21"></polyline>
                        <polyline points="7,3 7,8 15,8"></polyline>
                    </svg>
                    Update Promoter
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
