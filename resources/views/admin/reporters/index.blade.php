@extends('layouts.admin')

@section('title', 'Reporter Management')
@section('page-title', 'Reporter Management')

@section('breadcrumbs')
    <span class="breadcrumb-separator">›</span>
    <span class="breadcrumb-item active">Reporters</span>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h3>All Reporters</h3>
        <div style="margin-top: 1rem;">
            <a href="{{ route('admin.reporters.create') }}" class="btn btn-success">Add New Reporter</a>
        </div>
    </div>
    <div class="card-body">
        @if($reporters->count() > 0)
            <table class="table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Xelenic ID</th>
                        <th>Roles</th>
                        <th>Created At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($reporters as $reporter)
                    <tr>
                        <td>{{ $reporter->name }}</td>
                        <td>{{ $reporter->email }}</td>
                        <td>{{ $reporter->xelenic_id ?? 'N/A' }}</td>
                        <td>
                            @if($reporter->roles->count() > 0)
                                @foreach($reporter->roles as $role)
                                    <span style="padding: 0.25rem 0.5rem; border-radius: 3px; font-size: 0.8rem; 
                                        background-color: #dbeafe; color: #1e40af; margin-right: 0.25rem;">
                                        {{ $role->name }}
                                    </span>
                                @endforeach
                            @else
                                <span style="color: #6b7280; font-size: 0.8rem;">No roles assigned</span>
                            @endif
                        </td>
                        <td>{{ $reporter->created_at->format('M d, Y') }}</td>
                        <td>
                            <a href="{{ route('admin.reporters.show', $reporter) }}" class="btn" style="padding: 0.5rem; font-size: 0.8rem;">View</a>
                            <a href="{{ route('admin.reporters.edit', $reporter) }}" class="btn" style="padding: 0.5rem; font-size: 0.8rem;">Edit</a>
                            @if($reporter->id !== Auth::id())
                                <form method="POST" action="{{ route('admin.reporters.destroy', $reporter) }}" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this reporter?')">
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
                {{ $reporters->links() }}
            </div>
            </div>
        @else
            <p>No reporters found. <a href="{{ route('admin.reporters.create') }}">Add the first reporter</a></p>
        @endif
    </div>
</div>
@endsection
