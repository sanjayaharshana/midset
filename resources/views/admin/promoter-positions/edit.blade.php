@extends('layouts.admin')

@section('title', 'Edit Promoter Position')
@section('page-title', 'Edit Position')

@section('breadcrumbs')
    <span class="breadcrumb-separator">›</span>
    <a href="{{ route('admin.promoter-positions.index') }}" class="breadcrumb-item">Promoter Positions</a>
    <span class="breadcrumb-separator">›</span>
    <span class="breadcrumb-item active">Edit</span>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h3>Edit Position Information</h3>
        <p style="color: #6b7280; margin-top: 0.5rem;">Update the promoter position details.</p>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.promoter-positions.update', $promoterPosition) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 1.5rem;">
                <div class="form-group">
                    <label for="position_name" class="form-label">Position Name *</label>
                    <input type="text" class="form-control @error('position_name') is-invalid @enderror" 
                           id="position_name" name="position_name" value="{{ old('position_name', $promoterPosition->position_name) }}" required
                           placeholder="e.g., Senior Promoter, Team Lead, etc.">
                    @error('position_name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="status" class="form-label">Status *</label>
                    <select class="form-control @error('status') is-invalid @enderror" 
                            id="status" name="status" required>
                        <option value="">Select Status</option>
                        <option value="active" {{ old('status', $promoterPosition->status) == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ old('status', $promoterPosition->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                    @error('status')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="form-group">
                <label for="description" class="form-label">Description</label>
                <textarea class="form-control @error('description') is-invalid @enderror" 
                          id="description" name="description" rows="4" 
                          placeholder="Optional description of the position, responsibilities, requirements, etc.">{{ old('description', $promoterPosition->description) }}</textarea>
                <small style="color: #6b7280; font-size: 0.75rem;">Maximum 1000 characters</small>
                @error('description')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div style="display: flex; gap: 1rem; justify-content: flex-end;">
                <a href="{{ route('admin.promoter-positions.index') }}" class="btn btn-secondary">Cancel</a>
                <button type="submit" class="btn btn-success">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 8px;">
                        <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path>
                        <polyline points="17,21 17,13 7,13 7,21"></polyline>
                        <polyline points="7,3 7,8 15,8"></polyline>
                    </svg>
                    Update Position
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

