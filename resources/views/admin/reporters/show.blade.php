@extends('layouts.admin')

@section('title', 'Reporter Details')
@section('page-title', 'Reporter Details')

@section('breadcrumbs')
    <span class="breadcrumb-separator">›</span>
    <a href="{{ route('admin.reporters.index') }}" class="breadcrumb-item">Reporters</a>
    <span class="breadcrumb-separator">›</span>
    <span class="breadcrumb-item active">Details</span>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h3>Reporter Information</h3>
    </div>
    <div class="card-body">
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem;">
            <div>
                <h4 style="margin-bottom: 1rem; color: #1f2937;">Basic Information</h4>
                <div style="margin-bottom: 1rem;">
                    <strong>Name:</strong> {{ $reporter->name }}
                </div>
                <div style="margin-bottom: 1rem;">
                    <strong>Email:</strong> {{ $reporter->email }}
                </div>
                <div style="margin-bottom: 1rem;">
                    <strong>Xelenic ID:</strong> {{ $reporter->xelenic_id ?? 'N/A' }}
                </div>
                <div style="margin-bottom: 1rem;">
                    <strong>Created:</strong> {{ $reporter->created_at->format('M d, Y H:i') }}
                </div>
                <div style="margin-bottom: 1rem;">
                    <strong>Last Updated:</strong> {{ $reporter->updated_at->format('M d, Y H:i') }}
                </div>
            </div>
            
            <div>
                <h4 style="margin-bottom: 1rem; color: #1f2937;">Roles & Permissions</h4>
                
                <div style="margin-bottom: 1rem;">
                    <strong>Roles:</strong>
                    @if($reporter->roles->count() > 0)
                        <div style="margin-top: 0.5rem;">
                            @foreach($reporter->roles as $role)
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
                    @if($reporter->permissions->count() > 0)
                        <div style="margin-top: 0.5rem;">
                            @foreach($reporter->permissions as $permission)
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
            <a href="{{ route('admin.reporters.edit', $reporter) }}" class="btn">Edit Reporter</a>
            <a href="{{ route('admin.reporters.index') }}" class="btn">Back to Reporters</a>
        </div>
    </div>
</div>
@endsection
