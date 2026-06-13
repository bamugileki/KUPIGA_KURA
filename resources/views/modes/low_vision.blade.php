<div id="accessibility-bar" class="bg-amber-900 text-white text-sm px-4 py-2 flex items-center justify-between sticky top-0 z-50">
    <div class="flex items-center gap-3">
        <svg class="w-5 h-5 text-amber-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
        <span class="font-semibold">{{ __t('accessibility_low_vision') }}</span>
    </div>
    <div class="flex items-center gap-2">
        <button id="contrastToggle" class="flex items-center gap-1.5 bg-amber-700 hover:bg-amber-600 px-3 py-1 rounded-lg text-xs font-medium transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
            <span>{{ __t('toggle_contrast') }}</span>
        </button>
        <button id="fontIncrease" class="bg-amber-700 hover:bg-amber-600 px-2 py-1 rounded-lg text-xs font-medium transition">A+</button>
        <button id="fontDecrease" class="bg-amber-700 hover:bg-amber-600 px-2 py-1 rounded-lg text-xs font-medium transition">A-</button>
        <button id="fontReset" class="bg-amber-700 hover:bg-amber-600 px-2 py-1 rounded-lg text-xs font-medium transition">{{ __t('reset_btn') }}</button>
    </div>
</div>

<script>
(function() {
    var contrastBtn = document.getElementById('contrastToggle');
    var html = document.documentElement;

    if (contrastBtn) {
        if (html.classList.contains('high-contrast')) {
            contrastBtn.classList.add('bg-amber-500');
        }
        contrastBtn.addEventListener('click', function() {
            html.classList.toggle('high-contrast');
            this.classList.toggle('bg-amber-500');
            fetch('{{ route("accessibility.toggle_contrast") }}', { method: 'POST', headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' } });
        });
    }

    var body = document.body;
    var currentSize = 100;

    document.getElementById('fontIncrease')?.addEventListener('click', function() {
        currentSize = Math.min(currentSize + 10, 200);
        body.style.fontSize = currentSize + '%';
    });

    document.getElementById('fontDecrease')?.addEventListener('click', function() {
        currentSize = Math.max(currentSize - 10, 60);
        body.style.fontSize = currentSize + '%';
    });

    document.getElementById('fontReset')?.addEventListener('click', function() {
        currentSize = 100;
        body.style.fontSize = '';
    });
})();
</script>
