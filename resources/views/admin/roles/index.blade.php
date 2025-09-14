@extends('layouts.admin')

@section('title', 'Role Management')
@section('page-title', 'Role Management')

@section('breadcrumbs')
    <span class="breadcrumb-separator">›</span>
    <span class="breadcrumb-item active">Roles</span>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h3>All Roles</h3>
        <div style="margin-top: 1rem;">
            <a href="{{ route('admin.roles.create') }}" class="btn btn-success">Create New Role</a>
        </div>
    </div>
    <div class="card-body">
        @if($roles->count() > 0)
            <table class="table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Permissions</th>
                        <th>Created At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($roles as $role)
                    <tr>
                        <td>{{ $role->name }}</td>
                        <td>
                            @if($role->permissions->count() > 0)
                                @foreach($role->permissions->take(3) as $permission)
                                    <span style="padding: 0.25rem 0.5rem; border-radius: 3px; font-size: 0.8rem; 
                                        background-color: #f0fdf4; color: #166534; margin-right: 0.25rem;">
                                        {{ $permission->name }}
                                    </span>
                                @endforeach
                                @if($role->permissions->count() > 3)
                                    <span style="color: #6b7280; font-size: 0.8rem;">+{{ $role->permissions->count() - 3 }} more</span>
                                @endif
                            @else
                                <span style="color: #6b7280; font-size: 0.8rem;">No permissions</span>
                            @endif
                        </td>
                        <td>{{ $role->created_at->format('M d, Y') }}</td>
                        <td>
                            <a href="{{ route('admin.roles.show', $role) }}" class="btn" style="padding: 0.5rem; font-size: 0.8rem;">View</a>
                            <a href="{{ route('admin.roles.edit', $role) }}" class="btn" style="padding: 0.5rem; font-size: 0.8rem;">Edit</a>
                            <form method="POST" action="{{ route('admin.roles.destroy', $role) }}" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this role?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger" style="padding: 0.5rem; font-size: 0.8rem;">Delete</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            
            <div style="margin-top: 1rem;">
                {{ $roles->links() }}
            </div>
        @else
            <p>No roles found. <a href="{{ route('admin.roles.create') }}">Create the first role</a></p>
        @endif
    </div>
</div>
@endsection
