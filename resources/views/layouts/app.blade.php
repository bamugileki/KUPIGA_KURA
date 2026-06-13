<!DOCTYPE html>
<html lang="{{ session('lang', 'en') }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __t('app_name') }} - @yield('title', __t('home'))</title>
    <link rel="icon" type="image/png" href="{{ asset('images/TUME.png') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    @stack('head')
    <style>
        @keyframes fadeInUp { from { opacity: 0; transform: translateY(30px); } to { opacity: 1; transform: translateY(0); } }
        @keyframes fadeInDown { from { opacity: 0; transform: translateY(-20px); } to { opacity: 1; transform: translateY(0); } }
        @keyframes slideInRight { from { opacity: 0; transform: translateX(40px); } to { opacity: 1; transform: translateX(0); } }
        @keyframes scaleIn { from { opacity: 0; transform: scale(0.9); } to { opacity: 1; transform: scale(1); } }
        @keyframes pulseDot { 0%, 100% { opacity: 1; transform: scale(1); } 50% { opacity: 0.5; transform: scale(1.3); } }
        @keyframes voteSuccess { 0% { transform: scale(0); opacity: 0; } 50% { transform: scale(1.2); } 100% { transform: scale(1); opacity: 1; } }
        @keyframes countUp { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
        @keyframes shakeAlert { 0%, 100% { transform: translateX(0); } 10%, 30%, 50%, 70%, 90% { transform: translateX(-4px); } 20%, 40%, 60%, 80% { transform: translateX(4px); } }
        @keyframes progressFill { from { width: 0; } to { width: var(--progress); } }
        @keyframes confettiFall { 0% { transform: translateY(-100vh) rotate(0deg); opacity: 1; } 100% { transform: translateY(100vh) rotate(720deg); opacity: 0; } }
        @keyframes loaderPulse { 0%, 100% { transform: scale(1); opacity: 0.9; } 50% { transform: scale(1.08); opacity: 1; } }
        @keyframes loaderSpin { to { transform: rotate(360deg); } }
        .top-loader { position: fixed; top: 0; left: 0; z-index: 9999; width: 100%; height: 3px; pointer-events: none; }
        .top-loader .bar { height: 100%; width: 0%; background: linear-gradient(90deg, #3b82f6, #8b5cf6); transition: width 0.3s ease; }
        .top-loader.hide { display: none; }
        .fade-in-up { animation: fadeInUp 0.6s ease-out both; }
        .fade-in-down { animation: fadeInDown 0.5s ease-out both; }
        .slide-in-right { animation: slideInRight 0.4s ease-out both; }
        .scale-in { animation: scaleIn 0.4s ease-out both; }
        .vote-card { animation: fadeInUp 0.5s ease-out both; transition: all 0.3s ease; }
        .vote-card:hover { transform: translateY(-4px); box-shadow: 0 12px 30px rgba(0,0,0,0.1); }
        .vote-btn { transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1); position: relative; overflow: hidden; }
        .vote-btn:hover { transform: translateY(-2px) scale(1.03); box-shadow: 0 6px 20px rgba(0,0,0,0.15); }
        .vote-btn:active { transform: translateY(0) scale(0.97); }
        .vote-btn::after { content: ''; position: absolute; top: 0; left: -100%; width: 100%; height: 100%; background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent); transition: left 0.5s ease; }
        .vote-btn:hover::after { left: 100%; }
        .live-dot { display: inline-block; width: 10px; height: 10px; border-radius: 50%; background: #22c55e; animation: pulseDot 1.5s ease-in-out infinite; }
        .success-check { animation: voteSuccess 0.6s ease-out both; }
        .shake-alert { animation: shakeAlert 0.6s ease-out both; }
        .progress-bar { animation: progressFill 1.5s ease-out both; }
        .stagger-1 { animation-delay: 0.1s; }
        .stagger-2 { animation-delay: 0.2s; }
        .stagger-3 { animation-delay: 0.3s; }
        .stagger-4 { animation-delay: 0.4s; }
        .stagger-5 { animation-delay: 0.5s; }
        .stagger-6 { animation-delay: 0.6s; }
        .stagger-7 { animation-delay: 0.7s; }
        .stagger-8 { animation-delay: 0.8s; }
        .high-contrast { background: #000 !important; color: #fff !important; }
        .high-contrast .bg-white, .high-contrast .bg-gray-50, .high-contrast .bg-gray-100 { background: #111 !important; }
        .high-contrast .text-gray-700, .high-contrast .text-gray-600, .high-contrast .text-gray-500, .high-contrast .text-gray-800 { color: #fff !important; }
        .high-contrast .text-blue-900, .high-contrast .text-blue-800, .high-contrast .text-blue-700 { color: #60a5fa !important; }
        .high-contrast .border-gray-200, .high-contrast .border-gray-300 { border-color: #555 !important; }
        .high-contrast input, .high-contrast select, .high-contrast textarea { background: #222 !important; color: #fff !important; border-color: #666 !important; }
        .high-contrast nav { background: #000 !important; border-bottom: 2px solid #60a5fa !important; }
        .high-contrast footer { background: #000 !important; border-top: 2px solid #60a5fa !important; }
        .high-contrast a { color: #60a5fa !important; }
        .high-contrast .bg-blue-900 { background: #000 !important; border: 2px solid #60a5fa !important; }
        .high-contrast .bg-green-100, .high-contrast .bg-yellow-100, .high-contrast .bg-red-100 { background: #222 !important; }
        .accessibility-mode-blind a:focus, .accessibility-mode-blind button:focus, .accessibility-mode-blind input:focus { outline: 3px solid #fbbf24 !important; outline-offset: 2px; }
        .accessibility-mode-low_vision a:focus, .accessibility-mode-low_vision button:focus, .accessibility-mode-low_vision input:focus { outline: 3px solid #f59e0b !important; }
    </style>
</head>
<body class="bg-gray-50 min-h-screen flex flex-col {{ getAccessibilityBodyClass() }}">
    @php $previewRole = session('preview_role'); @endphp
    @if($previewRole)
        <div class="fixed top-0 left-0 right-0 z-50 bg-gradient-to-r from-yellow-500 via-amber-500 to-yellow-500 text-white text-sm px-4 py-2 flex items-center justify-between shadow-lg" style="animation: fadeInDown 0.4s ease-out">
            <div class="flex items-center space-x-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                <span class="font-medium">{{ __t('preview_mode') }}:</span>
                <span>{{ $previewRole === 'candidate' ? __t('preview_as_candidate') : __t('preview_as_voter') }}</span>
            </div>
            <a href="{{ route('preview.exit') }}" class="flex items-center space-x-1.5 bg-white/20 hover:bg-white/30 transition px-3 py-1 rounded-lg text-sm font-medium">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                <span>{{ __t('back_to_admin') }}</span>
            </a>
        </div>
        <style>
            body { padding-top: 40px; }
        </style>
    @endif
    <div id="topLoader" class="top-loader"><div class="bar" id="topLoaderBar"></div></div>
    <nav class="bg-blue-900 text-white shadow-lg sticky top-0 z-20">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex items-center justify-between h-16">
                <a href="{{ route('home') }}" class="flex items-center space-x-3">
                    <div class="bg-white rounded-full p-3 flex items-center justify-center shadow-lg"><img src="{{ asset('images/TUME.png') }}" alt="TUME Logo" class="h-12 w-12"></div>
                    <span class="font-bold text-lg">{{ __t('app_name') }}</span>
                </a>
                <div class="flex items-center space-x-2">
                    @auth
                        <a href="{{ route('dashboard') }}" class="text-blue-200 hover:text-white px-3 py-2 text-sm">{{ __t('dashboard') }}</a>
                        <a href="{{ route('results') }}" class="text-blue-200 hover:text-white px-3 py-2 text-sm">{{ __t('results') }}</a>
                        <a href="{{ route('objections.submit') }}" class="text-blue-200 hover:text-white px-3 py-2 text-sm">{{ __t('objections') }}</a>
                        @if(Auth::user()->isAdmin() && !session('preview_role'))
                            <a href="{{ route('admin.dashboard') }}" class="text-blue-200 hover:text-white px-3 py-2 text-sm">{{ __t('admin_panel') }}</a>
                        @endif
                        @if(Auth::user()->accessibility_enabled)
                        <a href="{{ route('profile') }}#accessibilitySettings" class="text-blue-200 hover:text-white px-2 py-2 text-sm flex items-center gap-1" title="{{ __t('accessibility_settings') }}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <span class="text-xs">♿</span>
                        </a>
                        @else
                        <a href="{{ route('profile') }}#accessibilitySettings" class="text-blue-300 hover:text-white px-2 py-2 text-sm flex items-center gap-1" title="{{ __t('enable_accessibility_short') }}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <span class="text-xs">♿</span>
                        </a>
                        @endif
                        <a href="{{ route('profile') }}" class="text-blue-200 hover:text-white px-3 py-2 text-sm">{{ Auth::user()->full_name }}</a>
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="text-blue-200 hover:text-white px-3 py-2 text-sm">{{ __t('logout') }}</button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="text-blue-200 hover:text-white px-3 py-2 text-sm">{{ __t('login') }}</a>
                        <a href="{{ route('register') }}" class="text-blue-200 hover:text-white px-3 py-2 text-sm">{{ __t('register') }}</a>
                    @endauth
                    <a href="{{ route('language.set', 'en') }}" class="text-xs border border-blue-300 rounded px-2 py-1 hover:bg-blue-800">EN</a>
                    <a href="{{ route('language.set', 'sw') }}" class="text-xs border border-blue-300 rounded px-2 py-1 hover:bg-blue-800">SW</a>
                </div>
            </div>
        </div>
    </nav>

    @auth
    @php
        $aMode = getAccessibilityMode();
        $disTypes = session('disability_type', []);
        $acEnabled = Auth::user()->accessibility_enabled;
    @endphp
    @if($acEnabled && $aMode === 'blind')
        @include('modes.blind')
    @elseif($acEnabled && $aMode === 'low_vision')
        @include('modes.low_vision')
    @elseif($acEnabled && $aMode === 'motor')
        @include('modes.motor')
    @elseif($acEnabled && $aMode === 'cognitive')
        @include('modes.cognitive')
    @elseif($acEnabled && $aMode === 'assisted')
        @include('modes.assisted')
    @elseif($acEnabled && $aMode === 'elderly')
        @include('modes.elderly')
    @elseif($acEnabled && $aMode === 'hearing')
        @include('modes.hearing')
    @elseif($acEnabled && $aMode === 'normal' && count($disTypes) > 0)
        {{-- Combined disabilities: apply minimal adaptations --}}
        <div id="accessibility-bar" class="bg-blue-800/80 text-white text-xs px-4 py-1.5 flex items-center justify-between sticky top-0 z-50">
            <div class="flex items-center gap-2">
                <svg class="w-4 h-4 text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span class="font-medium">{{ __t('accessible_ui_active') }}</span>
                <span class="text-blue-300">·</span>
                <span class="text-blue-200">{{ implode(', ', array_map(fn($d) => __t('disability_' . $d), $disTypes)) }}</span>
            </div>
            <a href="{{ route('profile') }}#accessibilitySettings" class="text-blue-200 hover:text-white underline text-xs">{{ __t('adjust_settings') }}</a>
        </div>
    @endif
@endauth

<main class="flex-1 max-w-7xl mx-auto px-4 py-6 w-full">
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">{{ session('error') }}</div>
        @endif
        @if(session('warning'))
            <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded mb-4">{{ session('warning') }}</div>
        @endif
        @if(session('info'))
            <div class="bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded mb-4">{{ session('info') }}</div>
        @endif
        @if($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                @foreach($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif
        @yield('content')
    </main>

    <footer class="bg-blue-900 text-gray-400 text-sm">
        <div class="max-w-7xl mx-auto px-4 py-8">
            <div class="flex flex-col md:flex-row items-center justify-between gap-4">
                <div class="flex items-center space-x-2">
                    <div class="bg-white rounded-full p-2 flex items-center justify-center shadow"><img src="{{ asset('images/TUME.png') }}" alt="TUME Logo" class="h-8 w-8"></div>
                    <span class="text-white font-semibold">{{ __t('app_name') }}</span>
                </div>
                <div class="flex flex-wrap justify-center gap-4 text-gray-400">
                    <a href="#" class="hover:text-white transition">{{ __t('privacy_policy') }}</a>
                    <a href="#" class="hover:text-white transition">{{ __t('terms_of_use') }}</a>
                    <a href="#" class="hover:text-white transition">{{ __t('support_center') }}</a>
                </div>
                <div class="text-center md:text-right">
                    <p>&copy; {{ date('Y') }} {{ __t('commission_name') }}</p>
                    <p class="text-xs mt-1">{{ __t('secure_transparent_independent') }}</p>
                </div>
            </div>
            <div class="border-t border-blue-800 mt-6 pt-4 text-center text-xs text-gray-500">
                {{ __t('powered_by') }} <span class="text-blue-400 font-medium">Francis Bamugileki</span>
            </div>
        </div>
    </footer>

<script>
(function() {
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
    document.addEventListener('click', function(e) {
        var link = e.target.closest('a');
        if (link && link.hostname === window.location.hostname && !link.hasAttribute('download') && !link.getAttribute('href')?.startsWith('#')) {
            var method = link.getAttribute('data-method') || link.getAttribute('onclick');
            if (!method && !e.ctrlKey && !e.metaKey && link.target !== '_blank') {
                showLoader();
            }
        }
    });
    window.addEventListener('beforeunload', function() { showLoader(); });

    @auth
    @if(!Auth::user()->accessibility_enabled)
    <div id="a11ySuggest" class="hidden fixed bottom-6 right-6 z-50 max-w-sm bg-white rounded-xl shadow-2xl border border-blue-100 p-5 animate-fadeIn">
        <div class="flex items-start gap-3 mb-3">
            <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center flex-shrink-0">
                <svg class="w-5 h-5 text-blue-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div class="flex-1 min-w-0">
                <p class="font-semibold text-gray-900 text-sm">{{ __t('a11y_suggest_title') }}</p>
                <p class="text-xs text-gray-600 mt-0.5 leading-relaxed">{{ __t('a11y_suggest_message') }}</p>
            </div>
            <button onclick="dismissA11ySuggest()" class="text-gray-400 hover:text-gray-600 p-1">&times;</button>
        </div>
        <div class="flex items-center gap-2">
            <button onclick="window.location.href='{{ route("profile") }}#accessibilitySettings'" class="flex-1 bg-blue-900 hover:bg-blue-800 text-white text-sm font-medium py-2 px-4 rounded-lg transition">{{ __t('enable_accessibility_short') ?? 'Enable' }}</button>
            <button onclick="dismissA11ySuggest()" class="text-xs text-gray-500 hover:text-gray-700 px-3 py-2">{{ __t('not_now') ?? 'Not now' }}</button>
        </div>
    </div>

    <script>
    (function() {
        var suggestKey = 'a11y_suggestion_shown';
        if (sessionStorage.getItem(suggestKey)) return;
        var clicks = 0, zooms = 0, suggested = false;
        function showA11ySuggest() {
            if (suggested) return;
            suggested = true;
            var el = document.getElementById('a11ySuggest');
            if (el) el.classList.remove('hidden');
            sessionStorage.setItem(suggestKey, '1');
        }
        function dismissA11ySuggest() {
            var el = document.getElementById('a11ySuggest');
            if (el) el.classList.add('hidden');
        }
        window.dismissA11ySuggest = dismissA11ySuggest;
        document.addEventListener('click', function() {
            clicks++;
            if (clicks >= 12 && zooms < 2) showA11ySuggest();
        });
        document.addEventListener('dblclick', function() { zooms++; });
        document.addEventListener('wheel', function(e) {
            if (e.ctrlKey) zooms++;
        });
    })();
    </script>
    @endif
    @endauth
})();
</script>
@stack('scripts')
@yield('scripts')
</body>
</html>
