<!DOCTYPE html>
<html lang="{{ session('lang', 'en') }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __t('app_name') }} - @yield('title', __t('admin_panel'))</title>
    <link rel="icon" type="image/png" href="{{ asset('images/TUME.png') }}">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    keyframes: {
                        fadeIn: { '0%': { opacity: '0', transform: 'translateY(8px)' }, '100%': { opacity: '1', transform: 'translateY(0)' } },
                        slideDown: { '0%': { opacity: '0', transform: 'translateY(-20px)' }, '100%': { opacity: '1', transform: 'translateY(0)' } },
                        slideUp: { '0%': { opacity: '0', transform: 'translateY(20px)' }, '100%': { opacity: '1', transform: 'translateY(0)' } },
                        scaleIn: { '0%': { opacity: '0', transform: 'scale(0.95)' }, '100%': { opacity: '1', transform: 'scale(1)' } },
                        pulse2: { '0%, 100%': { transform: 'scale(1)' }, '50%': { transform: 'scale(1.05)' } },
                        spinSlow: { '0%': { transform: 'rotate(0deg)' }, '100%': { transform: 'rotate(360deg)' } },
                    },
                    animation: {
                        fadeIn: 'fadeIn 0.4s ease-out',
                        slideDown: 'slideDown 0.3s ease-out',
                        slideUp: 'slideUp 0.4s ease-out',
                        scaleIn: 'scaleIn 0.3s ease-out',
                        pulse2: 'pulse2 2s ease-in-out infinite',
                        spinSlow: 'spinSlow 3s linear infinite',
                    },
                },
            },
        };
    </script>
    <script src="https://cdn.tailwindcss.com"></script>
    @stack('head')
    <style>
        * { scrollbar-width: thin; scrollbar-color: #cbd5e1 transparent; }
        ::-webkit-scrollbar { width: 4px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #94a3b8; border-radius: 2px; }

        body { background: #f1f5f9; font-family: system-ui, -apple-system, sans-serif; }

        .sidebar { background: #0f172a; }

        @keyframes fadeIn { 0% { opacity: 0; transform: translateY(8px); } 100% { opacity: 1; transform: translateY(0); } }
        @keyframes slideDown { 0% { opacity: 0; transform: translateY(-20px); } 100% { opacity: 1; transform: translateY(0); } }
        @keyframes slideUp { 0% { opacity: 0; transform: translateY(20px); } 100% { opacity: 1; transform: translateY(0); } }
        @keyframes scaleIn { 0% { opacity: 0; transform: scale(0.95); } 100% { opacity: 1; transform: scale(1); } }
        @keyframes pulse2 { 0%, 100% { transform: scale(1); } 50% { transform: scale(1.05); } }
        @keyframes spinSlow { to { transform: rotate(360deg); } }

        .text-10px { font-size: 10px; }

        .nav-link {
            position: relative;
            transition: all 0.2s ease;
            border-left: 3px solid transparent;
        }
        .nav-link:hover {
            background: rgba(255,255,255,0.06);
            border-left-color: #3b82f6;
        }
        .nav-link.active {
            background: rgba(59,130,246,0.1);
            border-left-color: #3b82f6;
        }
        .nav-link.active .nav-icon { color: #60a5fa; }
        .nav-link.active::after {
            content: ''; position: absolute; right: 12px; top: 50%;
            width: 5px; height: 5px; border-radius: 50%;
            background: #3b82f6; transform: translateY(-50%);
        }

        .main-content { animation: fadeIn 0.4s ease-out; }

        .card { animation: fadeIn 0.4s ease-out; }
        .card-hover { transition: all 0.2s ease; }
        .card-hover:hover { transform: translateY(-2px); box-shadow: 0 12px 30px rgba(0,0,0,0.08); }

        .stat-icon { transition: all 0.3s ease; }
        .card-hover:hover .stat-icon { transform: scale(1.1); }

        .btn { transition: all 0.2s ease; cursor: pointer; }
        .btn:hover { transform: translateY(-1px); box-shadow: 0 4px 12px rgba(0,0,0,0.15); }
        .btn:active { transform: translateY(0); }

        .table-wrap { animation: fadeIn 0.4s ease-out; }
        .table-wrap table { border-collapse: separate; border-spacing: 0; }
        .table-wrap th {
            font-weight: 600; font-size: 0.7rem; letter-spacing: 0.05em;
            text-transform: uppercase; color: #64748b;
            padding: 0.75rem 1rem; background: #f8fafc;
            border-bottom: 1px solid #e2e8f0;
        }
        .table-wrap td {
            padding: 0.75rem 1rem; border-bottom: 1px solid #f1f5f9;
            font-size: 0.875rem; color: #334155;
        }
        .table-wrap tr:last-child td { border-bottom: none; }
        .table-wrap tbody tr { transition: background 0.15s ease; }
        .table-wrap tbody tr:hover { background: #f8fafc; }

        .badge {
            display: inline-flex; align-items: center; gap: 0.25rem;
            padding: 0.125rem 0.625rem; border-radius: 9999px;
            font-size: 0.75rem; font-weight: 500;
        }

        .filter-btn {
            padding: 0.375rem 0.875rem; border-radius: 9999px;
            font-size: 0.8125rem; font-weight: 500;
            transition: all 0.2s ease; cursor: pointer;
        }

        .page-title { font-size: 1.5rem; font-weight: 700; color: #0f172a; }

        input, select, textarea {
            transition: all 0.2s ease;
            border: 1px solid #e2e8f0; border-radius: 0.5rem;
            padding: 0.5rem 0.75rem; font-size: 0.875rem;
            width: 100%; outline: none;
        }
        input:focus, select:focus, textarea:focus {
            border-color: #3b82f6; box-shadow: 0 0 0 3px rgba(59,130,246,0.1);
        }

        .user-avatar { transition: all 0.2s ease; }
        .user-avatar:hover { transform: scale(1.05); }

        .dropdown-menu {
            opacity: 0; visibility: hidden; transform: translateY(-4px);
            transition: all 0.2s ease;
        }
        .dropdown-menu.open {
            opacity: 1; visibility: visible; transform: translateY(0);
        }

        .top-loader {
            position: fixed; top: 0; left: 0; z-index: 9999;
            width: 100%; height: 3px; pointer-events: none;
        }
        .top-loader .bar {
            height: 100%; width: 0%;
            background: linear-gradient(90deg, #3b82f6, #8b5cf6);
            transition: width 0.3s ease;
        }
        .top-loader.hide { display: none; }
    </style>
</head>
<body class="min-h-screen flex">

    <div id="topLoader" class="top-loader"><div class="bar" id="topLoaderBar"></div></div>

    @php $user = Auth::user(); @endphp

    <aside id="sidebar" class="sidebar w-64 text-white flex flex-col fixed h-full z-30 transition-all duration-300 shadow-xl">
        <div class="h-16 flex items-center px-5 border-b border-white/10">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center space-x-3 group">
                <div class="bg-white/10 rounded-xl p-2 flex items-center justify-center group-hover:bg-white/20 transition-all">
                    <img src="{{ asset('images/TUME.png') }}" alt="Logo" class="h-9 w-9">
                </div>
                <div class="leading-tight">
                    <span class="font-bold text-sm block">{{ __t('app_name') }}</span>
                    <span class="text-10px text-blue-400 font-medium tracking-wider uppercase">Admin Panel</span>
                </div>
            </a>
            <button onclick="toggleSidebar()" class="ml-auto text-blue-400 hover:text-white p-1.5 rounded-lg hover:bg-white/10 transition-all">
                <svg id="sidebarToggleIcon" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l-7-7 7-7m8 14l-7-7 7-7"/></svg>
            </button>
        </div>

        <nav class="flex-1 overflow-y-auto py-3 px-3 space-y-0.5 text-sm">
            <div class="px-3 py-2 text-10px uppercase tracking-widest text-blue-400/50 font-semibold">Main Menu</div>

            <a href="{{ route('admin.dashboard') }}" class="nav-link flex items-center space-x-3 px-3 py-2.5 rounded-lg {{ request()->routeIs('admin.dashboard') ? 'active' : 'text-blue-100 hover:text-white' }}">
                <svg class="nav-icon w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                <span>{{ __t('dashboard') }}</span>
            </a>

            <div class="px-3 py-2 mt-4 text-10px uppercase tracking-widest text-blue-400/50 font-semibold">Management</div>

            <a href="{{ route('admin.users') }}" class="nav-link flex items-center space-x-3 px-3 py-2.5 rounded-lg {{ request()->routeIs('admin.users*') ? 'active' : 'text-blue-100 hover:text-white' }}">
                <svg class="nav-icon w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/></svg>
                <span>{{ __t('manage_users') }}</span>
            </a>
            <a href="{{ route('admin.candidates') }}" class="nav-link flex items-center space-x-3 px-3 py-2.5 rounded-lg {{ request()->routeIs('admin.candidates*') ? 'active' : 'text-blue-100 hover:text-white' }}">
                <svg class="nav-icon w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                <span>{{ __t('manage_candidates') }}</span>
            </a>
            <a href="{{ route('admin.elections') }}" class="nav-link flex items-center space-x-3 px-3 py-2.5 rounded-lg {{ request()->routeIs('admin.elections*') ? 'active' : 'text-blue-100 hover:text-white' }}">
                <svg class="nav-icon w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
                <span>{{ __t('manage_elections') }}</span>
            </a>
            <a href="{{ route('admin.positions') }}" class="nav-link flex items-center space-x-3 px-3 py-2.5 rounded-lg {{ request()->routeIs('admin.positions*') ? 'active' : 'text-blue-100 hover:text-white' }}">
                <svg class="nav-icon w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                <span>Positions</span>
            </a>

            <div class="px-3 py-2 mt-4 text-10px uppercase tracking-widest text-blue-400/50 font-semibold">Voting</div>

            <a href="{{ route('admin.votes') }}" class="nav-link flex items-center space-x-3 px-3 py-2.5 rounded-lg {{ request()->routeIs('admin.votes*') ? 'active' : 'text-blue-100 hover:text-white' }}">
                <svg class="nav-icon w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span>{{ __t('votes') }}</span>
            </a>
            <a href="{{ route('admin.results') }}" class="nav-link flex items-center space-x-3 px-3 py-2.5 rounded-lg {{ request()->routeIs('admin.results*') ? 'active' : 'text-blue-100 hover:text-white' }}">
                <svg class="nav-icon w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                <span>{{ __t('results') }}</span>
            </a>
            <a href="{{ route('admin.assisted_votes') }}" class="nav-link flex items-center space-x-3 px-3 py-2.5 rounded-lg {{ request()->routeIs('admin.assisted_votes*') ? 'active' : 'text-blue-100 hover:text-white' }}">
                <svg class="nav-icon w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                <span>{{ __t('assisted_votes') }}</span>
            </a>

            <div class="px-3 py-2 mt-4 text-10px uppercase tracking-widest text-blue-400/50 font-semibold">Oversight</div>

            <a href="{{ route('admin.objections') }}" class="nav-link flex items-center space-x-3 px-3 py-2.5 rounded-lg {{ request()->routeIs('admin.objections*') ? 'active' : 'text-blue-100 hover:text-white' }}">
                <svg class="nav-icon w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span>{{ __t('objections') }}</span>
            </a>
            <a href="{{ route('admin.violations') }}" class="nav-link flex items-center space-x-3 px-3 py-2.5 rounded-lg {{ request()->routeIs('admin.violations*') ? 'active' : 'text-blue-100 hover:text-white' }}">
                <svg class="nav-icon w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/></svg>
                <span>{{ __t('code_conduct_violations') }}</span>
            </a>
            <a href="{{ route('admin.announcements') }}" class="nav-link flex items-center space-x-3 px-3 py-2.5 rounded-lg {{ request()->routeIs('admin.announcements*') ? 'active' : 'text-blue-100 hover:text-white' }}">
                <svg class="nav-icon w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/></svg>
                <span>{{ __t('announcements') }}</span>
            </a>

            <div class="px-3 py-2 mt-4 text-10px uppercase tracking-widest text-blue-400/50 font-semibold">Security</div>

            <a href="{{ route('admin.audit_logs') }}" class="nav-link flex items-center space-x-3 px-3 py-2.5 rounded-lg {{ request()->routeIs('admin.audit_logs*') ? 'active' : 'text-blue-100 hover:text-white' }}">
                <svg class="nav-icon w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                <span>{{ __t('audit_logs') }}</span>
            </a>
            <a href="{{ route('admin.suspicious_logs') }}" class="nav-link flex items-center space-x-3 px-3 py-2.5 rounded-lg {{ request()->routeIs('admin.suspicious_logs*') ? 'active' : 'text-blue-100 hover:text-white' }}">
                <svg class="nav-icon w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/></svg>
                <span>{{ __t('suspicious_activity') }}</span>
            </a>
            <a href="{{ route('admin.accessibility_logs') }}" class="nav-link flex items-center space-x-3 px-3 py-2.5 rounded-lg {{ request()->routeIs('admin.accessibility_logs*') ? 'active' : 'text-blue-100 hover:text-white' }}">
                <svg class="nav-icon w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span>♿ {{ __t('accessibility_logs') }}</span>
            </a>

            <div class="px-3 py-2 mt-4 text-10px uppercase tracking-widest text-blue-400/50 font-semibold">System</div>

            <a href="{{ route('admin.settings') }}" class="nav-link flex items-center space-x-3 px-3 py-2.5 rounded-lg {{ request()->routeIs('admin.settings*') ? 'active' : 'text-blue-100 hover:text-white' }}">
                <svg class="nav-icon w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                <span>{{ __t('settings') }}</span>
            </a>

            <div class="px-3 py-2 mt-4 text-10px uppercase tracking-widest text-blue-400/50 font-semibold">Preview</div>

            <a href="{{ route('preview.voter') }}" class="nav-link flex items-center space-x-3 px-3 py-2.5 rounded-lg text-blue-100 hover:text-white">
                <svg class="nav-icon w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                <span>{{ __t('view_site') }} ({{ __t('voter') }})</span>
            </a>
            <a href="{{ route('preview.candidate') }}" class="nav-link flex items-center space-x-3 px-3 py-2.5 rounded-lg text-blue-100 hover:text-white">
                <svg class="nav-icon w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                <span>{{ __t('view_site') }} ({{ __t('candidate_role') }})</span>
            </a>
        </nav>

        <div class="p-3 border-t border-white/10">
            <a href="{{ route('dashboard') }}" class="flex items-center space-x-2.5 text-blue-400 hover:text-white text-sm transition-all px-3 py-2 rounded-lg hover:bg-white/5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                <span>{{ __t('back') }} {{ __t('home') }}</span>
            </a>
        </div>
    </aside>

    <div id="mainWrapper" class="flex-1 ml-64 transition-all duration-300">
        <header class="sticky top-0 z-20 bg-white/80 backdrop-blur-md border-b border-gray-200">
            <div class="flex items-center justify-between h-16 px-6">
                <div class="flex items-center space-x-3">
                    <button onclick="toggleSidebar()" class="p-2 text-gray-500 hover:text-gray-700 hover:bg-gray-100 rounded-lg transition-all">
                        <svg id="hamburgerIcon" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                    </button>
                    <div>
                        <h1 class="page-title">@yield('title', __t('admin_panel'))</h1>
                        <p class="text-xs text-gray-400 mt-0.5">@yield('subtitle', '&nbsp;')</p>
                    </div>
                </div>
                <div class="flex items-center space-x-4">
                    <div class="flex items-center space-x-1 px-3 py-1.5 rounded-lg text-xs font-medium
                        @if(session('system_status', 'safe') === 'alert') bg-red-50 text-red-700
                        @else bg-green-50 text-green-700 @endif">
                        <span class="w-2 h-2 rounded-full inline-block
                            @if(session('system_status', 'safe') === 'alert') bg-red-500
                            @else bg-green-500 @endif"></span>
                        <span>{{ __t(session('system_status', 'safe')) }}</span>
                    </div>

                    <div class="h-5 w-px bg-gray-200"></div>

                    <a href="{{ route('language.set', 'en') }}" class="text-xs font-semibold border rounded-lg px-2.5 py-1.5 transition-all hover:bg-gray-50 {{ session('lang') == 'en' ? 'bg-blue-50 border-blue-200 text-blue-700' : 'border-gray-200 text-gray-600' }}">EN</a>
                    <a href="{{ route('language.set', 'sw') }}" class="text-xs font-semibold border rounded-lg px-2.5 py-1.5 transition-all hover:bg-gray-50 {{ session('lang') == 'sw' ? 'bg-blue-50 border-blue-200 text-blue-700' : 'border-gray-200 text-gray-600' }}">SW</a>

                    <div class="h-5 w-px bg-gray-200"></div>

                    <div class="relative" x-data="{ open: false }">
                        <button onclick="toggleUserMenu()" class="flex items-center space-x-3 group cursor-pointer">
                            <div class="text-right hidden sm:block">
                                <p class="text-sm font-medium text-gray-800 leading-tight">{{ $user->full_name }}</p>
                                <p class="text-xs text-gray-400">{{ $user->role ?? 'Admin' }}</p>
                            </div>
                            <div class="w-9 h-9 rounded-full bg-gradient-to-br from-blue-600 to-blue-800 flex items-center justify-center text-white text-sm font-bold shadow-md user-avatar">
                                {{ substr($user->full_name, 0, 1) }}
                            </div>
                        </button>
                        <div id="userMenu" class="dropdown-menu absolute right-0 top-full mt-2 w-48 bg-white rounded-xl shadow-lg border border-gray-100 py-1 z-50">
                            <div class="px-4 py-2 border-b border-gray-100">
                                <p class="text-sm font-medium text-gray-800">{{ $user->full_name }}</p>
                                <p class="text-xs text-gray-400">{{ $user->email }}</p>
                            </div>
                            <a href="{{ route('profile') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 transition-all">{{ __t('profile') }}</a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition-all">{{ __t('logout') }}</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <main class="p-6" style="min-height:calc(100vh-4rem)">
            @if(session('success'))
                <div class="flash-msg flex items-center justify-between bg-green-50 border border-green-200 text-green-800 px-5 py-3 rounded-xl mb-5 shadow-sm">
                    <div class="flex items-center space-x-2.5">
                        <svg class="w-5 h-5 text-green-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span>{{ session('success') }}</span>
                    </div>
                    <button onclick="this.parentElement.remove()" class="text-green-600 hover:text-green-800 ml-4 p-1 hover:bg-green-100 rounded-lg transition-all">&times;</button>
                </div>
            @endif
            @if(session('error'))
                <div class="flash-msg flex items-center justify-between bg-red-50 border border-red-200 text-red-800 px-5 py-3 rounded-xl mb-5 shadow-sm">
                    <div class="flex items-center space-x-2.5">
                        <svg class="w-5 h-5 text-red-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span>{{ session('error') }}</span>
                    </div>
                    <button onclick="this.parentElement.remove()" class="text-red-600 hover:text-red-800 ml-4 p-1 hover:bg-red-100 rounded-lg transition-all">&times;</button>
                </div>
            @endif
            @if(session('warning'))
                <div class="flash-msg flex items-center justify-between bg-yellow-50 border border-yellow-200 text-yellow-800 px-5 py-3 rounded-xl mb-5 shadow-sm">
                    <div class="flex items-center space-x-2.5">
                        <svg class="w-5 h-5 text-yellow-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/></svg>
                        <span>{{ session('warning') }}</span>
                    </div>
                    <button onclick="this.parentElement.remove()" class="text-yellow-600 hover:text-yellow-800 ml-4 p-1 hover:bg-yellow-100 rounded-lg transition-all">&times;</button>
                </div>
            @endif
            @if(session('info'))
                <div class="flash-msg flex items-center justify-between bg-blue-50 border border-blue-200 text-blue-800 px-5 py-3 rounded-xl mb-5 shadow-sm">
                    <div class="flex items-center space-x-2.5">
                        <svg class="w-5 h-5 text-blue-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span>{{ session('info') }}</span>
                    </div>
                    <button onclick="this.parentElement.remove()" class="text-blue-600 hover:text-blue-800 ml-4 p-1 hover:bg-blue-100 rounded-lg transition-all">&times;</button>
                </div>
            @endif
            @if($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-800 px-5 py-3 rounded-xl mb-5 shadow-sm">
                    @foreach($errors->all() as $error)
                        <p class="flex items-center space-x-2 text-sm"><span class="w-1.5 h-1.5 bg-red-400 rounded-full"></span><span>{{ $error }}</span></p>
                    @endforeach
                </div>
            @endif
            @yield('content')
        </main>
    </div>

    <script>
        var loader = document.getElementById('topLoader');
        var loaderBar = document.getElementById('topLoaderBar');
        if (loader) {
            window.addEventListener('load', function() { hideLoader(); });
            setTimeout(hideLoader, 1500);
        }
        function hideLoader() { loader.classList.add('hide'); }
        function showLoader() {
            loader.classList.remove('hide');
            loaderBar.style.width = '30%';
            setTimeout(function() { loaderBar.style.width = '70%'; }, 200);
        }
        function finishLoader() {
            loaderBar.style.width = '100%';
            setTimeout(hideLoader, 400);
        }

        document.querySelectorAll('.flash-msg').forEach(function(el) {
            setTimeout(function() {
                el.style.transition = 'opacity 0.4s ease, transform 0.4s ease';
                el.style.opacity = '0';
                el.style.transform = 'translateX(30px)';
                setTimeout(function() { el.remove(); }, 400);
            }, 5000);
        });

        document.addEventListener('click', function(e) {
            var link = e.target.closest('a');
            if (link && link.hostname === window.location.hostname && !link.hasAttribute('download') && !link.getAttribute('href')?.startsWith('#') && loader) {
                var method = link.getAttribute('data-method') || link.getAttribute('onclick');
                if (!method && !e.ctrlKey && !e.metaKey && link.target !== '_blank') {
                    showLoader();
                }
            }
        });

        window.addEventListener('beforeunload', function() { showLoader(); });

        var sidebarBackdrop = null;
        function createBackdrop() {
            if (!sidebarBackdrop) {
                sidebarBackdrop = document.createElement('div');
                sidebarBackdrop.className = 'fixed inset-0 bg-black/40 z-20 lg:hidden transition-opacity duration-300';
                sidebarBackdrop.style.opacity = '0';
                sidebarBackdrop.style.pointerEvents = 'none';
                sidebarBackdrop.addEventListener('click', function() { toggleSidebar(); });
                document.body.appendChild(sidebarBackdrop);
            }
            return sidebarBackdrop;
        }

        function toggleSidebar() {
            var sidebar = document.getElementById('sidebar');
            var wrapper = document.getElementById('mainWrapper');
            var isHidden = sidebar.classList.contains('-translate-x-full');
            var isMobile = window.innerWidth < 1024;
            var backdrop = createBackdrop();

            if (isHidden) {
                sidebar.classList.remove('-translate-x-full');
                if (!isMobile) {
                    wrapper.classList.remove('ml-0');
                    wrapper.classList.add('ml-64');
                }
                if (isMobile) {
                    backdrop.style.pointerEvents = 'auto';
                    backdrop.style.opacity = '1';
                }
                document.getElementById('sidebarToggleIcon').innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l-7-7 7-7m8 14l-7-7 7-7"/>';
                document.getElementById('hamburgerIcon').innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>';
                if (!isMobile) localStorage.setItem('sidebar_collapsed', 'false');
            } else {
                sidebar.classList.add('-translate-x-full');
                if (!isMobile) {
                    wrapper.classList.remove('ml-64');
                    wrapper.classList.add('ml-0');
                }
                if (isMobile) {
                    backdrop.style.pointerEvents = 'none';
                    backdrop.style.opacity = '0';
                }
                document.getElementById('sidebarToggleIcon').innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5l7 7-7 7M5 5l7 7-7 7"/>';
                document.getElementById('hamburgerIcon').innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>';
                if (!isMobile) localStorage.setItem('sidebar_collapsed', 'true');
            }
        }

        function toggleUserMenu() {
            document.getElementById('userMenu').classList.toggle('open');
        }
        document.addEventListener('click', function(e) {
            var menu = document.getElementById('userMenu');
            if (menu && menu.classList.contains('open') && !e.target.closest('[onclick="toggleUserMenu()"]') && !e.target.closest('#userMenu')) {
                menu.classList.remove('open');
            }
        });

        (function() {
            var isMobile = window.innerWidth < 1024;
            var sidebar = document.getElementById('sidebar');
            var wrapper = document.getElementById('mainWrapper');

            if (isMobile) {
                sidebar.classList.add('-translate-x-full');
                wrapper.classList.remove('ml-64');
                wrapper.classList.add('ml-0');
            } else if (localStorage.getItem('sidebar_collapsed') === 'true') {
                sidebar.classList.add('-translate-x-full');
                wrapper.classList.remove('ml-64');
                wrapper.classList.add('ml-0');
                document.getElementById('sidebarToggleIcon').innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5l7 7-7 7M5 5l7 7-7 7"/>';
                document.getElementById('hamburgerIcon').innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>';
            }

            window.addEventListener('resize', function() {
                var m = window.innerWidth < 1024;
                if (m) {
                    if (!sidebar.classList.contains('-translate-x-full')) {
                        sidebar.classList.add('-translate-x-full');
                    }
                    wrapper.classList.remove('ml-64');
                    wrapper.classList.add('ml-0');
                    if (sidebarBackdrop) { sidebarBackdrop.style.pointerEvents = 'none'; sidebarBackdrop.style.opacity = '0'; }
                } else {
                    if (localStorage.getItem('sidebar_collapsed') !== 'true') {
                        sidebar.classList.remove('-translate-x-full');
                        wrapper.classList.remove('ml-0');
                        wrapper.classList.add('ml-64');
                    }
                }
            });
        })();
    </script>
    @stack('scripts')
</body>
</html>
