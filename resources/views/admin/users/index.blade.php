@extends('layouts.admin')

@section('title', 'User Management')
@section('page-title', 'User Management')

@section('breadcrumbs')
    <span class="breadcrumb-separator">›</span>
    <span class="breadcrumb-item active">Users</span>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h3>All Users</h3>
        <div style="margin-top: 1rem;">
            <a href="{{ route('admin.users.create') }}" class="btn btn-success">Add New User</a>
        </div>
    </div>
    <div class="card-body">
        @if($users->count() > 0)
            <table class="table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Roles</th>
                        <th>Created At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                    <tr>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>
                            @if($user->roles->count() > 0)
                                @foreach($user->roles as $role)
                                    <span style="padding: 0.25rem 0.5rem; border-radius: 3px; font-size: 0.8rem; 
                                        background-color: #dbeafe; color: #1e40af; margin-right: 0.25rem;">
                                        {{ $role->name }}
                                    </span>
                                @endforeach
                            @else
                                <span style="color: #6b7280; font-size: 0.8rem;">No roles assigned</span>
                            @endif
                        </td>
                        <td>{{ $user->created_at->format('M d, Y') }}</td>
                        <td>
                            <a href="{{ route('admin.users.show', $user) }}" class="btn" style="padding: 0.5rem; font-size: 0.8rem;">View</a>
                            <a href="{{ route('admin.users.edit', $user) }}" class="btn" style="padding: 0.5rem; font-size: 0.8rem;">Edit</a>
                            @if($user->id !== Auth::id())
                                <form method="POST" action="{{ route('admin.users.destroy', $user) }}" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this user?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger" style="padding: 0.5rem; font-size: 0.8rem;">Delete</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            
            <div style="margin-top: 1rem;">
            <div class="pagination-container">
                {{ $users->links() }}
            </div>
            </div>
        @else
            <p>No users found. <a href="{{ route('admin.users.create') }}">Add the first user</a></p>
        @endif
    </div>
</div>
@endsection
