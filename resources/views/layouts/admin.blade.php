<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Panel') - Mindpark HRM</title>
    
    <!-- SweetAlert2 CSS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Select2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f9fafb;
            color: #374151;
        }

        .sidebar {
            position: fixed;
            left: 0;
            top: 0;
            width: 280px;
            height: 100vh;
            background: #ffffff;
            color: #374151;
            padding: 0;
            z-index: 1000;
            box-shadow: 2px 0 10px rgba(0, 0, 0, 0.05);
            border-right: 1px solid #e5e7eb;
            overflow-y: auto;
        }

        .sidebar-header {
            padding: 2rem 1.5rem;
            background: #f8fafc;
            border-bottom: 1px solid #e5e7eb;
        }

        .sidebar-header h2 {
            font-size: 1.6rem;
            margin-bottom: 0.5rem;
            font-weight: 600;
            color: #1f2937;
        }

        .sidebar-header p {
            font-size: 0.85rem;
            opacity: 0.7;
            font-weight: 400;
            color: #6b7280;
        }

        .sidebar-menu {
            list-style: none;
            padding: 1.5rem 0;
            margin: 0;
        }

        .sidebar-menu li {
            margin: 0.2rem 1rem;
        }

        .sidebar-menu a {
            display: flex;
            align-items: center;
            padding: 0.875rem 1.25rem;
            color: #6b7280;
            text-decoration: none;
            border-radius: 8px;
            transition: all 0.2s ease;
            font-weight: 500;
            font-size: 0.9rem;
        }

        .sidebar-menu a:hover {
            background: #f3f4f6;
            color: #1f2937;
            transform: translateX(3px);
        }

        .sidebar-menu a.active {
            background: #eff6ff;
            color: #1d4ed8;
            border-left: 3px solid #3b82f6;
        }

        .sidebar-menu a.active::after {
            content: '';
            position: absolute;
            right: 1rem;
            top: 50%;
            transform: translateY(-50%);
            width: 4px;
            height: 4px;
            background: #3b82f6;
            border-radius: 50%;
        }

        /* Menu Groups */
        .menu-group {
            margin-bottom: 1rem;
        }

        .menu-group-header {
            display: flex;
            align-items: center;
            padding: 0.75rem 1rem;
            font-size: 0.75rem;
            font-weight: 600;
            color: #6b7280;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            border-bottom: 1px solid #e5e7eb;
            margin-bottom: 0.5rem;
        }

        .submenu {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .submenu li {
            margin: 0;
        }

        .submenu li a {
            padding: 0.5rem 1rem 0.5rem 2rem;
            font-size: 0.875rem;
            border-radius: 0.375rem;
            margin: 0.125rem 0.5rem;
            display: flex;
            align-items: center;
            text-decoration: none;
            color: #6b7280;
            transition: all 0.2s;
        }

        .submenu li a:hover {
            background-color: #f3f4f6;
            color: #1f2937;
        }

        .submenu li a.active {
            background-color: #3b82f6;
            color: white;
        }

        .menu-item {
            margin-bottom: 0.5rem;
        }

        .main-content {
            margin-left: 280px;
            min-height: 100vh;
        }

        .header {
            background: #ffffff;
            padding: 1.5rem 2rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid #e5e7eb;
        }

        .header h1 {
            color: #1f2937;
            font-size: 1.75rem;
            font-weight: 600;
            margin: 0;
        }

        .user-menu {
            display: flex;
            align-items: center;
            gap: 1.5rem;
        }

        .user-menu span {
            color: #6b7280;
            font-weight: 500;
            font-size: 0.9rem;
        }

        .logout-btn {
            background: #6b7280;
            color: white;
            border: none;
            padding: 0.625rem 1.25rem;
            border-radius: 6px;
            cursor: pointer;
            text-decoration: none;
            font-size: 0.85rem;
            font-weight: 500;
            transition: all 0.2s ease;
        }

        .logout-btn:hover {
            background: #4b5563;
            transform: translateY(-1px);
        }

        /* Breadcrumb Styles */
        .breadcrumb-container {
            background: #ffffff;
            padding: 1rem 2rem;
            border-bottom: 1px solid #e5e7eb;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        }

        .breadcrumb {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.875rem;
        }

        .breadcrumb-item {
            display: flex;
            align-items: center;
            color: #6b7280;
            text-decoration: none;
            padding: 0.25rem 0.5rem;
            border-radius: 0.25rem;
            transition: all 0.2s;
        }

        .breadcrumb-item:hover {
            color: #3b82f6;
            background-color: #f3f4f6;
        }

        .breadcrumb-item.active {
            color: #1f2937;
            font-weight: 500;
        }

        .breadcrumb-separator {
            color: #9ca3af;
            margin: 0 0.25rem;
        }

        .content {
            padding: 2rem;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .stat-card {
            background: white;
            padding: 1.5rem;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .stat-card h3 {
            font-size: 2rem;
            color: #667eea;
            margin-bottom: 0.5rem;
        }

        .stat-card p {
            color: #666;
            font-size: 0.9rem;
        }

        .card {
            background: white;
            border-radius: 8px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            margin-bottom: 1.5rem;
            border: 1px solid #e5e7eb;
            overflow: hidden;
        }

        .card-header {
            padding: 1.5rem;
            border-bottom: 1px solid #e5e7eb;
            background: #f9fafb;
        }

        .card-header h3 {
            color: #1f2937;
            font-size: 1.25rem;
            font-weight: 600;
            margin: 0;
        }

        .card-body {
            padding: 1.5rem;
        }

        .btn {
            display: inline-block;
            padding: 0.625rem 1.25rem;
            background: #3b82f6;
            color: white;
            text-decoration: none;
            border-radius: 6px;
            border: none;
            cursor: pointer;
            font-size: 0.875rem;
            font-weight: 500;
            transition: all 0.2s ease;
        }

        .btn:hover {
            background: #2563eb;
            transform: translateY(-1px);
        }

        .btn-success {
            background: #10b981;
        }

        .btn-success:hover {
            background: #059669;
        }

        .btn-danger {
            background: #ef4444;
        }

        .btn-danger:hover {
            background: #dc2626;
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
            border-bottom: 1px solid #eee;
        }

        .table th {
            background-color: #f8f9fa;
            font-weight: 600;
            color: #333;
        }

        .alert {
            padding: 1rem;
            border-radius: 5px;
            margin-bottom: 1rem;
        }

        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
                transition: transform 0.3s ease;
                width: 280px;
            }

            .sidebar.open {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0;
            }

            .header {
                padding: 1rem;
            }

            .header h1 {
                font-size: 1.5rem;
            }

            .breadcrumb-container {
                padding: 0.75rem 1rem;
            }

            .breadcrumb {
                font-size: 0.8rem;
            }

            .content {
                padding: 1rem;
            }

            .stats-grid {
                grid-template-columns: 1fr;
            }

            .card-header {
                padding: 1.5rem;
            }

            .card-body {
                padding: 1.5rem;
            }
        }

        /* Mobile menu toggle button */
        .mobile-menu-toggle {
            display: none;
            background: none;
            border: none;
            font-size: 1.5rem;
            color: #6b7280;
            cursor: pointer;
            padding: 0.5rem;
            border-radius: 8px;
            transition: all 0.2s ease;
        }

        .mobile-menu-toggle:hover {
            background-color: #f3f4f6;
            color: #1f2937;
        }

        @media (max-width: 768px) {
            .mobile-menu-toggle {
                display: block;
            }
        }

        /* Form Controls */
        .form-control {
            display: block;
            width: 100%;
            padding: 0.75rem 1rem;
            font-size: 0.875rem;
            font-weight: 400;
            line-height: 1.5;
            color: #374151;
            background-color: #ffffff;
            background-clip: padding-box;
            border: 1px solid #d1d5db;
            border-radius: 0.375rem;
            transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
        }

        .form-control:focus {
            color: #374151;
            background-color: #ffffff;
            border-color: #3b82f6;
            outline: 0;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        .form-control::placeholder {
            color: #9ca3af;
            opacity: 1;
        }

        .form-control:disabled,
        .form-control[readonly] {
            background-color: #f9fafb;
            opacity: 1;
        }

        .form-control.is-invalid {
            border-color: #ef4444;
        }

        .form-control.is-invalid:focus {
            border-color: #ef4444;
            box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.1);
        }

        .form-control.is-valid {
            border-color: #10b981;
        }

        .form-control.is-valid:focus {
            border-color: #10b981;
            box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1);
        }

        /* Button Styles */
        .btn-secondary {
            background: #6b7280;
            color: white;
            border: 1px solid #6b7280;
        }

        .btn-secondary:hover {
            background: #4b5563;
            border-color: #4b5563;
            color: white;
        }

        .btn-primary {
            background: #3b82f6;
            color: white;
            border: 1px solid #3b82f6;
        }

        .btn-primary:hover {
            background: #2563eb;
            border-color: #2563eb;
            color: white;
        }

        /* Form Validation */
        .invalid-feedback {
            display: block;
            width: 100%;
            margin-top: 0.25rem;
            font-size: 0.875rem;
            color: #ef4444;
        }

        .valid-feedback {
            display: block;
            width: 100%;
            margin-top: 0.25rem;
            font-size: 0.875rem;
            color: #10b981;
        }

        /* Form Groups */
        .form-group {
            margin-bottom: 1rem;
        }

        .form-label {
            display: inline-block;
            margin-bottom: 0.5rem;
            font-weight: 500;
            color: #374151;
        }

        /* Select Styles */
        select.form-control {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='m6 8 4 4 4-4'/%3e%3c/svg%3e");
            background-position: right 0.5rem center;
            background-repeat: no-repeat;
            background-size: 1.5em 1.5em;
            padding-right: 2.5rem;
        }

        /* Textarea Styles */
        textarea.form-control {
            resize: vertical;
            min-height: 80px;
        }

        /* Input Number Styles */
        input[type="number"].form-control {
            -moz-appearance: textfield;
        }

        input[type="number"].form-control::-webkit-outer-spin-button,
        input[type="number"].form-control::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        /* Focus States */
        .form-control:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        /* Responsive Form Controls */
        @media (max-width: 768px) {
            .form-control {
                font-size: 16px; /* Prevents zoom on iOS */
            }
        }

        /* Common Pagination Styles for All Admin Pages */
        .pagination-container {
            margin-top: 1.5rem;
            padding-top: 1rem;
            border-top: 1px solid #e5e7eb;
            background: white;
            border-radius: 0 0 8px 8px;
        }

        /* Override Tailwind CSS classes for pagination with !important */
        .pagination-container nav {
            display: flex !important;
            justify-content: space-between !important;
            align-items: center !important;
            margin-top: 0 !important;
            padding: 1rem 0 !important;
            border-top: none !important;
            background: white !important;
        }

        .pagination-container .flex.justify-between.flex-1.sm\\:hidden {
            display: none !important;
        }

        .pagination-container .hidden.sm\\:flex-1.sm\\:flex.sm\\:items-center.sm\\:justify-between {
            display: flex !important;
            flex: 1 !important;
            align-items: center !important;
            justify-content: space-between !important;
            width: 100% !important;
        }

        .pagination-container .text-sm.text-gray-700.leading-5 {
            color: #6b7280 !important;
            font-size: 0.875rem !important;
            font-weight: 500 !important;
        }

        .pagination-container .relative.z-0.inline-flex {
            display: flex !important;
            list-style: none !important;
            margin: 0 !important;
            padding: 0 !important;
            gap: 0.25rem !important;
            box-shadow: none !important;
            border-radius: 0.375rem !important;
        }

        .pagination-container .relative.inline-flex.items-center {
            display: flex !important;
            align-items: center !important;
            position: relative !important;
        }

        /* Override all pagination button styles */
        .pagination-container .relative.inline-flex.items-center.px-4.py-2,
        .pagination-container .relative.inline-flex.items-center.px-2.py-2 {
            padding: 0.5rem 0.75rem !important;
            color: #374151 !important;
            text-decoration: none !important;
            border: 1px solid #d1d5db !important;
            border-radius: 0.375rem !important;
            font-size: 0.875rem !important;
            font-weight: 500 !important;
            transition: all 0.2s ease !important;
            background: white !important;
            min-width: 2.5rem !important;
            justify-content: center !important;
            margin-left: -1px !important;
        }

        .pagination-container .relative.inline-flex.items-center.px-2.py-2 {
            padding: 0.5rem 0.5rem !important;
        }

        /* Hover effects */
        .pagination-container .relative.inline-flex.items-center.px-4.py-2:hover,
        .pagination-container .relative.inline-flex.items-center.px-2.py-2:hover {
            background-color: #f3f4f6 !important;
            border-color: #9ca3af !important;
            color: #1f2937 !important;
            transform: translateY(-1px) !important;
        }

        /* Active page styling - override the current page */
        .pagination-container .relative.inline-flex.items-center.px-4.py-2.-ml-px.text-sm.font-medium.text-gray-500.bg-white.border.border-gray-300.cursor-default {
            background-color: #3b82f6 !important;
            border-color: #3b82f6 !important;
            color: white !important;
            font-weight: 600 !important;
        }

        /* Disabled styling for previous/next buttons */
        .pagination-container .relative.inline-flex.items-center.px-2.py-2.text-sm.font-medium.text-gray-500.bg-white.border.border-gray-300.cursor-default {
            color: #9ca3af !important;
            background-color: #f9fafb !important;
            border-color: #e5e7eb !important;
            cursor: not-allowed !important;
            opacity: 0.6 !important;
        }

        .pagination-container .relative.inline-flex.items-center.px-2.py-2.text-sm.font-medium.text-gray-500.bg-white.border.border-gray-300.cursor-default:hover {
            background-color: #f9fafb !important;
            border-color: #e5e7eb !important;
            color: #9ca3af !important;
            transform: none !important;
        }

        /* Rounded corners */
        .pagination-container .rounded-l-md {
            border-top-left-radius: 0.375rem !important;
            border-bottom-left-radius: 0.375rem !important;
        }

        .pagination-container .rounded-r-md {
            border-top-right-radius: 0.375rem !important;
            border-bottom-right-radius: 0.375rem !important;
        }

        .pagination-container svg {
            flex-shrink: 0 !important;
            width: 16px !important;
            height: 16px !important;
        }

        /* Responsive design for pagination */
        @media (max-width: 768px) {
            .pagination-container nav {
                flex-direction: column !important;
                gap: 1rem !important;
                align-items: stretch !important;
            }

            .pagination-container .hidden.sm\\:flex-1.sm\\:flex.sm\\:items-center.sm\\:justify-between {
                flex-direction: column !important;
                gap: 1rem !important;
                align-items: stretch !important;
            }

            .pagination-container .relative.z-0.inline-flex {
                justify-content: center !important;
                flex-wrap: wrap !important;
            }

            .pagination-container .relative.inline-flex.items-center.px-4.py-2,
            .pagination-container .relative.inline-flex.items-center.px-2.py-2 {
                padding: 0.375rem 0.5rem !important;
                font-size: 0.8rem !important;
                min-width: 2rem !important;
            }

            .pagination-container svg {
                width: 14px !important;
                height: 14px !important;
            }
        }

        @media (max-width: 480px) {
            .pagination-container .relative.z-0.inline-flex {
                gap: 0.125rem !important;
            }

            .pagination-container .relative.inline-flex.items-center.px-4.py-2,
            .pagination-container .relative.inline-flex.items-center.px-2.py-2 {
                padding: 0.25rem 0.375rem !important;
                font-size: 0.75rem !important;
                min-width: 1.75rem !important;
            }

            .pagination-container .text-sm.text-gray-700.leading-5 {
                font-size: 0.8rem !important;
                text-align: center !important;
            }
        }

        /* Select2 Custom Styling */
        .select2-container {
            width: 100% !important;
        }

        .select2-container--default .select2-selection--single {
            height: 42px !important;
            border: 1px solid #d1d5db !important;
            border-radius: 0.375rem !important;
            background-color: #ffffff !important;
            padding: 0.75rem 1rem !important;
            font-size: 0.875rem !important;
            font-weight: 400 !important;
            line-height: 1.5 !important;
            color: #374151 !important;
        }

        .select2-container--default .select2-selection--single:focus {
            border-color: #3b82f6 !important;
            outline: 0 !important;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1) !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            color: #374151 !important;
            line-height: 28px !important;
            padding-left: 0 !important;
            padding-right: 20px !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__placeholder {
            color: #9ca3af !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 40px !important;
            right: 8px !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow b {
            border-color: #6b7280 transparent transparent transparent !important;
            border-style: solid !important;
            border-width: 5px 4px 0 4px !important;
            height: 0 !important;
            left: 50% !important;
            margin-left: -4px !important;
            margin-top: -2px !important;
            position: absolute !important;
            top: 50% !important;
            width: 0 !important;
        }

        .select2-dropdown {
            border: 1px solid #d1d5db !important;
            border-radius: 0.375rem !important;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06) !important;
        }

        .select2-container--default .select2-search--dropdown .select2-search__field {
            border: 1px solid #d1d5db !important;
            border-radius: 0.375rem !important;
            padding: 0.5rem 0.75rem !important;
            font-size: 0.875rem !important;
        }

        .select2-container--default .select2-search--dropdown .select2-search__field:focus {
            border-color: #3b82f6 !important;
            outline: 0 !important;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1) !important;
        }

        .select2-container--default .select2-results__option {
            padding: 0.5rem 0.75rem !important;
            font-size: 0.875rem !important;
            color: #374151 !important;
        }

        .select2-container--default .select2-results__option--highlighted[aria-selected] {
            background-color: #3b82f6 !important;
            color: white !important;
        }

        .select2-container--default .select2-results__option[aria-selected=true] {
            background-color: #f3f4f6 !important;
            color: #1f2937 !important;
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <div class="sidebar-header">
            <h2>Mindpark Admin</h2>
            <p>Admin Panel</p>
        </div>
        <ul class="sidebar-menu">
            <!-- Dashboard -->
            <li class="menu-item">
                <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 12px;">
                        <rect x="3" y="3" width="7" height="7"></rect>
                        <rect x="14" y="3" width="7" height="7"></rect>
                        <rect x="14" y="14" width="7" height="7"></rect>
                        <rect x="3" y="14" width="7" height="7"></rect>
                    </svg>
                    Dashboard
                </a>
            </li>

            <!-- System Management Group -->
            @if(auth()->user()->can('view users') || auth()->user()->can('view roles') || auth()->user()->can('view reporters') || auth()->user()->can('view officers') || auth()->user()->can('view settings'))
            <li class="menu-group">
                <div class="menu-group-header">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 8px;">
                        <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                        <circle cx="9" cy="9" r="2"></circle>
                        <path d="M21 15.5V19a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-3.5"></path>
                    </svg>
                    System Management
                </div>
                <ul class="submenu">
                    @can('view users')
                    <li>
                        <a href="{{ route('admin.users.index') }}" class="{{ request()->routeIs('admin.users*') ? 'active' : '' }}">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 8px;">
                                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                <circle cx="12" cy="7" r="4"></circle>
                            </svg>
                            User Management
                        </a>
                    </li>
                    @endcan
                    @can('view roles')
                    <li>
                        <a href="{{ route('admin.roles.index') }}" class="{{ request()->routeIs('admin.roles*') ? 'active' : '' }}">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 8px;">
                                <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path>
                                <circle cx="9" cy="7" r="4"></circle>
                                <path d="M22 21v-2a4 4 0 0 0-3-3.87"></path>
                                <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                            </svg>
                            Role Management
                        </a>
                    </li>
                    @endcan
                    @can('view reporters')
                    <li>
                        <a href="{{ route('admin.reporters.index') }}" class="{{ request()->routeIs('admin.reporters*') ? 'active' : '' }}">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 8px;">
                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                <polyline points="14,2 14,8 20,8"></polyline>
                                <line x1="16" y1="13" x2="8" y2="13"></line>
                                <line x1="16" y1="17" x2="8" y2="17"></line>
                                <polyline points="10,9 9,9 8,9"></polyline>
                            </svg>
                            Reporter Management
                        </a>
                    </li>
                    @endcan
                    @can('view officers')
                    <li>
                        <a href="{{ route('admin.officers.index') }}" class="{{ request()->routeIs('admin.officers*') ? 'active' : '' }}">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 8px;">
                                <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path>
                                <circle cx="9" cy="7" r="4"></circle>
                                <path d="M22 21v-2a4 4 0 0 0-3-3.87"></path>
                                <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                            </svg>
                            Officer Management
                        </a>
                    </li>
                    @endcan
                    @can('view settings')
                    <li>
                        <a href="{{ route('admin.settings.index') }}" class="{{ request()->routeIs('admin.settings*') ? 'active' : '' }}">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 8px;">
                                <circle cx="12" cy="12" r="3"></circle>
                                <path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1 1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"></path>
                            </svg>
                            Settings
                        </a>
                    </li>
                    @endcan
                </ul>
            </li>
            @endif

            <!-- Business Management Group -->
            @if(auth()->user()->can('view clients') || auth()->user()->can('view jobs'))
            <li class="menu-group">
                <div class="menu-group-header">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 8px;">
                        <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                        <polyline points="9,22 9,12 15,12 15,22"></polyline>
                    </svg>
                    Business Management
                </div>
                <ul class="submenu">
                    @can('view clients')
                    <li>
                        <a href="{{ route('admin.clients.index') }}" class="{{ request()->routeIs('admin.clients*') ? 'active' : '' }}">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 8px;">
                                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                <circle cx="9" cy="7" r="4"></circle>
                                <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                                <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                            </svg>
                            Client Management
                        </a>
                    </li>
                    @endcan
                    @can('view jobs')
                    <li>
                        <a href="{{ route('admin.jobs.index') }}" class="{{ request()->routeIs('admin.jobs*') ? 'active' : '' }}">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 8px;">
                                <rect x="2" y="3" width="20" height="14" rx="2" ry="2"></rect>
                                <line x1="8" y1="21" x2="16" y2="21"></line>
                                <line x1="12" y1="17" x2="12" y2="21"></line>
                            </svg>
                            Job ID Management
                        </a>
                    </li>
                    @endcan
                </ul>
            </li>
            @endif

            <!-- Promoter Management Group -->
            @if(auth()->user()->can('view promoters') || auth()->user()->can('view promoter positions') || auth()->user()->can('view coordinators'))
            <li class="menu-group">
                <div class="menu-group-header">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 8px;">
                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                        <circle cx="12" cy="7" r="4"></circle>
                        <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                    </svg>
                    Promoter Management
                </div>
                <ul class="submenu">
                    @can('view promoters')
                    <li>
                        <a href="{{ route('admin.promoters.index') }}" class="{{ request()->routeIs('admin.promoters*') ? 'active' : '' }}">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 8px;">
                                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                <circle cx="12" cy="7" r="4"></circle>
                                <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                            </svg>
                            Promoter Details
                        </a>
                    </li>
                    @endcan
                    @can('view promoter positions')
                    <li>
                        <a href="{{ route('admin.promoter-positions.index') }}" class="{{ request()->routeIs('admin.promoter-positions*') ? 'active' : '' }}">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 8px;">
                                <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                                <circle cx="9" cy="9" r="2"></circle>
                                <path d="M21 15.5V19a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-3.5"></path>
                            </svg>
                            Promoter Positions
                        </a>
                    </li>
                    @endcan
                    @can('view coordinators')
                    <li>
                        <a href="{{ route('admin.coordinators.index') }}" class="{{ request()->routeIs('admin.coordinators*') ? 'active' : '' }}">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 8px;">
                                <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path>
                                <circle cx="9" cy="7" r="4"></circle>
                                <path d="M22 21v-2a4 4 0 0 0-3-3.87"></path>
                                <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                            </svg>
                            Coordinator Details
                        </a>
                    </li>
                    @endcan
                </ul>
            </li>
            @endif

            <!-- HR Management Group -->
            @if(auth()->user()->can('view salary sheets') || auth()->user()->can('view salary rules') || auth()->user()->can('view allowances'))
            <li class="menu-group">
                <div class="menu-group-header">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 8px;">
                        <rect x="2" y="3" width="20" height="14" rx="2" ry="2"></rect>
                        <line x1="8" y1="21" x2="16" y2="21"></line>
                        <line x1="12" y1="17" x2="12" y2="21"></line>
                    </svg>
                    HR Management
                </div>
                <ul class="submenu">
                    @can('view salary sheets')
                    <li>
                        <a href="{{ route('admin.salary-sheets.index') }}" class="{{ request()->routeIs('admin.salary-sheets*') ? 'active' : '' }}">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 8px;">
                                <rect x="2" y="3" width="20" height="14" rx="2" ry="2"></rect>
                                <line x1="8" y1="21" x2="16" y2="21"></line>
                                <line x1="12" y1="17" x2="12" y2="21"></line>
                            </svg>
                            Salary Sheets
                        </a>
                    </li>
                    @endcan
                    @can('view salary rules')
                    <li>
                        <a href="{{ route('admin.position-wise-salary-rules.index') }}" class="{{ request()->routeIs('admin.position-wise-salary-rules*') ? 'active' : '' }}">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 8px;">
                                <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                                <line x1="9" y1="9" x2="15" y2="15"></line>
                                <line x1="15" y1="9" x2="9" y2="15"></line>
                            </svg>
                            Salary Rules
                        </a>
                    </li>
                    @endcan
                    @can('view allowances')
                    <li>
                        <a href="{{ route('admin.allowances.index') }}" class="{{ request()->routeIs('admin.allowances*') ? 'active' : '' }}">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 8px;">
                                <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"></path>
                            </svg>
                            Allowances
                        </a>
                    </li>
                    @endcan
                </ul>
            </li>
            @endif
        </ul>
    </div>

    <div class="main-content">
        <div class="header">
            <div style="display: flex; align-items: center; gap: 1rem;">
                <button class="mobile-menu-toggle" onclick="toggleSidebar()">☰</button>
                <h1>@yield('page-title', 'Dashboard')</h1>
            </div>
            <div class="user-menu">
                <span>Welcome, {{ Auth::user()->name }}</span>
                <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                    @csrf
                    <button type="submit" class="logout-btn">Logout</button>
                </form>
            </div>
        </div>

        <!-- Breadcrumb Navigation -->
        <div class="breadcrumb-container">
            <nav class="breadcrumb">
                <a href="{{ route('admin.dashboard') }}" class="breadcrumb-item">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 6px;">
                        <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                        <polyline points="9,22 9,12 15,12 15,22"></polyline>
                    </svg>
                    Dashboard
                </a>
                @yield('breadcrumbs')
            </nav>
        </div>

        <div class="content">
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif

            @yield('content')
        </div>
    </div>

    <script>
        function toggleSidebar() {
            const sidebar = document.querySelector('.sidebar');
            sidebar.classList.toggle('open');
        }

        // Close sidebar when clicking outside on mobile
        document.addEventListener('click', function(event) {
            const sidebar = document.querySelector('.sidebar');
            const toggleButton = document.querySelector('.mobile-menu-toggle');
            
            if (window.innerWidth <= 768 && 
                !sidebar.contains(event.target) && 
                !toggleButton.contains(event.target)) {
                sidebar.classList.remove('open');
            }
        });

        // Handle window resize
        window.addEventListener('resize', function() {
            const sidebar = document.querySelector('.sidebar');
            if (window.innerWidth > 768) {
                sidebar.classList.remove('open');
            }
        });
    </script>
</body>
</html>
