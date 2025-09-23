@extends('layouts.admin')

@section('title', 'Officer Details')
@section('page-title', 'Officer Details')

@section('breadcrumbs')
    <span class="breadcrumb-separator">›</span>
    <a href="{{ route('admin.officers.index') }}" class="breadcrumb-item">Officers</a>
    <span class="breadcrumb-separator">›</span>
    <span class="breadcrumb-item active">Details</span>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h3>Officer Information</h3>
    </div>
    <div class="card-body">
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem;">
            <div>
                <h4 style="margin-bottom: 1rem; color: #1f2937;">Basic Information</h4>
                <div style="margin-bottom: 1rem;">
                    <strong>Name:</strong> {{ $officer->name }}
                </div>
                <div style="margin-bottom: 1rem;">
                    <strong>Email:</strong> {{ $officer->email }}
                </div>
                <div style="margin-bottom: 1rem;">
                    <strong>Xelenic ID:</strong> {{ $officer->xelenic_id ?? 'N/A' }}
                </div>
                <div style="margin-bottom: 1rem;">
                    <strong>Created:</strong> {{ $officer->created_at->format('M d, Y H:i') }}
                </div>
                <div style="margin-bottom: 1rem;">
                    <strong>Last Updated:</strong> {{ $officer->updated_at->format('M d, Y H:i') }}
                </div>
            </div>
            
            <div>
                <h4 style="margin-bottom: 1rem; color: #1f2937;">Roles & Permissions</h4>
                
                <div style="margin-bottom: 1rem;">
                    <strong>Roles:</strong>
                    @if($officer->roles->count() > 0)
                        <div style="margin-top: 0.5rem;">
                            @foreach($officer->roles as $role)
                                <span style="padding: 0.25rem 0.5rem; border-radius: 3px; font-size: 0.8rem; 
                                    background-color: #dbeafe; color: #1e40af; margin-right: 0.25rem; display: inline-block; margin-bottom: 0.25rem;">
                                    {{ $role->name }}
                                </span>
                            @endforeach
                        </div>
                    @else
                        <span style="color: #6b7280; font-size: 0.9rem;">No roles assigned</span>
                    @endif
                </div>

                <div style="margin-bottom: 1rem;">
                    <strong>Direct Permissions:</strong>
                    @if($officer->permissions->count() > 0)
                        <div style="margin-top: 0.5rem;">
                            @foreach($officer->permissions as $permission)
                                <span style="padding: 0.25rem 0.5rem; border-radius: 3px; font-size: 0.8rem; 
                                    background-color: #f0fdf4; color: #166534; margin-right: 0.25rem; display: inline-block; margin-bottom: 0.25rem;">
                                    {{ $permission->name }}
                                </span>
                            @endforeach
                        </div>
                    @else
                        <span style="color: #6b7280; font-size: 0.9rem;">No direct permissions</span>
                    @endif
                </div>
            </div>
        </div>

        <div style="margin-top: 2rem; display: flex; gap: 1rem;">
            <a href="{{ route('admin.officers.edit', $officer) }}" class="btn">Edit Officer</a>
            <a href="{{ route('admin.officers.index') }}" class="btn">Back to Officers</a>
        </div>
    </div>
</div>
@endsection
