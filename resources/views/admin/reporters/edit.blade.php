@extends('layouts.admin')

@section('title', 'Edit Reporter')
@section('page-title', 'Edit Reporter')

@section('breadcrumbs')
    <span class="breadcrumb-separator">›</span>
    <a href="{{ route('admin.reporters.index') }}" class="breadcrumb-item">Reporters</a>
    <span class="breadcrumb-separator">›</span>
    <span class="breadcrumb-item active">Edit</span>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h3>Edit Reporter Information</h3>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('admin.reporters.update', $reporter) }}">
            @csrf
            @method('PUT')
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1rem;">
                <div>
                    <label for="name" style="display: block; margin-bottom: 0.5rem; font-weight: 500;">Full Name *</label>
                    <input type="text" id="name" name="name" value="{{ old('name', $reporter->name) }}" required
                           style="width: 100%; padding: 0.75rem; border: 2px solid #e1e5e9; border-radius: 5px;">
                    @error('name')
                        <div style="color: #e74c3c; font-size: 0.9rem; margin-top: 0.5rem;">{{ $message }}</div>
                    @enderror
                </div>
                
                <div>
                    <label for="email" style="display: block; margin-bottom: 0.5rem; font-weight: 500;">Email Address *</label>
                    <input type="email" id="email" name="email" value="{{ old('email', $reporter->email) }}" required
                           style="width: 100%; padding: 0.75rem; border: 2px solid #e1e5e9; border-radius: 5px;">
                    @error('email')
                        <div style="color: #e74c3c; font-size: 0.9rem; margin-top: 0.5rem;">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1rem;">
                <div>
                    <label for="password" style="display: block; margin-bottom: 0.5rem; font-weight: 500;">New Password (leave blank to keep current)</label>
                    <input type="password" id="password" name="password"
                           style="width: 100%; padding: 0.75rem; border: 2px solid #e1e5e9; border-radius: 5px;">
                    @error('password')
                        <div style="color: #e74c3c; font-size: 0.9rem; margin-top: 0.5rem;">{{ $message }}</div>
                    @enderror
                </div>
                
                <div>
                    <label for="password_confirmation" style="display: block; margin-bottom: 0.5rem; font-weight: 500;">Confirm New Password</label>
                    <input type="password" id="password_confirmation" name="password_confirmation"
                           style="width: 100%; padding: 0.75rem; border: 2px solid #e1e5e9; border-radius: 5px;">
                </div>
            </div>

            <div style="margin-bottom: 1rem;">
                <label for="xelenic_id" style="display: block; margin-bottom: 0.5rem; font-weight: 500;">Xelenic ID</label>
                <input type="text" id="xelenic_id" name="xelenic_id" value="{{ old('xelenic_id', $reporter->xelenic_id) }}"
                       style="width: 100%; padding: 0.75rem; border: 2px solid #e1e5e9; border-radius: 5px;">
                @error('xelenic_id')
                    <div style="color: #e74c3c; font-size: 0.9rem; margin-top: 0.5rem;">{{ $message }}</div>
                @enderror
            </div>

            <div style="margin-bottom: 1rem;">
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 500;">Roles</label>
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 0.5rem;">
                    @foreach($roles as $role)
                        <label style="display: flex; align-items: center; padding: 0.5rem; border: 1px solid #e1e5e9; border-radius: 5px; cursor: pointer;">
                            <input type="checkbox" name="roles[]" value="{{ $role->name }}" 
                                   {{ in_array($role->name, old('roles', $userRoles)) ? 'checked' : '' }}
                                   style="margin-right: 0.5rem;">
                            {{ $role->name }}
                        </label>
                    @endforeach
                </div>
                @error('roles')
                    <div style="color: #e74c3c; font-size: 0.9rem; margin-top: 0.5rem;">{{ $message }}</div>
                @enderror
            </div>

            <div style="display: flex; gap: 1rem;">
                <button type="submit" class="btn btn-success">Update Reporter</button>
                <a href="{{ route('admin.reporters.index') }}" class="btn">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
