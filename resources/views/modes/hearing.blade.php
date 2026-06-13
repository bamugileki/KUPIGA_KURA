<div id="accessibility-bar" class="bg-sky-800 text-white text-sm px-4 py-2 flex items-center justify-between sticky top-0 z-50">
    <div class="flex items-center gap-3">
        <svg class="w-5 h-5 text-sky-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.536 8.464a5 5 0 010 7.072m2.828-9.9a9 9 0 010 12.728M5.586 15H4a1 1 0 01-1-1v-4a1 1 0 011-1h1.586l4.707-4.707C10.923 3.663 12 4.109 12 5v14c0 .891-1.077 1.337-1.707.707L5.586 15z"/></svg>
        <span class="font-semibold">{{ __t('accessibility_hearing') }}</span>
    </div>
    <div class="flex items-center gap-2">
        <span class="text-xs text-sky-300">{{ __t('hearing_hint') }}</span>
    </div>
</div>

<style>
    .accessibility-mode-hearing p, .accessibility-mode-hearing label, .accessibility-mode-hearing .text-sm { font-size: 1.05rem !important; line-height: 1.7 !important; }
    .accessibility-mode-hearing h1, .accessibility-mode-hearing h2, .accessibility-mode-hearing h3 { font-weight: 700 !important; }
    .accessibility-mode-hearing button, .accessibility-mode-hearing a { font-weight: 600 !important; }
    .accessibility-mode-hearing input, .accessibility-mode-hearing select, .accessibility-mode-hearing textarea { font-size: 1.05rem !important; padding: 0.75rem !important; }
    .accessibility-mode-hearing nav, .accessibility-mode-hearing footer { border-width: 0 !important; }
    .accessibility-mode-hearing .candidate-card label { border-width: 3px !important; }
    .accessibility-mode-hearing .grid.md\\:grid-cols-2 { grid-template-columns: 1fr !important; }
    .flash-alert { animation: shakeAlert 0.6s ease-out; border: 3px solid #ef4444 !important; }
</style>

<script>
(function() {
    document.querySelectorAll('[role="alert"], .flash-message, .bg-red-100, .bg-green-100, .bg-yellow-100, .bg-blue-100').forEach(function(el) {
        el.classList.add('flash-alert');
        var interval = setInterval(function() {
            el.style.opacity = el.style.opacity === '0.3' ? '1' : '0.3';
        }, 800);
        setTimeout(function() { clearInterval(interval); el.style.opacity = '1'; }, 10000);
    });
})();
</script>
