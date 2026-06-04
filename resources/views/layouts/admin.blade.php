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
                        fadeIn: { '0%': { opacity: '0', transform: 'translateY(10px)' }, '100%': { opacity: '1', transform: 'translateY(0)' } },
                        slideDown: { '0%': { opacity: '0', transform: 'translateY(-30px)' }, '100%': { opacity: '1', transform: 'translateY(0)' } },
                        slideUp: { '0%': { opacity: '0', transform: 'translateY(30px)' }, '100%': { opacity: '1', transform: 'translateY(0)' } },
                        slideInRight: { '0%': { opacity: '0', transform: 'translateX(100%)' }, '100%': { opacity: '1', transform: 'translateX(0)' } },
                        slideInLeft: { '0%': { opacity: '0', transform: 'translateX(-30px)' }, '100%': { opacity: '1', transform: 'translateX(0)' } },
                        scaleIn: { '0%': { opacity: '0', transform: 'scale(0.85)' }, '100%': { opacity: '1', transform: 'scale(1)' } },
                        bounceIn: { '0%': { opacity: '0', transform: 'scale(0.3)' }, '50%': { transform: 'scale(1.08)' }, '70%': { transform: 'scale(0.95)' }, '100%': { opacity: '1', transform: 'scale(1)' } },
                        shimmer: { '0%': { backgroundPosition: '-200% 0' }, '100%': { backgroundPosition: '200% 0' } },
                        glow: { '0%, 100%': { boxShadow: '0 0 5px rgba(59,130,246,0.3)' }, '50%': { boxShadow: '0 0 20px rgba(59,130,246,0.7)' } },
                        float: { '0%, 100%': { transform: 'translateY(0)' }, '50%': { transform: 'translateY(-6px)' } },
                        pulse: { '0%, 100%': { transform: 'scale(1)' }, '50%': { transform: 'scale(1.05)' } },
                        wiggle: { '0%, 100%': { transform: 'rotate(0)' }, '25%': { transform: 'rotate(-3deg)' }, '75%': { transform: 'rotate(3deg)' } },
                        flipIn: { '0%': { opacity: '0', transform: 'rotateX(-90deg)' }, '100%': { opacity: '1', transform: 'rotateX(0)' } },
                    },
                    animation: {
                        fadeIn: 'fadeIn 0.6s ease-out',
                        slideDown: 'slideDown 0.5s ease-out',
                        slideUp: 'slideUp 0.5s ease-out',
                        slideInRight: 'slideInRight 0.5s ease-out',
                        slideInLeft: 'slideInLeft 0.5s ease-out',
                        scaleIn: 'scaleIn 0.5s ease-out',
                        bounceIn: 'bounceIn 0.7s ease-out',
                        shimmer: 'shimmer 2s linear infinite',
                        glow: 'glow 2s ease-in-out infinite',
                        float: 'float 3s ease-in-out infinite',
                        pulse: 'pulse 2s ease-in-out infinite',
                        wiggle: 'wiggle 0.5s ease-in-out',
                        flipIn: 'flipIn 0.6s ease-out',
                    },
                },
            },
        };
    </script>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        * { scrollbar-width: thin; scrollbar-color: #cbd5e1 transparent; }
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 3px; }

        .main-content { animation: fadeIn 0.5s ease-out; }
        .card-anim { animation: scaleIn 0.5s ease-out both; }
        .top-bar { position: fixed; top: 0; left: 0; width: 100%; height: 3px; z-index: 9999; background: #e5e7eb; }
        .top-bar-fill { height: 100%; width: 0%; background: linear-gradient(90deg, #3b82f6, #60a5fa, #93c5fd); border-radius: 0 2px 2px 0; }

        .nav-link { position: relative; overflow: hidden; transition: all 0.3s ease; }
        .nav-link::before {
            content: ''; position: absolute; left: 0; top: 0; width: 4px; height: 100%;
            background: #60a5fa; transform: scaleY(0); transition: transform 0.3s ease;
        }
        .nav-link:hover::before, .nav-link.active::before { transform: scaleY(1); }
        .nav-link:hover { background: rgba(30, 64, 175, 0.8); transform: translateX(4px); }

        .sidebar-icon { transition: all 0.4s ease; }
        .nav-link:hover .sidebar-icon { transform: translateX(6px) scale(1.1); }
        .nav-link.active .sidebar-icon { color: #60a5fa; }

        .flash-msg:hover { transform: translateX(-4px); }

        .card-hover { transition: all 0.35s cubic-bezier(0.4, 0, 0.2, 1); }
        .card-hover:hover { transform: translateY(-4px) scale(1.01); box-shadow: 0 12px 35px rgba(0,0,0,0.12); }

        .btn-anim { transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1); position: relative; overflow: hidden; }
        .btn-anim:hover { transform: translateY(-2px) scale(1.04); box-shadow: 0 6px 20px rgba(0,0,0,0.15); }
        .btn-anim:active { transform: translateY(0) scale(0.96); }
        .btn-anim::after {
            content: ''; position: absolute; inset: 0; background: linear-gradient(120deg, transparent 30%, rgba(255,255,255,0.2) 50%, transparent 70%);
            transform: translateX(-100%); transition: transform 0.6s ease;
        }
        .btn-anim:hover::after { transform: translateX(100%); }

        .table-row { transition: all 0.25s ease; }
        .table-row:hover { transform: translateX(6px) scale(1.005); box-shadow: 0 3px 12px rgba(0,0,0,0.08); }
        .table-row td:first-child { transition: padding-left 0.25s ease; }
        .table-row:hover td:first-child { padding-left: 20px; }

        .badge-anim { transition: all 0.3s ease; display: inline-block; }
        .badge-anim:hover { transform: scale(1.15) rotate(2deg); box-shadow: 0 2px 8px rgba(0,0,0,0.1); }

        .stagger-1 { animation: slideUp 0.45s ease-out 0.05s both; }
        .stagger-2 { animation: slideUp 0.45s ease-out 0.1s both; }
        .stagger-3 { animation: slideUp 0.45s ease-out 0.15s both; }
        .stagger-4 { animation: slideUp 0.45s ease-out 0.2s both; }
        .stagger-5 { animation: slideUp 0.45s ease-out 0.25s both; }
        .stagger-6 { animation: slideUp 0.45s ease-out 0.3s both; }
        .stagger-7 { animation: slideUp 0.45s ease-out 0.35s both; }
        .stagger-8 { animation: slideUp 0.45s ease-out 0.4s both; }
        .stagger-9 { animation: slideUp 0.45s ease-out 0.45s both; }
        .stagger-10 { animation: slideUp 0.45s ease-out 0.5s both; }

        .system-status { animation: glow 2s ease-in-out infinite; }

        table { animation: fadeIn 0.6s ease-out; }
        table thead tr { animation: slideDown 0.3s ease-out; }
    </style>
</head>
<body class="bg-gray-100 min-h-screen flex">
    <div id="pageLoader" style="position:fixed;inset:0;z-index:9999;background:#1e3a8a;display:flex;flex-direction:column;align-items:center;justify-content:center;transition:opacity 0.5s ease,visibility 0.5s ease">
        <div style="animation:loaderPulse 1.5s ease-in-out infinite;background:white;border-radius:50%;padding:20px;display:flex;align-items:center;justify-content:center;box-shadow:0 10px 40px rgba(0,0,0,0.3)">
            <img src="{{ asset('images/TUME.png') }}" alt="TUME Logo" style="height:96px;width:96px">
        </div>
        <div style="width:32px;height:32px;border:3px solid rgba(255,255,255,0.2);border-top-color:white;border-radius:50%;animation:loaderSpin 0.8s linear infinite;margin-top:20px"></div>
    </div>
    <style>
        @keyframes loaderPulse { 0%,100% { transform:scale(1);opacity:0.9; } 50% { transform:scale(1.08);opacity:1; } }
        @keyframes loaderSpin { to { transform:rotate(360deg); } }
        .loader-hide { opacity: 0 !important; visibility: hidden !important; }
    </style>
    <div class="top-bar"><div class="top-bar-fill" id="topBarFill"></div></div>
    @php $user = Auth::user(); @endphp
    <aside class="w-64 bg-blue-900 text-white flex flex-col fixed h-full z-10 card-anim">
        <div class="p-4 border-b border-blue-800">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center space-x-3">
                <div class="bg-white rounded-full p-3 flex items-center justify-center shadow-lg"><img src="{{ asset('images/TUME.png') }}" alt="Logo" class="h-12 w-12"></div>
                <span class="font-bold text-sm leading-tight">{{ __t('app_name') }}</span>
            </a>
        </div>
        <nav class="flex-1 overflow-y-auto py-4">
            <a href="{{ route('admin.dashboard') }}" class="nav-link flex items-center space-x-3 px-4 py-3 hover:bg-blue-800 {{ request()->routeIs('admin.dashboard') ? 'bg-blue-800 active' : '' }}">
                <svg class="sidebar-icon w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                <span>{{ __t('dashboard') }}</span>
            </a>
            <a href="{{ route('admin.users') }}" class="nav-link flex items-center space-x-3 px-4 py-3 hover:bg-blue-800 {{ request()->routeIs('admin.users*') ? 'bg-blue-800 active' : '' }}">
                <svg class="sidebar-icon w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/></svg>
                <span>{{ __t('manage_users') }}</span>
            </a>
            <a href="{{ route('admin.candidates') }}" class="nav-link flex items-center space-x-3 px-4 py-3 hover:bg-blue-800 {{ request()->routeIs('admin.candidates*') ? 'bg-blue-800 active' : '' }}">
                <svg class="sidebar-icon w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                <span>{{ __t('manage_candidates') }}</span>
            </a>
            <a href="{{ route('admin.elections') }}" class="nav-link flex items-center space-x-3 px-4 py-3 hover:bg-blue-800 {{ request()->routeIs('admin.elections*') ? 'bg-blue-800 active' : '' }}">
                <svg class="sidebar-icon w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
                <span>{{ __t('manage_elections') }}</span>
            </a>
            <a href="{{ route('admin.votes') }}" class="nav-link flex items-center space-x-3 px-4 py-3 hover:bg-blue-800 {{ request()->routeIs('admin.votes*') ? 'bg-blue-800 active' : '' }}">
                <svg class="sidebar-icon w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span>{{ __t('votes') }}</span>
            </a>
            <a href="{{ route('admin.results') }}" class="nav-link flex items-center space-x-3 px-4 py-3 hover:bg-blue-800 {{ request()->routeIs('admin.results*') ? 'bg-blue-800 active' : '' }}">
                <svg class="sidebar-icon w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                <span>{{ __t('results') }}</span>
            </a>
            <div class="border-t border-blue-800 my-2"></div>
            <a href="{{ route('admin.announcements') }}" class="nav-link flex items-center space-x-3 px-4 py-3 hover:bg-blue-800 {{ request()->routeIs('admin.announcements*') ? 'bg-blue-800 active' : '' }}">
                <svg class="sidebar-icon w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/></svg>
                <span>{{ __t('announcements') }}</span>
            </a>
            <a href="{{ route('admin.objections') }}" class="nav-link flex items-center space-x-3 px-4 py-3 hover:bg-blue-800 {{ request()->routeIs('admin.objections*') ? 'bg-blue-800 active' : '' }}">
                <svg class="sidebar-icon w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span>{{ __t('objections') }}</span>
            </a>
            <a href="{{ route('admin.violations') }}" class="nav-link flex items-center space-x-3 px-4 py-3 hover:bg-blue-800 {{ request()->routeIs('admin.violations*') ? 'bg-blue-800 active' : '' }}">
                <svg class="sidebar-icon w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/></svg>
                <span>{{ __t('code_conduct_violations') }}</span>
            </a>
            <a href="{{ route('admin.assisted_votes') }}" class="nav-link flex items-center space-x-3 px-4 py-3 hover:bg-blue-800 {{ request()->routeIs('admin.assisted_votes*') ? 'bg-blue-800 active' : '' }}">
                <svg class="sidebar-icon w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                <span>{{ __t('assisted_votes') }}</span>
            </a>
            <div class="border-t border-blue-800 my-2"></div>
            <a href="{{ route('admin.audit_logs') }}" class="nav-link flex items-center space-x-3 px-4 py-3 hover:bg-blue-800 {{ request()->routeIs('admin.audit_logs*') ? 'bg-blue-800 active' : '' }}">
                <svg class="sidebar-icon w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                <span>{{ __t('audit_logs') }}</span>
            </a>
            <a href="{{ route('admin.suspicious_logs') }}" class="nav-link flex items-center space-x-3 px-4 py-3 hover:bg-blue-800 {{ request()->routeIs('admin.suspicious_logs*') ? 'bg-blue-800 active' : '' }}">
                <svg class="sidebar-icon w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/></svg>
                <span>{{ __t('suspicious_activity') }}</span>
            </a>
            <a href="{{ route('admin.settings') }}" class="nav-link flex items-center space-x-3 px-4 py-3 hover:bg-blue-800 {{ request()->routeIs('admin.settings*') ? 'bg-blue-800 active' : '' }}">
                <svg class="sidebar-icon w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                <span>{{ __t('settings') }}</span>
            </a>
        </nav>
        <div class="p-4 border-t border-blue-800">
            <a href="{{ route('dashboard') }}" class="flex items-center space-x-2 text-blue-300 hover:text-white text-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                <span>{{ __t('back') }} {{ __t('home') }}</span>
            </a>
        </div>
    </aside>
    <div class="flex-1 ml-64 main-content">
        <header class="bg-white shadow-sm border-b border-gray-200 sticky top-0 z-20 page-header">
            <div class="flex items-center justify-between h-16 px-6">
                <h1 class="text-lg font-semibold text-gray-800 page-title">@yield('title', __t('admin_panel'))</h1>
                <div class="flex items-center space-x-4">
                    <div class="flex items-center space-x-1 px-3 py-1 rounded-full text-xs font-medium system-status
                        @if(session('system_status', 'safe') === 'alert') bg-red-100 text-red-700
                        @else bg-green-100 text-green-700 @endif">
                        <span class="w-2 h-2 rounded-full inline-block mr-1
                            @if(session('system_status', 'safe') === 'alert') bg-red-500
                            @else bg-green-500 @endif"></span>
                        {{ __t(session('system_status', 'safe')) }}
                    </div>
                    <a href="{{ route('language.set', 'en') }}" class="text-xs border border-gray-300 rounded px-2 py-1 hover:bg-gray-100">EN</a>
                    <a href="{{ route('language.set', 'sw') }}" class="text-xs border border-gray-300 rounded px-2 py-1 hover:bg-gray-100">SW</a>
                    <div class="flex items-center space-x-2 text-sm">
                        <span class="text-gray-600">{{ $user->full_name }}</span>
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="text-red-600 hover:text-red-800 text-xs">{{ __t('logout') }}</button>
                        </form>
                    </div>
                </div>
            </div>
        </header>
        <main class="p-6">
            @if(session('success'))
                <div class="flash-msg bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4 flex justify-between items-center">{{ session('success') }}<button onclick="this.parentElement.remove()" class="text-green-700 hover:text-green-900 ml-4">&times;</button></div>
            @endif
            @if(session('error'))
                <div class="flash-msg bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4 flex justify-between items-center">{{ session('error') }}<button onclick="this.parentElement.remove()" class="text-red-700 hover:text-red-900 ml-4">&times;</button></div>
            @endif
            @if(session('warning'))
                <div class="flash-msg bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded mb-4 flex justify-between items-center">{{ session('warning') }}<button onclick="this.parentElement.remove()" class="text-yellow-700 hover:text-yellow-900 ml-4">&times;</button></div>
            @endif
            @if(session('info'))
                <div class="flash-msg bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded mb-4 flex justify-between items-center">{{ session('info') }}<button onclick="this.parentElement.remove()" class="text-blue-700 hover:text-blue-900 ml-4">&times;</button></div>
            @endif
            @if($errors->any())
                <div class="flash-msg bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    @foreach($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif
            @yield('content')
        </main>
    </div>
    <script>
        var topBar = document.getElementById('topBarFill');
        if (topBar) {
            topBar.style.transition = 'width 0.8s cubic-bezier(0.4, 0, 0.2, 1)';
            topBar.style.width = '70%';
            window.addEventListener('load', function() {
                topBar.style.width = '100%';
                setTimeout(function() { topBar.parentElement.style.opacity = '0'; }, 500);
            });
        }
        document.querySelectorAll('.flash-msg').forEach(function(el) {
            setTimeout(function() {
                el.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
                el.style.opacity = '0';
                el.style.transform = 'translateX(100%)';
                setTimeout(function() { el.remove(); }, 500);
            }, 5000);
        });
    </script>
<script>
(function(){
    var loader = document.getElementById('pageLoader');
    if(loader){
        window.addEventListener('load',function(){loader.classList.add('loader-hide');});
        setTimeout(function(){loader.classList.add('loader-hide');},2000);
    }
    document.addEventListener('click',function(e){
        var link = e.target.closest('a');
        if(link && link.hostname === window.location.hostname && !link.hasAttribute('download') && !link.getAttribute('href')?.startsWith('#')){
            var method = link.getAttribute('data-method') || link.getAttribute('onclick');
            if(!method && !e.ctrlKey && !e.metaKey && link.target !== '_blank'){
                loader.classList.remove('loader-hide');
                loader.style.opacity='1';loader.style.visibility='visible';
            }
        }
    });
    window.addEventListener('beforeunload',function(){
        if(loader){loader.classList.remove('loader-hide');loader.style.opacity='1';loader.style.visibility='visible';}
    });
})();
</script>
</body>
</html>