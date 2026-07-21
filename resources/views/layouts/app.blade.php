<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="description" content="Cloud File Management System - Manage your files securely">

        <title>{{ config('app.name', 'CloudDrive') }} - @yield('title', 'Dashboard')</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:300,400,500,600,700,800&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            :root {
                --bg-primary: #0f172a;
                --bg-secondary: #1e293b;
                --bg-card: #1e293b;
                --bg-card-hover: #334155;
                --text-primary: #f1f5f9;
                --text-secondary: #94a3b8;
                --text-muted: #64748b;
                --accent-blue: #3b82f6;
                --accent-blue-hover: #2563eb;
                --accent-emerald: #10b981;
                --accent-violet: #8b5cf6;
                --accent-amber: #f59e0b;
                --accent-rose: #f43f5e;
                --border-color: #334155;
                --glass-bg: rgba(30, 41, 59, 0.8);
                --glass-border: rgba(148, 163, 184, 0.1);
            }

            * {
                font-family: 'Inter', sans-serif;
            }

            body {
                background: var(--bg-primary);
                color: var(--text-primary);
            }

            /* Sidebar styles */
            .sidebar {
                background: var(--bg-secondary);
                border-right: 1px solid var(--border-color);
                width: 260px;
                min-height: 100vh;
                position: fixed;
                top: 0;
                left: 0;
                z-index: 40;
                transition: transform 0.3s ease;
            }

            .sidebar-logo {
                padding: 1.5rem;
                border-bottom: 1px solid var(--border-color);
            }

            .sidebar-nav {
                padding: 1rem 0.75rem;
            }

            .sidebar-link {
                display: flex;
                align-items: center;
                gap: 0.75rem;
                padding: 0.75rem 1rem;
                border-radius: 0.5rem;
                color: var(--text-secondary);
                text-decoration: none;
                font-size: 0.875rem;
                font-weight: 500;
                transition: all 0.2s ease;
                margin-bottom: 0.25rem;
            }

            .sidebar-link:hover {
                background: var(--bg-card-hover);
                color: var(--text-primary);
            }

            .sidebar-link.active {
                background: linear-gradient(135deg, var(--accent-blue), var(--accent-violet));
                color: white;
                box-shadow: 0 4px 15px rgba(59, 130, 246, 0.3);
            }

            .sidebar-link svg {
                width: 20px;
                height: 20px;
                flex-shrink: 0;
            }

            /* Main content */
            .main-content {
                margin-left: 260px;
                min-height: 100vh;
            }

            /* Top bar */
            .topbar {
                background: var(--glass-bg);
                backdrop-filter: blur(20px);
                border-bottom: 1px solid var(--glass-border);
                padding: 1rem 2rem;
                display: flex;
                justify-content: space-between;
                align-items: center;
                position: sticky;
                top: 0;
                z-index: 30;
            }

            .topbar-title {
                font-size: 1.25rem;
                font-weight: 700;
                color: var(--text-primary);
            }

            .topbar-user {
                display: flex;
                align-items: center;
                gap: 0.75rem;
            }

            .user-avatar {
                width: 36px;
                height: 36px;
                border-radius: 50%;
                background: linear-gradient(135deg, var(--accent-blue), var(--accent-violet));
                display: flex;
                align-items: center;
                justify-content: center;
                font-weight: 700;
                font-size: 0.875rem;
                color: white;
            }

            .user-dropdown {
                position: relative;
            }

            .user-dropdown-menu {
                position: absolute;
                right: 0;
                top: 100%;
                margin-top: 0.5rem;
                background: var(--bg-secondary);
                border: 1px solid var(--border-color);
                border-radius: 0.75rem;
                padding: 0.5rem;
                min-width: 200px;
                box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
                display: none;
                z-index: 50;
            }

            .user-dropdown-menu.show {
                display: block;
                animation: fadeInDown 0.2s ease;
            }

            .user-dropdown-menu a,
            .user-dropdown-menu button {
                display: block;
                width: 100%;
                padding: 0.625rem 1rem;
                border-radius: 0.5rem;
                color: var(--text-secondary);
                text-decoration: none;
                font-size: 0.875rem;
                text-align: left;
                border: none;
                background: none;
                cursor: pointer;
                transition: all 0.15s ease;
            }

            .user-dropdown-menu a:hover,
            .user-dropdown-menu button:hover {
                background: var(--bg-card-hover);
                color: var(--text-primary);
            }

            /* Cards */
            .card {
                background: var(--bg-card);
                border: 1px solid var(--border-color);
                border-radius: 1rem;
                padding: 1.5rem;
                transition: all 0.3s ease;
            }

            .card:hover {
                border-color: var(--accent-blue);
                box-shadow: 0 0 30px rgba(59, 130, 246, 0.1);
            }

            .card-glow-blue {
                border-color: rgba(59, 130, 246, 0.3);
            }

            .card-glow-emerald {
                border-color: rgba(16, 185, 129, 0.3);
            }

            .card-glow-violet {
                border-color: rgba(139, 92, 246, 0.3);
            }

            .card-glow-amber {
                border-color: rgba(245, 158, 11, 0.3);
            }

            /* Stat cards */
            .stat-icon {
                width: 48px;
                height: 48px;
                border-radius: 0.75rem;
                display: flex;
                align-items: center;
                justify-content: center;
            }

            .stat-icon svg {
                width: 24px;
                height: 24px;
            }

            .stat-icon-blue {
                background: rgba(59, 130, 246, 0.15);
                color: var(--accent-blue);
            }

            .stat-icon-emerald {
                background: rgba(16, 185, 129, 0.15);
                color: var(--accent-emerald);
            }

            .stat-icon-violet {
                background: rgba(139, 92, 246, 0.15);
                color: var(--accent-violet);
            }

            .stat-icon-amber {
                background: rgba(245, 158, 11, 0.15);
                color: var(--accent-amber);
            }

            .stat-value {
                font-size: 1.875rem;
                font-weight: 800;
                letter-spacing: -0.025em;
                margin-top: 0.75rem;
            }

            .stat-label {
                font-size: 0.875rem;
                color: var(--text-secondary);
                margin-top: 0.25rem;
            }

            /* Progress bar */
            .progress-bar-bg {
                background: rgba(100, 116, 139, 0.2);
                border-radius: 999px;
                height: 12px;
                overflow: hidden;
            }

            .progress-bar-fill {
                height: 100%;
                border-radius: 999px;
                transition: width 1s ease;
                background: linear-gradient(90deg, var(--accent-blue), var(--accent-violet));
            }

            .progress-bar-fill.warning {
                background: linear-gradient(90deg, var(--accent-amber), var(--accent-rose));
            }

            /* Table */
            .data-table {
                width: 100%;
                border-collapse: separate;
                border-spacing: 0;
            }

            .data-table th {
                padding: 0.875rem 1rem;
                text-align: left;
                font-size: 0.75rem;
                font-weight: 600;
                text-transform: uppercase;
                letter-spacing: 0.05em;
                color: var(--text-muted);
                border-bottom: 1px solid var(--border-color);
            }

            .data-table td {
                padding: 0.875rem 1rem;
                font-size: 0.875rem;
                border-bottom: 1px solid rgba(51, 65, 85, 0.5);
                color: var(--text-secondary);
            }

            .data-table tr:hover td {
                background: rgba(51, 65, 85, 0.3);
            }

            .data-table tr:last-child td {
                border-bottom: none;
            }

            /* Buttons */
            .btn {
                display: inline-flex;
                align-items: center;
                gap: 0.5rem;
                padding: 0.625rem 1.25rem;
                border-radius: 0.5rem;
                font-size: 0.875rem;
                font-weight: 600;
                border: none;
                cursor: pointer;
                transition: all 0.2s ease;
                text-decoration: none;
            }

            .btn svg {
                width: 18px;
                height: 18px;
            }

            .btn-primary {
                background: linear-gradient(135deg, var(--accent-blue), var(--accent-violet));
                color: white;
                box-shadow: 0 4px 15px rgba(59, 130, 246, 0.3);
            }

            .btn-primary:hover {
                transform: translateY(-1px);
                box-shadow: 0 8px 25px rgba(59, 130, 246, 0.4);
            }

            .btn-danger {
                background: rgba(244, 63, 94, 0.15);
                color: var(--accent-rose);
                border: 1px solid rgba(244, 63, 94, 0.3);
            }

            .btn-danger:hover {
                background: var(--accent-rose);
                color: white;
            }

            .btn-ghost {
                background: transparent;
                color: var(--text-secondary);
                border: 1px solid var(--border-color);
            }

            .btn-ghost:hover {
                background: var(--bg-card-hover);
                color: var(--text-primary);
            }

            .btn-sm {
                padding: 0.375rem 0.75rem;
                font-size: 0.8125rem;
            }

            /* Badge */
            .badge {
                display: inline-flex;
                align-items: center;
                padding: 0.25rem 0.625rem;
                border-radius: 999px;
                font-size: 0.75rem;
                font-weight: 600;
            }

            .badge-admin {
                background: rgba(139, 92, 246, 0.15);
                color: var(--accent-violet);
                border: 1px solid rgba(139, 92, 246, 0.3);
            }

            .badge-user {
                background: rgba(59, 130, 246, 0.15);
                color: var(--accent-blue);
                border: 1px solid rgba(59, 130, 246, 0.3);
            }

            /* Alerts */
            .alert {
                padding: 1rem 1.25rem;
                border-radius: 0.75rem;
                margin-bottom: 1.5rem;
                display: flex;
                align-items: center;
                gap: 0.75rem;
                font-size: 0.875rem;
                animation: fadeInDown 0.3s ease;
            }

            .alert-success {
                background: rgba(16, 185, 129, 0.1);
                border: 1px solid rgba(16, 185, 129, 0.3);
                color: var(--accent-emerald);
            }

            .alert-error {
                background: rgba(244, 63, 94, 0.1);
                border: 1px solid rgba(244, 63, 94, 0.3);
                color: var(--accent-rose);
            }

            /* File type icons */
            .file-icon {
                width: 40px;
                height: 40px;
                border-radius: 0.5rem;
                display: flex;
                align-items: center;
                justify-content: center;
                font-weight: 700;
                font-size: 0.625rem;
                text-transform: uppercase;
            }

            .file-icon-image { background: rgba(245, 158, 11, 0.15); color: var(--accent-amber); }
            .file-icon-pdf { background: rgba(244, 63, 94, 0.15); color: var(--accent-rose); }
            .file-icon-doc { background: rgba(59, 130, 246, 0.15); color: var(--accent-blue); }
            .file-icon-default { background: rgba(100, 116, 139, 0.15); color: var(--text-muted); }

            /* Pagination */
            .pagination-wrapper nav > div:first-child {
                display: none;
            }

            .pagination-wrapper span[aria-current="page"] span {
                background: linear-gradient(135deg, var(--accent-blue), var(--accent-violet)) !important;
                border-color: transparent !important;
                color: white !important;
            }

            .pagination-wrapper a {
                background: var(--bg-card) !important;
                border-color: var(--border-color) !important;
                color: var(--text-secondary) !important;
            }

            .pagination-wrapper a:hover {
                background: var(--bg-card-hover) !important;
                color: var(--text-primary) !important;
            }

            .pagination-wrapper span.cursor-default {
                background: var(--bg-card) !important;
                border-color: var(--border-color) !important;
                color: var(--text-muted) !important;
            }

            /* Upload zone */
            .upload-zone {
                border: 2px dashed var(--border-color);
                border-radius: 1rem;
                padding: 3rem 2rem;
                text-align: center;
                transition: all 0.3s ease;
                cursor: pointer;
            }

            .upload-zone:hover,
            .upload-zone.dragover {
                border-color: var(--accent-blue);
                background: rgba(59, 130, 246, 0.05);
            }

            .upload-zone.dragover {
                transform: scale(1.02);
                box-shadow: 0 0 40px rgba(59, 130, 246, 0.15);
            }

            /* Form inputs */
            .form-input {
                width: 100%;
                padding: 0.625rem 1rem;
                background: var(--bg-primary);
                border: 1px solid var(--border-color);
                border-radius: 0.5rem;
                color: var(--text-primary);
                font-size: 0.875rem;
                transition: border-color 0.2s ease;
            }

            .form-input:focus {
                outline: none;
                border-color: var(--accent-blue);
                box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
            }

            .form-select {
                padding: 0.5rem 0.75rem;
                background: var(--bg-primary);
                border: 1px solid var(--border-color);
                border-radius: 0.5rem;
                color: var(--text-primary);
                font-size: 0.8125rem;
                cursor: pointer;
            }

            .form-select:focus {
                outline: none;
                border-color: var(--accent-blue);
            }

            /* Animations */
            @keyframes fadeInDown {
                from {
                    opacity: 0;
                    transform: translateY(-10px);
                }
                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }

            @keyframes fadeIn {
                from { opacity: 0; }
                to { opacity: 1; }
            }

            .fade-in {
                animation: fadeIn 0.5s ease;
            }

            /* Mobile sidebar */
            .sidebar-overlay {
                display: none;
                position: fixed;
                inset: 0;
                background: rgba(0, 0, 0, 0.5);
                z-index: 35;
            }

            .mobile-menu-btn {
                display: none;
                padding: 0.5rem;
                background: transparent;
                border: none;
                color: var(--text-primary);
                cursor: pointer;
            }

            @media (max-width: 768px) {
                .sidebar {
                    transform: translateX(-100%);
                }
                .sidebar.open {
                    transform: translateX(0);
                }
                .sidebar-overlay.show {
                    display: block;
                }
                .main-content {
                    margin-left: 0;
                }
                .mobile-menu-btn {
                    display: block;
                }
            }

            /* Modal */
            .modal-overlay {
                position: fixed;
                inset: 0;
                background: rgba(0, 0, 0, 0.6);
                backdrop-filter: blur(4px);
                display: flex;
                align-items: center;
                justify-content: center;
                z-index: 100;
                padding: 1rem;
            }

            .modal-content {
                background: var(--bg-secondary);
                border: 1px solid var(--border-color);
                border-radius: 1rem;
                padding: 2rem;
                max-width: 500px;
                width: 100%;
                box-shadow: 0 25px 50px rgba(0, 0, 0, 0.5);
                animation: fadeInDown 0.3s ease;
            }

            /* Scrollbar */
            ::-webkit-scrollbar {
                width: 6px;
            }
            ::-webkit-scrollbar-track {
                background: var(--bg-primary);
            }
            ::-webkit-scrollbar-thumb {
                background: var(--border-color);
                border-radius: 3px;
            }
            ::-webkit-scrollbar-thumb:hover {
                background: var(--text-muted);
            }
        </style>
    </head>
    <body>
        <!-- Sidebar Overlay for Mobile -->
        <div class="sidebar-overlay" id="sidebarOverlay" onclick="toggleSidebar()"></div>

        <!-- Sidebar -->
        <aside class="sidebar" id="sidebar">
            <div class="sidebar-logo">
                <div style="display: flex; align-items: center; gap: 0.75rem;">
                    <div style="width: 40px; height: 40px; border-radius: 0.75rem; background: linear-gradient(135deg, var(--accent-blue), var(--accent-violet)); display: flex; align-items: center; justify-content: center;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M17.5 19H9a7 7 0 1 1 6.71-9h1.79a4.5 4.5 0 1 1 0 9Z"/>
                        </svg>
                    </div>
                    <div>
                        <div style="font-weight: 700; font-size: 1.125rem; color: var(--text-primary);">CloudDrive</div>
                        <div style="font-size: 0.6875rem; color: var(--text-muted);">File Management</div>
                    </div>
                </div>
            </div>
            <nav class="sidebar-nav">
                <a href="{{ route('dashboard') }}" class="sidebar-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" id="nav-dashboard">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/>
                    </svg>
                    Dashboard
                </a>
                <a href="{{ route('files.index') }}" class="sidebar-link {{ request()->routeIs('files.*') ? 'active' : '' }}" id="nav-files">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8Z"/><polyline points="14 2 14 8 20 8"/>
                    </svg>
                    File Manager
                </a>
                @if(Auth::user()->hasRole('admin'))
                <a href="{{ route('users.index') }}" class="sidebar-link {{ request()->routeIs('users.*') ? 'active' : '' }}" id="nav-users">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                    </svg>
                    Kelola Pengguna
                </a>
                @endif

                <div style="margin-top: 1.5rem; padding-top: 1rem; border-top: 1px solid var(--border-color);">
                    <div style="padding: 0 1rem; margin-bottom: 0.5rem;">
                        <span style="font-size: 0.6875rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; color: var(--text-muted);">Akun</span>
                    </div>
                    <a href="{{ route('profile.edit') }}" class="sidebar-link {{ request()->routeIs('profile.*') ? 'active' : '' }}" id="nav-profile">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06A1.65 1.65 0 0 0 4.68 15a1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06A1.65 1.65 0 0 0 9 4.68a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"/>
                        </svg>
                        Pengaturan
                    </a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <a href="{{ route('logout') }}" onclick="event.preventDefault(); this.closest('form').submit();" class="sidebar-link" id="nav-logout">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/>
                            </svg>
                            Keluar
                        </a>
                    </form>
                </div>
            </nav>
        </aside>

        <!-- Main Content -->
        <div class="main-content">
            <!-- Top Bar -->
            <div class="topbar">
                <div style="display: flex; align-items: center; gap: 1rem;">
                    <button class="mobile-menu-btn" onclick="toggleSidebar()">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="18" x2="21" y2="18"/>
                        </svg>
                    </button>
                    @isset($header)
                        <h1 class="topbar-title">{{ $header }}</h1>
                    @endisset
                </div>
                <div class="topbar-user">
                    <div class="user-dropdown" id="userDropdown">
                        <button onclick="toggleDropdown()" style="display: flex; align-items: center; gap: 0.75rem; background: transparent; border: none; cursor: pointer; color: var(--text-primary);">
                            <div>
                                <div style="font-size: 0.875rem; font-weight: 600; text-align: right;">{{ Auth::user()->name }}</div>
                                <div style="font-size: 0.6875rem; color: var(--text-muted); text-align: right;">
                                    {{ Auth::user()->roles->first()?->name ?? 'User' }}
                                </div>
                            </div>
                            <div class="user-avatar">
                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                            </div>
                        </button>
                        <div class="user-dropdown-menu" id="dropdownMenu">
                            <a href="{{ route('profile.edit') }}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display:inline; vertical-align: middle; margin-right: 0.5rem;"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                                Profil
                            </a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display:inline; vertical-align: middle; margin-right: 0.5rem;"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
                                    Keluar
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Page Content -->
            <main style="padding: 1.5rem 2rem;" class="fade-in">
                @if(session('success'))
                    <div class="alert alert-success" id="alert-success">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                        {{ session('success') }}
                    </div>
                @endif
                @if(session('error'))
                    <div class="alert alert-error" id="alert-error">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
                        {{ session('error') }}
                    </div>
                @endif

                {{ $slot }}
            </main>
        </div>

        <script>
            function toggleSidebar() {
                document.getElementById('sidebar').classList.toggle('open');
                document.getElementById('sidebarOverlay').classList.toggle('show');
            }

            function toggleDropdown() {
                document.getElementById('dropdownMenu').classList.toggle('show');
            }

            // Close dropdown when clicking outside
            document.addEventListener('click', function(e) {
                const dropdown = document.getElementById('userDropdown');
                if (dropdown && !dropdown.contains(e.target)) {
                    document.getElementById('dropdownMenu').classList.remove('show');
                }
            });

            // Auto-dismiss alerts after 5 seconds
            setTimeout(() => {
                const alerts = document.querySelectorAll('.alert');
                alerts.forEach(alert => {
                    alert.style.transition = 'opacity 0.5s ease';
                    alert.style.opacity = '0';
                    setTimeout(() => alert.remove(), 500);
                });
            }, 5000);
        </script>
    </body>
</html>
