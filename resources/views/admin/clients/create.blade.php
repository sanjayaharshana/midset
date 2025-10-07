@extends('layouts.admin')

@section('title', 'Create Brand')
@section('page-title', 'Add New Brand')

@section('breadcrumbs')
    <span class="breadcrumb-separator">›</span>
    <a href="{{ route('admin.clients.index') }}" class="breadcrumb-item">Brands</a>
    <span class="breadcrumb-separator">›</span>
    <span class="breadcrumb-item active">Create</span>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h3>Brand Information</h3>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.clients.store') }}" method="POST">
            @csrf
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 1.5rem;">
                <div class="form-group">
                    <label for="name" class="form-label">Brand Name *</label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                           id="name" name="name" value="{{ old('name') }}" required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="short_code" class="form-label">Short Code *</label>
                    <input type="text" class="form-control @error('short_code') is-invalid @enderror" 
                           id="short_code" name="short_code" value="{{ old('short_code') }}" 
                           maxlength="3" style="text-transform: uppercase;" required
                           placeholder="ABC">
                    <small style="color: #6b7280; font-size: 0.75rem;">3 uppercase letters (e.g., ABC)</small>
                    @error('short_code')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 1.5rem;">
                <div class="form-group">
                    <label for="email" class="form-label">Email Address *</label>
                    <input type="email" class="form-control @error('email') is-invalid @enderror" 
                           id="email" name="email" value="{{ old('email') }}" required>
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="phone" class="form-label">Phone Number</label>
                    <input type="text" class="form-control @error('phone') is-invalid @enderror" 
                           id="phone" name="phone" value="{{ old('phone') }}">
                    @error('phone')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 1.5rem;">
                <div class="form-group">
                    <label for="company_name" class="form-label">Company Name</label>
                    <input type="text" class="form-control @error('company_name') is-invalid @enderror" 
                           id="company_name" name="company_name" value="{{ old('company_name') }}">
                    @error('company_name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="contact_person" class="form-label">Contact Person</label>
                    <input type="text" class="form-control @error('contact_person') is-invalid @enderror" 
                           id="contact_person" name="contact_person" value="{{ old('contact_person') }}">
                    @error('contact_person')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="form-group" style="margin-bottom: 1.5rem;">
                <label for="company_address" class="form-label">Company Address</label>
                <textarea class="form-control @error('company_address') is-invalid @enderror" 
                          id="company_address" name="company_address" rows="3">{{ old('company_address') }}</textarea>
                @error('company_address')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group" style="margin-bottom: 1.5rem;">
                <label for="status" class="form-label">Status *</label>
                <select class="form-control @error('status') is-invalid @enderror" 
                        id="status" name="status" required>
                    <option value="">Select Status</option>
                    <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                    <option value="suspended" {{ old('status') == 'suspended' ? 'selected' : '' }}>Suspended</option>
                </select>
                @error('status')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div style="background: #f8fafc; padding: 1.5rem; border-radius: 0.5rem; margin-bottom: 1.5rem;">
                <h4 style="margin-bottom: 1rem; color: #374151;">Bank Details (Optional)</h4>
                
                <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 1rem;">
                    <div class="form-group">
                        <label for="bank_name" class="form-label">Bank Name</label>
                        <input type="text" class="form-control @error('bank_name') is-invalid @enderror" 
                               id="bank_name" name="bank_name" value="{{ old('bank_name') }}">
                        @error('bank_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="bank_account_number" class="form-label">Account Number</label>
                        <input type="text" class="form-control @error('bank_account_number') is-invalid @enderror" 
                               id="bank_account_number" name="bank_account_number" value="{{ old('bank_account_number') }}">
                        @error('bank_account_number')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="bank_routing_number" class="form-label">Routing Number</label>
                        <input type="text" class="form-control @error('bank_routing_number') is-invalid @enderror" 
                               id="bank_routing_number" name="bank_routing_number" value="{{ old('bank_routing_number') }}">
                        @error('bank_routing_number')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="form-group" style="margin-bottom: 1.5rem;">
                <label for="notes" class="form-label">Notes</label>
                <textarea class="form-control @error('notes') is-invalid @enderror" 
                          id="notes" name="notes" rows="4" placeholder="Additional notes about the brand...">{{ old('notes') }}</textarea>
                @error('notes')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div style="display: flex; gap: 1rem; justify-content: flex-end;">
                <a href="{{ route('admin.clients.index') }}" class="btn btn-secondary">Cancel</a>
                <button type="submit" class="btn btn-success">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 8px;">
                        <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path>
                        <polyline points="17,21 17,13 7,13 7,21"></polyline>
                        <polyline points="7,3 7,8 15,8"></polyline>
                    </svg>
                    Create Brand
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

/* JavaScript for short code input */
<script>
document.addEventListener('DOMContentLoaded', function() {
    const shortCodeInput = document.getElementById('short_code');
    if (shortCodeInput) {
        shortCodeInput.addEventListener('input', function(e) {
            e.target.value = e.target.value.toUpperCase().replace(/[^A-Z]/g, '').substring(0, 3);
        });
    }
});
</script>
@endsection
