@extends('layouts.admin')

@section('title', 'Settings')
@section('page-title', 'System Settings')

@section('breadcrumbs')
    <span class="breadcrumb-separator">›</span>
    <span class="breadcrumb-item active">Settings</span>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h3>System Settings</h3>
        <p style="color: #6b7280; margin-top: 0.5rem;">Manage your application settings and configuration.</p>
    </div>
    <div class="card-body">
        <!-- Tab Navigation -->
        <div class="settings-tabs">
            <nav class="tab-nav">
                @foreach($settings as $group => $groupSettings)
                <button type="button" 
                        class="tab-button {{ $loop->first ? 'active' : '' }}" 
                        data-tab="{{ $group }}"
                        onclick="switchTab('{{ $group }}')">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 8px;">
                        @if($group === 'company')
                            <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                            <polyline points="9,22 9,12 15,12 15,22"></polyline>
                        @elseif($group === 'system')
                            <circle cx="12" cy="12" r="3"></circle>
                            <path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1 1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"></path>
                        @elseif($group === 'email')
                            <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                            <polyline points="22,6 12,13 2,6"></polyline>
                        @elseif($group === 'notifications')
                            <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path>
                            <path d="M13.73 21a2 2 0 0 1-3.46 0"></path>
                        @else
                            <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                            <circle cx="9" cy="9" r="2"></circle>
                            <path d="M21 15.5V19a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-3.5"></path>
                        @endif
                    </svg>
                    {{ ucfirst(str_replace('_', ' ', $group)) }}
                </button>
                @endforeach
            </nav>
        </div>

        <form method="POST" action="{{ route('admin.settings.update') }}">
            @csrf
            @method('PUT')
            
            @foreach($settings as $group => $groupSettings)
            <div class="tab-content {{ $loop->first ? 'active' : '' }}" id="tab-{{ $group }}">
                <div class="tab-header">
                    <h4>{{ ucfirst(str_replace('_', ' ', $group)) }} Settings</h4>
                    <p>Configure your {{ strtolower(str_replace('_', ' ', $group)) }} preferences and settings.</p>
                </div>
                
                <div class="settings-grid">
                    @foreach($groupSettings as $setting)
                    <div class="form-group">
                        <label for="setting_{{ $setting->key }}" class="form-label">
                            {{ $setting->label }}
                            @if($setting->description)
                                <small class="form-description">
                                    {{ $setting->description }}
                                </small>
                            @endif
                        </label>
                        
                        @if($setting->type === 'textarea')
                            <textarea 
                                class="form-control @error('settings.' . $setting->key) is-invalid @enderror" 
                                id="setting_{{ $setting->key }}" 
                                name="settings[{{ $setting->key }}]" 
                                rows="4"
                                placeholder="Enter {{ strtolower($setting->label) }}...">{{ old('settings.' . $setting->key, $setting->value) }}</textarea>
                        @elseif($setting->type === 'boolean')
                            <select 
                                class="form-control @error('settings.' . $setting->key) is-invalid @enderror" 
                                id="setting_{{ $setting->key }}" 
                                name="settings[{{ $setting->key }}]">
                                <option value="1" {{ old('settings.' . $setting->key, $setting->value) == '1' ? 'selected' : '' }}>Yes</option>
                                <option value="0" {{ old('settings.' . $setting->key, $setting->value) == '0' ? 'selected' : '' }}>No</option>
                            </select>
                        @elseif($setting->type === 'number')
                            <input 
                                type="number" 
                                class="form-control @error('settings.' . $setting->key) is-invalid @enderror" 
                                id="setting_{{ $setting->key }}" 
                                name="settings[{{ $setting->key }}]" 
                                value="{{ old('settings.' . $setting->key, $setting->value) }}"
                                placeholder="Enter {{ strtolower($setting->label) }}...">
                        @elseif($setting->type === 'email')
                            <input 
                                type="email" 
                                class="form-control @error('settings.' . $setting->key) is-invalid @enderror" 
                                id="setting_{{ $setting->key }}" 
                                name="settings[{{ $setting->key }}]" 
                                value="{{ old('settings.' . $setting->key, $setting->value) }}"
                                placeholder="Enter {{ strtolower($setting->label) }}...">
                        @elseif($setting->type === 'url')
                            <input 
                                type="url" 
                                class="form-control @error('settings.' . $setting->key) is-invalid @enderror" 
                                id="setting_{{ $setting->key }}" 
                                name="settings[{{ $setting->key }}]" 
                                value="{{ old('settings.' . $setting->key, $setting->value) }}"
                                placeholder="Enter {{ strtolower($setting->label) }}...">
                        @else
                            <input 
                                type="text" 
                                class="form-control @error('settings.' . $setting->key) is-invalid @enderror" 
                                id="setting_{{ $setting->key }}" 
                                name="settings[{{ $setting->key }}]" 
                                value="{{ old('settings.' . $setting->key, $setting->value) }}"
                                placeholder="Enter {{ strtolower($setting->label) }}...">
                        @endif
                        
                        @error('settings.' . $setting->key)
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    @endforeach
                </div>
            </div>
            @endforeach

            <div class="form-actions">
                <button type="submit" class="btn btn-success">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 8px;">
                        <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path>
                        <polyline points="17,21 17,13 7,13 7,21"></polyline>
                        <polyline points="7,3 7,8 15,8"></polyline>
                    </svg>
                    Save All Settings
                </button>
            </div>
        </form>
    </div>
</div>

<style>
/* Tab Navigation Styles */
.settings-tabs {
    margin-bottom: 2rem;
}

.tab-nav {
    display: flex;
    border-bottom: 2px solid #e5e7eb;
    margin-bottom: 0;
    overflow-x: auto;
    scrollbar-width: none;
    -ms-overflow-style: none;
}

