<!DOCTYPE html>
<html lang="{{ session('lang', 'en') }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __t('app_name') }} - @yield('title', __t('home'))</title>
    <link rel="icon" type="image/png" href="{{ asset('images/TUME.png') }}">
    <script src="https://cdn.tailwindcss.com"></script>
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
        @keyframes loaderFadeOut { to { opacity: 0; visibility: hidden; } }
        .page-loader { position: fixed; inset: 0; z-index: 9999; background: #1e3a8a; display: flex; flex-direction: column; align-items: center; justify-content: center; transition: opacity 0.5s ease, visibility 0.5s ease; }
        .page-loader.hide { animation: loaderFadeOut 0.5s ease forwards; }
        .page-loader .logo-ring { animation: loaderPulse 1.5s ease-in-out infinite; }
        .page-loader .spinner { width: 32px; height: 32px; border: 3px solid rgba(255,255,255,0.2); border-top-color: white; border-radius: 50%; animation: loaderSpin 0.8s linear infinite; margin-top: 20px; }
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
    </style>
</head>
<body class="bg-gray-50 min-h-screen flex flex-col">
    <div id="pageLoader" class="page-loader">
        <div class="logo-ring bg-white rounded-full p-5 flex items-center justify-center shadow-xl">
            <img src="{{ asset('images/TUME.png') }}" alt="TUME Logo" class="h-24 w-24">
        </div>
        <div class="spinner"></div>
    </div>
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
                        @if(Auth::user()->isAdmin())
                            <a href="{{ route('admin.dashboard') }}" class="text-blue-200 hover:text-white px-3 py-2 text-sm">{{ __t('admin_panel') }}</a>
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
    var loader = document.getElementById('pageLoader');
    if (loader) {
        window.addEventListener('load', function() { loader.classList.add('hide'); });
        setTimeout(function() { loader.classList.add('hide'); }, 2000);
    }
    document.addEventListener('click', function(e) {
        var link = e.target.closest('a');
        if (link && link.hostname === window.location.hostname && !link.hasAttribute('download') && !link.getAttribute('href')?.startsWith('#')) {
            var method = link.getAttribute('data-method') || link.getAttribute('onclick');
            if (!method && !e.ctrlKey && !e.metaKey && link.target !== '_blank') {
                loader.classList.remove('hide');
                loader.style.opacity = '1';
                loader.style.visibility = 'visible';
            }
        }
    });
    window.addEventListener('beforeunload', function() {
        if (loader) {
            loader.classList.remove('hide');
            loader.style.opacity = '1';
            loader.style.visibility = 'visible';
        }
    });
})();
</script>
</body>
</html>
