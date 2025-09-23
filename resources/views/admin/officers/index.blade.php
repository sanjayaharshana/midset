@extends('layouts.admin')

@section('title', 'Officer Management')
@section('page-title', 'Officer Management')

@section('breadcrumbs')
    <span class="breadcrumb-separator">›</span>
    <span class="breadcrumb-item active">Officers</span>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h3>All Officers</h3>
        <div style="margin-top: 1rem;">
            <a href="{{ route('admin.officers.create') }}" class="btn btn-success">Add New Officer</a>
        </div>
    </div>
    <div class="card-body">
        @if($officers->count() > 0)
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
                    @foreach($officers as $officer)
                    <tr>
                        <td>{{ $officer->name }}</td>
                        <td>{{ $officer->email }}</td>
                        <td>{{ $officer->xelenic_id ?? 'N/A' }}</td>
                        <td>
                            @if($officer->roles->count() > 0)
                                @foreach($officer->roles as $role)
                                    <span style="padding: 0.25rem 0.5rem; border-radius: 3px; font-size: 0.8rem; 
                                        background-color: #dbeafe; color: #1e40af; margin-right: 0.25rem;">
                                        {{ $role->name }}
                                    </span>
                                @endforeach
                            @else
                                <span style="color: #6b7280; font-size: 0.8rem;">No roles assigned</span>
                            @endif
                        </td>
                        <td>{{ $officer->created_at->format('M d, Y') }}</td>
                        <td>
                            <a href="{{ route('admin.officers.show', $officer) }}" class="btn" style="padding: 0.5rem; font-size: 0.8rem;">View</a>
                            <a href="{{ route('admin.officers.edit', $officer) }}" class="btn" style="padding: 0.5rem; font-size: 0.8rem;">Edit</a>
                            @if($officer->id !== Auth::id())
                                <form method="POST" action="{{ route('admin.officers.destroy', $officer) }}" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this officer?')">
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
                {{ $officers->links() }}
            </div>
            </div>
        @else
            <p>No officers found. <a href="{{ route('admin.officers.create') }}">Add the first officer</a></p>
        @endif
    </div>
</div>
@endsection
