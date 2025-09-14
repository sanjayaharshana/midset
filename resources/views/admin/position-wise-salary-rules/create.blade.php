@extends('layouts.admin')

@section('title', 'Create Position Wise Salary Rule')
@section('page-title', 'Create Position Wise Salary Rule')

@section('breadcrumbs')
    <span class="breadcrumb-separator">›</span>
    <a href="{{ route('admin.position-wise-salary-rules.index') }}" class="breadcrumb-item">Position Wise Salary Rules</a>
    <span class="breadcrumb-separator">›</span>
    <span class="breadcrumb-item active">Create</span>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <h3>Create Position Wise Salary Rule</h3>
            <a href="{{ route('admin.position-wise-salary-rules.index') }}" class="btn btn-secondary">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 8px;">
                    <line x1="19" y1="12" x2="5" y2="12"></line>
                    <polyline points="12,19 5,12 12,5"></polyline>
                </svg>
                Back to Rules
            </a>
        </div>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.position-wise-salary-rules.store') }}" method="POST">
            @csrf
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem;">
                <div class="form-group">
                    <label for="position_id" class="form-label">Position</label>
                    <select class="form-control @error('position_id') is-invalid @enderror" id="position_id" name="position_id" required>
                        <option value="">Select Position</option>
                        @foreach($positions as $position)
                            <option value="{{ $position->id }}" {{ old('position_id') == $position->id ? 'selected' : '' }}>
                                {{ $position->position_name }}
                            </option>
                        @endforeach
                    </select>
                    @error('position_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="job_id" class="form-label">Job (Optional)</label>
                    <select class="form-control @error('job_id') is-invalid @enderror" id="job_id" name="job_id">
                        <option value="">Select Job (Optional)</option>
                        @foreach($jobs as $job)
                            <option value="{{ $job->id }}" {{ old('job_id') == $job->id ? 'selected' : '' }}>
                                {{ $job->job_number }} - {{ $job->job_name }}
                            </option>
                        @endforeach
                    </select>
                    @error('job_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="form-group" style="margin-top: 1.5rem;">
                <label for="amount" class="form-label">Amount (Rs.)</label>
                <input type="number" step="0.01" min="0" class="form-control @error('amount') is-invalid @enderror" id="amount" name="amount" value="{{ old('amount') }}" placeholder="Enter amount" required>
                @error('amount')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group" style="margin-top: 1.5rem;">
                <label for="description" class="form-label">Description (Optional)</label>
                <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="3" placeholder="Enter description...">{{ old('description') }}</textarea>
                @error('description')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group" style="margin-top: 1.5rem;">
                <label for="status" class="form-label">Status</label>
                <select class="form-control @error('status') is-invalid @enderror" id="status" name="status" required>
                    <option value="">Select Status</option>
                    <option value="active" {{ old('status', 'active') == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
                @error('status')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div style="margin-top: 2rem; display: flex; gap: 1rem;">
                <button type="submit" class="btn btn-primary">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 8px;">
                        <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path>
                        <polyline points="17,21 17,13 7,13 7,21"></polyline>
                        <polyline points="7,3 7,8 15,8"></polyline>
                    </svg>
                    Create Rule
                </button>
                <a href="{{ route('admin.position-wise-salary-rules.index') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
