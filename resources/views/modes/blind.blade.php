<div id="accessibility-bar" class="bg-indigo-900 text-white text-sm px-4 py-2 flex items-center justify-between sticky top-0 z-50">
    <div class="flex items-center gap-3">
        <svg class="w-5 h-5 text-indigo-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
        <span class="font-semibold">{{ __t('accessibility_reader_mode') }}</span>
    </div>
    <div class="flex items-center gap-3">
        <button id="ttsToggle" class="flex items-center gap-1.5 bg-indigo-700 hover:bg-indigo-600 px-3 py-1 rounded-lg text-xs font-medium transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.536 8.464a5 5 0 010 7.072m2.828-9.9a9 9 0 010 12.728M5.586 15H4a1 1 0 01-1-1v-4a1 1 0 011-1h1.586l4.707-4.707C10.923 3.663 12 4.109 12 5v14c0 .891-1.077 1.337-1.707.707L5.586 15z"/></svg>
            <span>{{ __t('tts_activate') }}</span>
        </button>
        <button id="keyboardGuide" class="flex items-center gap-1.5 bg-indigo-700 hover:bg-indigo-600 px-3 py-1 rounded-lg text-xs font-medium transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M3 14h18m-9-4v8m-7 0h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
            <span>{{ __t('keyboard_nav') }}</span>
        </button>
    </div>
</div>

<script>
(function() {
    var ttsToggle = document.getElementById('ttsToggle');
    var speechSynth = window.speechSynthesis;
    var speaking = false;
    var utterance = null;

    if (ttsToggle) {
        ttsToggle.addEventListener('click', function() {
            if (speaking) {
                speechSynth.cancel();
                speaking = false;
                ttsToggle.innerHTML = '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.536 8.464a5 5 0 010 7.072m2.828-9.9a9 9 0 010 12.728M5.586 15H4a1 1 0 01-1-1v-4a1 1 0 011-1h1.586l4.707-4.707C10.923 3.663 12 4.109 12 5v14c0 .891-1.077 1.337-1.707.707L5.586 15z"/></svg> {{ __t('tts_activate') }}';
                return;
            }
            var text = document.body.innerText.substring(0, 3000);
            utterance = new SpeechSynthesisUtterance(text);
            utterance.lang = document.documentElement.lang || 'en';
            utterance.rate = 0.9;
            speechSynth.speak(utterance);
            speaking = true;
            ttsToggle.innerHTML = '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 10a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1h-4a1 1 0 01-1-1v-4z"/></svg> {{ __t('tts_stop') }}';
            utterance.onend = function() {
                speaking = false;
                ttsToggle.innerHTML = '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.536 8.464a5 5 0 010 7.072m2.828-9.9a9 9 0 010 12.728M5.586 15H4a1 1 0 01-1-1v-4a1 1 0 011-1h1.586l4.707-4.707C10.923 3.663 12 4.109 12 5v14c0 .891-1.077 1.337-1.707.707L5.586 15z"/></svg> {{ __t('tts_activate') }}';
            };
        });
    }
})();
</script>