.tab-nav::-webkit-scrollbar {
    display: none;
}

.tab-button {
    background: none;
    border: none;
    padding: 1rem 1.5rem;
    cursor: pointer;
    display: flex;
    align-items: center;
    font-weight: 500;
    color: #6b7280;
    border-bottom: 3px solid transparent;
    transition: all 0.3s ease;
    white-space: nowrap;
    font-size: 0.875rem;
}

.tab-button:hover {
    color: #374151;
    background-color: #f9fafb;
}

.tab-button.active {
    color: #3b82f6;
    border-bottom-color: #3b82f6;
    background-color: #f8fafc;
}

/* Tab Content Styles */
.tab-content {
    display: none;
    animation: fadeIn 0.3s ease-in-out;
}

.tab-content.active {
    display: block;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}

.tab-header {
    margin-bottom: 2rem;
    padding-bottom: 1rem;
    border-bottom: 1px solid #e5e7eb;
}

.tab-header h4 {
    margin: 0 0 0.5rem 0;
    color: #1f2937;
    font-size: 1.25rem;
    font-weight: 600;
}

.tab-header p {
    margin: 0;
    color: #6b7280;
    font-size: 0.875rem;
}

/* Settings Grid */
.settings-grid {
    display: grid;
    gap: 1.5rem;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
}

/* Form Styles */
.form-group {
    margin-bottom: 1.5rem;
}

.form-label {
    display: block;
    margin-bottom: 0.5rem;
    font-weight: 600;
    color: #374151;
    font-size: 0.875rem;
}

.form-description {
    display: block;
    color: #6b7280;
    font-weight: normal;
    margin-top: 0.25rem;
    font-size: 0.8rem;
    line-height: 1.4;
}

.form-control {
    width: 100%;
    padding: 0.75rem;
    border: 1px solid #d1d5db;
    border-radius: 0.375rem;
    font-size: 0.875rem;
    transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
    background-color: #ffffff;
}

.form-control:focus {
    outline: none;
    border-color: #3b82f6;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

.form-control.is-invalid {
    border-color: #ef4444;
}

.invalid-feedback {
    display: block;
    width: 100%;
    margin-top: 0.25rem;
    font-size: 0.875rem;
    color: #ef4444;
}

/* Form Actions */
.form-actions {
    display: flex;
    gap: 1rem;
    justify-content: flex-end;
    margin-top: 2rem;
    padding-top: 1.5rem;
    border-top: 1px solid #e5e7eb;
}

.btn-success {
    background-color: #10b981;
    color: white;
    padding: 0.75rem 1.5rem;
    border: none;
    border-radius: 0.375rem;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    font-weight: 500;
    transition: all 0.2s;
    cursor: pointer;
    font-size: 0.875rem;
}

.btn-success:hover {
    background-color: #059669;
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
}

/* Responsive Design */
@media (max-width: 768px) {
    .tab-nav {
        flex-direction: column;
        border-bottom: none;
    }
    
    .tab-button {
        border-bottom: none;
        border-left: 3px solid transparent;
        justify-content: flex-start;
    }
    
    .tab-button.active {
        border-left-color: #3b82f6;
        border-bottom-color: transparent;
    }
    
    .settings-grid {
        grid-template-columns: 1fr;
        gap: 1rem;
    }
    
    .form-actions {
        flex-direction: column;
    }
    
    .btn-success {
        width: 100%;
        justify-content: center;
    }
}

@media (max-width: 480px) {
    .tab-button {
        padding: 0.75rem 1rem;
        font-size: 0.8rem;
    }
    
    .tab-header h4 {
        font-size: 1.1rem;
    }
    
    .form-control {
        padding: 0.625rem;
    }
}
</style>

<script>
function switchTab(tabName) {
    // Remove active class from all tabs and content
    document.querySelectorAll('.tab-button').forEach(button => {
        button.classList.remove('active');
    });
    
    document.querySelectorAll('.tab-content').forEach(content => {
        content.classList.remove('active');
    });
    
    // Add active class to clicked tab
    document.querySelector(`[data-tab="${tabName}"]`).classList.add('active');
    
    // Show corresponding content
    document.getElementById(`tab-${tabName}`).classList.add('active');
    
    // Update URL hash for bookmarking
    window.location.hash = tabName;
}

// Handle browser back/forward buttons
window.addEventListener('hashchange', function() {
    const hash = window.location.hash.substring(1);
    if (hash && document.querySelector(`[data-tab="${hash}"]`)) {
        switchTab(hash);
    }
});

// Initialize tab from URL hash on page load
document.addEventListener('DOMContentLoaded', function() {
    const hash = window.location.hash.substring(1);
    if (hash && document.querySelector(`[data-tab="${hash}"]`)) {
        switchTab(hash);
    }
});

// Keyboard navigation for tabs
document.addEventListener('keydown', function(e) {
    if (e.altKey && e.key >= '1' && e.key <= '9') {
        e.preventDefault();
        const tabIndex = parseInt(e.key) - 1;
        const tabs = document.querySelectorAll('.tab-button');
        if (tabs[tabIndex]) {
            const tabName = tabs[tabIndex].getAttribute('data-tab');
            switchTab(tabName);
        }
    }
});

// Auto-save functionality (optional)
let autoSaveTimeout;
function autoSave() {
    clearTimeout(autoSaveTimeout);
    autoSaveTimeout = setTimeout(() => {
        // You can implement auto-save logic here
        console.log('Auto-save triggered');
    }, 2000);
}

// Add auto-save listeners to form inputs
document.addEventListener('DOMContentLoaded', function() {
    const formInputs = document.querySelectorAll('.form-control');
    formInputs.forEach(input => {
        input.addEventListener('input', autoSave);
    });
});
</script>
@endsection
