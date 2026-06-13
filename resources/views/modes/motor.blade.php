<div id="accessibility-bar" class="bg-emerald-900 text-white text-sm px-4 py-2 flex items-center justify-between sticky top-0 z-50">
    <div class="flex items-center gap-3">
        <svg class="w-5 h-5 text-emerald-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
        <span class="font-semibold">{{ __t('accessibility_motor') }}</span>
    </div>
    <div class="flex items-center gap-3">
        <span class="text-xs text-emerald-300">{{ __t('motor_hint') }}</span>
        <button id="btnSizeIncrease" class="bg-emerald-700 hover:bg-emerald-600 px-3 py-1.5 rounded-lg text-sm font-medium transition">{{ __t('larger_buttons') }}</button>
    </div>
</div>

<style>
    .candidate-card label, .vote-btn, a, button, input, select, textarea {
        min-height: 44px;
        min-width: 44px;
    }
    .candidate-card label {
        padding: 1rem !important;
    }
    .candidate-card label .flex {
        gap: 1rem !important;
    }
    .vote-btn {
        padding: 0.75rem 2rem !important;
        font-size: 1.1rem !important;
    }
    nav a, nav button {
        padding-top: 0.5rem !important;
        padding-bottom: 0.5rem !important;
    }
    input, select, textarea {
        font-size: 1rem !important;
        padding: 0.75rem !important;
    }
</style>

<script>
(function() {
    document.getElementById('btnSizeIncrease')?.addEventListener('click', function() {
        document.querySelectorAll('button, a, .vote-btn, .candidate-card').forEach(function(el) {
            var current = parseFloat(window.getComputedStyle(el).fontSize) || 14;
            el.style.fontSize = (current + 2) + 'px';
            el.style.padding = '1rem 2rem';
        });
    });
})();
</script>
