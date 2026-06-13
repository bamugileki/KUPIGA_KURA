<div id="accessibility-bar" class="bg-teal-900 text-white text-sm px-4 py-2 flex items-center justify-between sticky top-0 z-50">
    <div class="flex items-center gap-3">
        <svg class="w-5 h-5 text-teal-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/></svg>
        <span class="font-semibold">{{ __t('accessibility_cognitive') }}</span>
    </div>
    <div class="flex items-center gap-2">
        <span class="text-xs text-teal-300">{{ __t('cognitive_hint') }}</span>
    </div>
</div>

<style>
    body { max-width: 100vw; overflow-x: hidden; }
    .candidate-card { border-width: 3px !important; }
    nav { box-shadow: 0 4px 6px rgba(0,0,0,0.1) !important; }
    main { max-width: 48rem !important; }
    .vote-card h2, h3, h4 { font-weight: 700 !important; }
    p, label, .text-sm { font-size: 1rem !important; line-height: 1.6 !important; }
    button, a { font-weight: 600 !important; }
    .grid.md\\:grid-cols-2 { grid-template-columns: 1fr !important; }
    .grid.md\\:grid-cols-3 { grid-template-columns: 1fr !important; }
    input, select, textarea { font-size: 1.05rem !important; padding: 0.75rem !important; }
</style>
