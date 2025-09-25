@extends('layouts.admin')

@section('title', __('common.dashboard'))
@section('page-title', __('common.dashboard'))

@section('breadcrumbs')
    <span class="breadcrumb-separator">›</span>
    <span class="breadcrumb-item active">{{ __('common.dashboard') }}</span>
@endsection

@section('content')
<!-- Statistics Cards -->
<div class="stats-grid">
    <div class="stat-card">
        <h3>{{ $stats['total_clients'] }}</h3>
        <p>{{ __('common.total_clients') }}</p>
    </div>
    <div class="stat-card">
        <h3>{{ $stats['total_promoters'] }}</h3>
        <p>{{ __('common.total_promoters') }}</p>
    </div>
    <div class="stat-card">
        <h3>{{ $stats['total_coordinators'] }}</h3>
        <p>{{ __('common.total_coordinators') }}</p>
    </div>
    <div class="stat-card">
        <h3>{{ $stats['total_campaigns'] }}</h3>
        <p>{{ __('common.campaign_count') }}</p>
    </div>
</div>

<!-- Main Content Grid -->
<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem; margin-bottom: 2rem;">
    
    <!-- Campaign Count Graph (Last 6 Months) -->
    <div class="card">
        <div class="card-header">
            <h3>
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 8px;">
                    <path d="M3 3v18h18"></path>
                    <path d="M18.7 8l-5.1 5.2-2.8-2.7L7 14.3"></path>
                </svg>
                {{ __('common.campaign_count_last_6_months') }}
            </h3>
        </div>
        <div class="card-body">
            <canvas id="campaignChart" width="400" height="200"></canvas>
        </div>
    </div>

    <!-- Registered Promoters Count Graph (Last 6 Months) -->
    <div class="card">
        <div class="card-header">
            <h3>
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 8px;">
                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                    <circle cx="12" cy="7" r="4"></circle>
                </svg>
                {{ __('common.registered_promoters_last_6_months') }}
            </h3>
        </div>
        <div class="card-body">
            <canvas id="promotersChart" width="400" height="200"></canvas>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="card">
    <div class="card-header">
        <h3>
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 8px;">
                <path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z"></path>
            </svg>
            {{ __('common.quick_actions') }}
        </h3>
    </div>
    <div class="card-body">
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem;">
            <a href="{{ route('admin.clients.create') }}" class="btn btn-success" style="display: flex; align-items: center; justify-content: center; padding: 1rem;">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 8px;">
                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                    <circle cx="9" cy="7" r="4"></circle>
                    <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                    <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                </svg>
                {{ __('common.add_new_client') }}
            </a>
            <a href="{{ route('admin.promoters.create') }}" class="btn btn-primary" style="display: flex; align-items: center; justify-content: center; padding: 1rem;">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 8px;">
                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                    <circle cx="12" cy="7" r="4"></circle>
                    <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                </svg>
                {{ __('common.add_new_promoter') }}
            </a>
            <a href="{{ route('admin.coordinators.create') }}" class="btn btn-secondary" style="display: flex; align-items: center; justify-content: center; padding: 1rem;">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 8px;">
                    <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path>
                    <circle cx="9" cy="7" r="4"></circle>
                    <path d="M22 21v-2a4 4 0 0 0-3-3.87"></path>
                    <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                </svg>
                {{ __('common.add_new_coordinator') }}
            </a>
            <a href="{{ route('admin.salary-sheets.create') }}" class="btn btn-warning" style="display: flex; align-items: center; justify-content: center; padding: 1rem;">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 8px;">
                    <rect x="2" y="3" width="20" height="14" rx="2" ry="2"></rect>
                    <line x1="8" y1="21" x2="16" y2="21"></line>
                    <line x1="12" y1="17" x2="12" y2="21"></line>
                </svg>
                {{ __('common.create_salary_sheet') }}
            </a>
        </div>
    </div>
</div>

<style>
.btn-sm {
    padding: 0.25rem 0.5rem;
    font-size: 0.875rem;
    border-radius: 0.375rem;
}

.btn-info {
    background-color: #3b82f6;
    color: white;
    border: none;
}

.btn-warning {
    background-color: #f59e0b;
    color: white;
    border: none;
}

.btn-info:hover,
.btn-warning:hover {
    opacity: 0.9;
    transform: translateY(-1px);
}

.table-responsive {
    overflow-x: auto;
}

.table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 1rem;
}

.table th,
.table td {
    padding: 0.75rem;
    text-align: left;
    border-bottom: 1px solid #e5e7eb;
}

.table th {
    background-color: #f9fafb;
    font-weight: 600;
    color: #374151;
}

.table tbody tr:hover {
    background-color: #f9fafb;
}

@media (max-width: 768px) {
    div[style*="grid-template-columns"] {
        grid-template-columns: 1fr !important;
    }
    
    .stats-grid {
        grid-template-columns: repeat(2, 1fr) !important;
    }
}

@media (max-width: 480px) {
    .stats-grid {
        grid-template-columns: 1fr !important;
    }
}

/* Chart styling */
.card-body canvas {
    max-height: 300px;
    width: 100% !important;
    height: 300px !important;
}

.chart-container {
    position: relative;
    height: 300px;
    width: 100%;
}
</style>

<!-- Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Campaign Chart
    const campaignCtx = document.getElementById('campaignChart').getContext('2d');
    new Chart(campaignCtx, {
        type: 'line',
        data: {
            labels: {!! json_encode($campaignData['labels']) !!},
            datasets: [{
                label: 'Campaigns',
                data: {!! json_encode($campaignData['data']) !!},
                borderColor: '#3b82f6',
                backgroundColor: 'rgba(59, 130, 246, 0.1)',
                borderWidth: 3,
                fill: true,
                tension: 0.4,
                pointBackgroundColor: '#3b82f6',
                pointBorderColor: '#ffffff',
                pointBorderWidth: 2,
                pointRadius: 6,
                pointHoverRadius: 8
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(0, 0, 0, 0.1)'
                    },
                    ticks: {
                        stepSize: 1
                    }
                },
                x: {
                    grid: {
                        display: false
                    }
                }
            },
            elements: {
                point: {
                    hoverBackgroundColor: '#3b82f6'
                }
            }
        }
    });

    // Promoters Chart
    const promotersCtx = document.getElementById('promotersChart').getContext('2d');
    new Chart(promotersCtx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($promotersData['labels']) !!},
            datasets: [{
                label: 'Promoters',
                data: {!! json_encode($promotersData['data']) !!},
                backgroundColor: 'rgba(16, 185, 129, 0.8)',
                borderColor: '#10b981',
                borderWidth: 2,
                borderRadius: 6,
                borderSkipped: false,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(0, 0, 0, 0.1)'
                    },
                    ticks: {
                        stepSize: 1
                    }
                },
                x: {
                    grid: {
                        display: false
                    }
                }
            }
        }
    });
});
</script>
@endsection
