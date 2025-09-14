@extends('layouts.admin')

@section('title', 'Role Details')
@section('page-title', 'Role Details')

@section('breadcrumbs')
    <span class="breadcrumb-separator">›</span>
    <a href="{{ route('admin.roles.index') }}" class="breadcrumb-item">Roles</a>
    <span class="breadcrumb-separator">›</span>
    <span class="breadcrumb-item active">Details</span>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h3>Role Information</h3>
    </div>
    <div class="card-body">
        <div style="margin-bottom: 2rem;">
            <h4 style="margin-bottom: 1rem; color: #1f2937;">Basic Information</h4>
            <div style="margin-bottom: 1rem;">
                <strong>Role Name:</strong> {{ $role->name }}
            </div>
            <div style="margin-bottom: 1rem;">
                <strong>Created:</strong> {{ $role->created_at->format('M d, Y H:i') }}
            </div>
            <div style="margin-bottom: 1rem;">
                <strong>Last Updated:</strong> {{ $role->updated_at->format('M d, Y H:i') }}
            </div>
        </div>
        
        <div>
            <h4 style="margin-bottom: 1rem; color: #1f2937;">Permissions</h4>
            @if($role->permissions->count() > 0)
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 0.5rem;">
                    @foreach($role->permissions as $permission)
                        <div style="padding: 0.75rem; border: 1px solid #e1e5e9; border-radius: 5px; background: #f9fafb;">
                            <strong>{{ $permission->name }}</strong>
                        </div>
                    @endforeach
                </div>
            @else
                <p style="color: #6b7280;">No permissions assigned to this role.</p>
            @endif
        </div>

        <div style="margin-top: 2rem; display: flex; gap: 1rem;">
            <a href="{{ route('admin.roles.edit', $role) }}" class="btn">Edit Role</a>
            <a href="{{ route('admin.roles.index') }}" class="btn">Back to Roles</a>
        </div>
    </div>
</div>
@endsection
