@extends('layouts.admin')

@section('title', 'Allowance Details')
@section('page-title', 'Allowance Details')

@section('breadcrumbs')
    <span class="breadcrumb-separator">›</span>
    <a href="{{ route('admin.allowances.index') }}" class="breadcrumb-item">Allowances</a>
    <span class="breadcrumb-separator">›</span>
    <span class="breadcrumb-item active">{{ $allowance->name }}</span>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <h3>Allowance Details</h3>
            <div style="display: flex; gap: 8px;">
                @can('edit allowances')
                    <a href="{{ route('admin.allowances.edit', $allowance) }}" class="btn btn-warning">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 8px;">
                            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                        </svg>
                        Edit
                    </a>
                @endcan
                @can('delete allowances')
                    <form action="{{ route('admin.allowances.destroy', $allowance) }}" method="POST" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this allowance?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 8px;">
                                <polyline points="3,6 5,6 21,6"></polyline>
                                <path d="M19,6v14a2,2 0 0,1 -2,2H7a2,2 0 0,1 -2,-2V6m3,0V4a2,2 0 0,1 2,-2h4a2,2 0 0,1 2,2v2"></path>
                            </svg>
                            Delete
                        </button>
                    </form>
                @endcan
            </div>
        </div>
    </div>
    <div class="card-body">
        <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 2rem; margin-bottom: 2rem;">
            <div>
                <div style="width: 80px; height: 80px; background: linear-gradient(135deg, #10b981 0%, #059669 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin-bottom: 1rem;">
                    <span style="color: white; font-weight: bold; font-size: 24px;">
                        {{ strtoupper(substr($allowance->name, 0, 2)) }}
                    </span>
                </div>
            </div>
            <div>
                <h2 style="margin-bottom: 0.5rem; color: #374151;">{{ $allowance->name }}</h2>
                <p style="color: #6b7280; margin-bottom: 1rem;">Allowance Information</p>
            </div>
        </div>

        <div style="background: #f8fafc; padding: 1.5rem; border-radius: 0.5rem; margin-bottom: 1.5rem;">
            <h4 style="margin-bottom: 1rem; color: #374151;">Description</h4>
            <p style="color: #4b5563; line-height: 1.6;">
                {{ $allowance->description ?: 'No description provided for this allowance.' }}
            </p>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
            <div style="background: #f8fafc; padding: 1.5rem; border-radius: 0.5rem;">
                <h4 style="margin-bottom: 1rem; color: #374151;">Created</h4>
                <p style="color: #4b5563; margin-bottom: 0.5rem;">
                    <strong>Date:</strong> {{ $allowance->created_at->format('F d, Y') }}
                </p>
                <p style="color: #4b5563;">
                    <strong>Time:</strong> {{ $allowance->created_at->format('h:i A') }}
                </p>
            </div>

            <div style="background: #f8fafc; padding: 1.5rem; border-radius: 0.5rem;">
                <h4 style="margin-bottom: 1rem; color: #374151;">Last Updated</h4>
                <p style="color: #4b5563; margin-bottom: 0.5rem;">
                    <strong>Date:</strong> {{ $allowance->updated_at->format('F d, Y') }}
                </p>
                <p style="color: #4b5563;">
                    <strong>Time:</strong> {{ $allowance->updated_at->format('h:i A') }}
                </p>
            </div>
        </div>

        <div style="margin-top: 2rem; padding-top: 1.5rem; border-top: 1px solid #e5e7eb;">
            <a href="{{ route('admin.allowances.index') }}" class="btn btn-secondary">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 8px;">
                    <path d="M19 12H5M12 19l-7-7 7-7"></path>
                </svg>
                Back to Allowances
            </a>
        </div>
    </div>
</div>

<style>
.btn-warning {
    background-color: #f59e0b;
    color: white;
    padding: 0.75rem 1.5rem;
    border: none;
    border-radius: 0.375rem;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    font-weight: 500;
    transition: all 0.2s;
}

.btn-warning:hover {
    background-color: #d97706;
    transform: translateY(-1px);
}

.btn-danger {
    background-color: #ef4444;
    color: white;
    padding: 0.75rem 1.5rem;
    border: none;
    border-radius: 0.375rem;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    font-weight: 500;
    transition: all 0.2s;
}

.btn-danger:hover {
    background-color: #dc2626;
    transform: translateY(-1px);
}

.btn-secondary {
    background-color: #6b7280;
    color: white;
    padding: 0.75rem 1.5rem;
    border: none;
    border-radius: 0.375rem;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    font-weight: 500;
    transition: all 0.2s;
}

.btn-secondary:hover {
    background-color: #4b5563;
    transform: translateY(-1px);
}

@media (max-width: 768px) {
    div[style*="grid-template-columns"] {
        grid-template-columns: 1fr !important;
    }
}
</style>
@endsection
