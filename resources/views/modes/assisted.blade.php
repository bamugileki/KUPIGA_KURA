<div id="accessibility-bar" class="bg-purple-900 text-white text-sm px-4 py-2 flex items-center justify-between sticky top-0 z-50">
    <div class="flex items-center gap-3">
        <svg class="w-5 h-5 text-purple-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
        <span class="font-semibold">{{ __t('assisted_mode_active') }}</span>
    </div>
    <div class="flex items-center gap-2">
        <span class="text-xs text-purple-300">{{ __t('assisted_hint') }}</span>
    </div>
</div>

<style>
    body { max-width: 100vw; overflow-x: hidden; }
    .candidate-card { border-width: 3px !important; }
    p, label, .text-sm { font-size: 1.1rem !important; line-height: 1.8 !important; }
    button, a, .vote-btn { font-size: 1.1rem !important; padding: 0.75rem 1.5rem !important; min-height: 48px; }
    input, select, textarea { font-size: 1.1rem !important; padding: 0.75rem !important; }
</style>
