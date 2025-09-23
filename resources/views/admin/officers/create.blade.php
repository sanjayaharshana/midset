@extends('layouts.admin')

@section('title', 'Create Officer')
@section('page-title', 'Create New Officer')

@section('breadcrumbs')
    <span class="breadcrumb-separator">›</span>
    <a href="{{ route('admin.officers.index') }}" class="breadcrumb-item">Officers</a>
    <span class="breadcrumb-separator">›</span>
    <span class="breadcrumb-item active">Create</span>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h3>Officer Information</h3>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('admin.officers.store') }}">
            @csrf
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1rem;">
                <div>
                    <label for="name" style="display: block; margin-bottom: 0.5rem; font-weight: 500;">Full Name *</label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}" required
                           style="width: 100%; padding: 0.75rem; border: 2px solid #e1e5e9; border-radius: 5px;">
                    @error('name')
                        <div style="color: #e74c3c; font-size: 0.9rem; margin-top: 0.5rem;">{{ $message }}</div>
                    @enderror
                </div>
                
                <div>
                    <label for="email" style="display: block; margin-bottom: 0.5rem; font-weight: 500;">Email Address *</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" required
                           style="width: 100%; padding: 0.75rem; border: 2px solid #e1e5e9; border-radius: 5px;">
                    @error('email')
                        <div style="color: #e74c3c; font-size: 0.9rem; margin-top: 0.5rem;">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1rem;">
                <div>
                    <label for="password" style="display: block; margin-bottom: 0.5rem; font-weight: 500;">Password *</label>
                    <input type="password" id="password" name="password" required
                           style="width: 100%; padding: 0.75rem; border: 2px solid #e1e5e9; border-radius: 5px;">
                    @error('password')
                        <div style="color: #e74c3c; font-size: 0.9rem; margin-top: 0.5rem;">{{ $message }}</div>
                    @enderror
                </div>
                
                <div>
                    <label for="password_confirmation" style="display: block; margin-bottom: 0.5rem; font-weight: 500;">Confirm Password *</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" required
                           style="width: 100%; padding: 0.75rem; border: 2px solid #e1e5e9; border-radius: 5px;">
                </div>
            </div>

            <div style="margin-bottom: 1rem;">
                <label for="xelenic_id" style="display: block; margin-bottom: 0.5rem; font-weight: 500;">Xelenic ID</label>
                <input type="text" id="xelenic_id" name="xelenic_id" value="{{ old('xelenic_id') }}"
                       style="width: 100%; padding: 0.75rem; border: 2px solid #e1e5e9; border-radius: 5px;">
                @error('xelenic_id')
                    <div style="color: #e74c3c; font-size: 0.9rem; margin-top: 0.5rem;">{{ $message }}</div>
                @enderror
            </div>

            <div style="display: flex; gap: 1rem;">
                <button type="submit" class="btn btn-success">Create Officer</button>
                <a href="{{ route('admin.officers.index') }}" class="btn">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
