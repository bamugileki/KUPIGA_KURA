<div id="accessibility-bar" class="bg-amber-800 text-white text-sm px-4 py-2 flex items-center justify-between sticky top-0 z-50">
    <div class="flex items-center gap-3">
        <svg class="w-5 h-5 text-amber-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        <span class="font-semibold">{{ __t('accessibility_elderly') }}</span>
    </div>
    <div class="flex items-center gap-2">
        <button id="elderlyContrast" class="flex items-center gap-1.5 bg-amber-700 hover:bg-amber-600 px-3 py-1 rounded-lg text-xs font-medium transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
            <span>{{ __t('toggle_contrast') }}</span>
        </button>
        <button id="elderlyFontUp" class="bg-amber-700 hover:bg-amber-600 px-2 py-1 rounded-lg text-xs font-medium transition">A+</button>
        <button id="elderlyFontDown" class="bg-amber-700 hover:bg-amber-600 px-2 py-1 rounded-lg text-xs font-medium transition">A-</button>
    </div>
</div>

<style>
    .accessibility-mode-elderly p, .accessibility-mode-elderly label, .accessibility-mode-elderly .text-sm { font-size: 1.15rem !important; line-height: 1.7 !important; }
    .accessibility-mode-elderly h1 { font-size: 1.75rem !important; }
    .accessibility-mode-elderly h2 { font-size: 1.5rem !important; }
    .accessibility-mode-elderly h3 { font-size: 1.3rem !important; }
    .accessibility-mode-elderly input, .accessibility-mode-elderly select, .accessibility-mode-elderly textarea { font-size: 1.1rem !important; padding: 0.75rem !important; }
    .accessibility-mode-elderly button, .accessibility-mode-elderly .vote-btn { padding: 0.75rem 1.5rem !important; font-size: 1.1rem !important; min-height: 48px; }
    .accessibility-mode-elderly .candidate-card label { padding: 1rem !important; }
    .accessibility-mode-elderly a:focus, .accessibility-mode-elderly button:focus, .accessibility-mode-elderly input:focus { outline: 3px solid #d97706 !important; outline-offset: 2px; }
</style>

<script>
(function() {
    var contrastBtn = document.getElementById('elderlyContrast');
    var html = document.documentElement;
    if (contrastBtn) {
        contrastBtn.addEventListener('click', function() {
            html.classList.toggle('high-contrast');
            fetch('{{ route("accessibility.toggle_contrast") }}', { method: 'POST', headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' } });
        });
    }
    var body = document.body;
    var currentSize = 100;
    document.getElementById('elderlyFontUp')?.addEventListener('click', function() {
        currentSize = Math.min(currentSize + 10, 200);
        body.style.fontSize = currentSize + '%';
    });
    document.getElementById('elderlyFontDown')?.addEventListener('click', function() {
        currentSize = Math.max(currentSize - 10, 70);
        body.style.fontSize = currentSize + '%';
    });
})();
</script>
