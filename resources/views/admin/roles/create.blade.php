@extends('layouts.admin')

@section('title', 'Create Role')
@section('page-title', 'Create New Role')

@section('breadcrumbs')
    <span class="breadcrumb-separator">›</span>
    <a href="{{ route('admin.roles.index') }}" class="breadcrumb-item">Roles</a>
    <span class="breadcrumb-separator">›</span>
    <span class="breadcrumb-item active">Create</span>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h3>Role Information</h3>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('admin.roles.store') }}">
            @csrf
            
            <div style="margin-bottom: 1rem;">
                <label for="name" style="display: block; margin-bottom: 0.5rem; font-weight: 500;">Role Name *</label>
                <input type="text" id="name" name="name" value="{{ old('name') }}" required
                       style="width: 100%; padding: 0.75rem; border: 2px solid #e1e5e9; border-radius: 5px;">
                @error('name')
                    <div style="color: #e74c3c; font-size: 0.9rem; margin-top: 0.5rem;">{{ $message }}</div>
                @enderror
            </div>

            <div style="margin-bottom: 1rem;">
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 500;">Permissions</label>
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 0.5rem; max-height: 300px; overflow-y: auto; border: 1px solid #e1e5e9; padding: 1rem; border-radius: 5px;">
                    @foreach($permissions as $permission)
                        <label style="display: flex; align-items: center; padding: 0.5rem; border: 1px solid #e1e5e9; border-radius: 5px; cursor: pointer;">
                            <input type="checkbox" name="permissions[]" value="{{ $permission->name }}" 
                                   {{ in_array($permission->name, old('permissions', [])) ? 'checked' : '' }}
                                   style="margin-right: 0.5rem;">
                            {{ $permission->name }}
                        </label>
                    @endforeach
                </div>
                @error('permissions')
                    <div style="color: #e74c3c; font-size: 0.9rem; margin-top: 0.5rem;">{{ $message }}</div>
                @enderror
            </div>

            <div style="display: flex; gap: 1rem;">
                <button type="submit" class="btn btn-success">Create Role</button>
                <a href="{{ route('admin.roles.index') }}" class="btn">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
