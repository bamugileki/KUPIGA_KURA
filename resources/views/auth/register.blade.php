@extends('layouts.app')
@section('title', __t('register'))
@section('content')
<div class="max-w-lg mx-auto mt-8">
    <div class="bg-white rounded-lg shadow-lg p-8">
        <div class="text-center mb-6">
            <div class="bg-white rounded-full p-3 inline-flex items-center justify-center mx-auto mb-4 shadow-lg"><img src="{{ asset('images/TUME.png') }}" alt="TUME Logo" class="h-16 w-16"></div>
            <h2 class="text-2xl font-bold text-blue-900">{{ __t('register') }}</h2>
            <p class="text-gray-600 text-sm">{{ __t('at_least_one_id') }}</p>
        </div>
        <form method="POST" action="{{ route('register') }}">
            @csrf
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">{{ __t('full_name') }}</label>
                <input type="text" name="full_name" value="{{ old('full_name') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500" required>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">{{ __t('email') }}</label>
                <input type="email" name="email" value="{{ old('email') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500" required>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">{{ __t('phone') }} <span class="text-xs text-gray-500">({{ __t('phone_tz_hint') }})</span></label>
                <input type="text" name="phone" value="{{ old('phone') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500" placeholder="+2557XXXXXXXX">
            </div>
            <div class="grid md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2">{{ __t('password') }}</label>
                    <input type="password" name="password" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500" required minlength="8">
                </div>
                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2">{{ __t('confirm_password') }}</label>
                    <input type="password" name="password_confirmation" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500" required>
                </div>
            </div>
            <hr class="my-4">
            <p class="text-sm text-gray-600 mb-4"><strong>{{ __t('at_least_one_id') }}</strong></p>
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">{{ __t('nida_number') }}</label>
                <input type="text" name="nida_number" value="{{ old('nida_number') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500" placeholder="YYYYMMDD-XXXXX-XXXXX-XX">
            </div>
            <div class="grid md:grid-cols-2 gap-4 mb-6">
                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2">{{ __t('driving_licence_label') }}</label>
                    <input type="text" name="driving_licence" value="{{ old('driving_licence') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2">{{ __t('nhif_number') }}</label>
                    <input type="text" name="nhif_number" value="{{ old('nhif_number') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500">
                </div>
            </div>

            {{-- Accessibility Section --}}
            <hr class="my-4">
            <div class="mb-6">
                <h3 class="font-bold text-blue-900 mb-3 flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    ♿ {{ __t('accessibility_setup') }}
                </h3>
                <p class="text-xs text-gray-500 mb-3">{{ __t('accessibility_registration_hint') }}</p>

                <label class="flex items-center gap-3 cursor-pointer mb-4">
                    <input type="checkbox" name="accessibility_enabled" value="1" {{ old('accessibility_enabled') ? 'checked' : '' }} class="w-5 h-5 rounded border-gray-300 text-blue-900 focus:ring-blue-500" id="regAccessToggle">
                    <span class="text-sm font-medium text-gray-700">{{ __t('enable_accessibility') }}</span>
                </label>

                <div id="regAccessFields" class="space-y-4 {{ old('accessibility_enabled') ? '' : 'hidden' }}">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">{{ __t('disability_type') }}</label>
                        <div class="grid grid-cols-2 gap-2">
                            @foreach([
                                ['value' => 'visual', 'label_en' => 'Vision Impairment', 'label_sw' => 'Ulemavu wa Macho'],
                                ['value' => 'hearing', 'label_en' => 'Hearing Impairment', 'label_sw' => 'Ulemavu wa Kusikia'],
                                ['value' => 'cognitive', 'label_en' => 'Cognitive Disability', 'label_sw' => 'Ulemavu wa Akili'],
                                ['value' => 'motor', 'label_en' => 'Physical/Motor Disability', 'label_sw' => 'Ulemavu wa Mwili'],
                                ['value' => 'elderly', 'label_en' => 'Elderly Support', 'label_sw' => 'Msaada kwa Wazee'],
                                ['value' => 'speech', 'label_en' => 'Speech Impairment', 'label_sw' => 'Ulemavu wa Kuongea'],
                                ['value' => 'other', 'label_en' => 'Other / Prefer not to say', 'label_sw' => 'Nyingine / Sitaki kusema'],
                            ] as $d)
                            <label class="flex items-center gap-2 text-sm text-gray-700 cursor-pointer">
                                <input type="checkbox" name="disability_type[]" value="{{ $d['value'] }}"
                                    {{ in_array($d['value'], old('disability_type', [])) ? 'checked' : '' }}
                                    class="rounded border-gray-300 text-blue-900 focus:ring-blue-500">
                                {{ session('lang') == 'sw' ? $d['label_sw'] : $d['label_en'] }}
                            </label>
                            @endforeach
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">{{ __t('accessibility_mode_label') }}</label>
                        <select name="accessibility_mode" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 text-sm">
                            <option value="normal" {{ old('accessibility_mode') === 'normal' ? 'selected' : '' }}>{{ __t('mode_normal') }}</option>
                            <option value="blind" {{ old('accessibility_mode') === 'blind' ? 'selected' : '' }}>{{ __t('mode_blind') }}</option>
                            <option value="low_vision" {{ old('accessibility_mode') === 'low_vision' ? 'selected' : '' }}>{{ __t('mode_low_vision') }}</option>
                            <option value="hearing" {{ old('accessibility_mode') === 'hearing' ? 'selected' : '' }}>{{ __t('mode_hearing') }}</option>
                            <option value="motor" {{ old('accessibility_mode') === 'motor' ? 'selected' : '' }}>{{ __t('mode_motor') }}</option>
                            <option value="cognitive" {{ old('accessibility_mode') === 'cognitive' ? 'selected' : '' }}>{{ __t('mode_cognitive') }}</option>
                            <option value="elderly" {{ old('accessibility_mode') === 'elderly' ? 'selected' : '' }}>{{ __t('mode_elderly') }}</option>
                            <option value="assisted" {{ old('accessibility_mode') === 'assisted' ? 'selected' : '' }}>{{ __t('mode_assisted') }}</option>
                        </select>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">{{ __t('text_size') }}</label>
                            <select name="text_size" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 text-sm">
                                <option value="small" {{ old('text_size') === 'small' ? 'selected' : '' }}>{{ __t('text_small') }}</option>
                                <option value="medium" {{ old('text_size') === 'medium' || !old('text_size') ? 'selected' : '' }}>{{ __t('text_medium') }}</option>
                                <option value="large" {{ old('text_size') === 'large' ? 'selected' : '' }}>{{ __t('text_large') }}</option>
                            </select>
                        </div>
                        <div>
                            <label class="flex items-center gap-2 mt-6 cursor-pointer">
                                <input type="checkbox" name="high_contrast" value="1" {{ old('high_contrast') ? 'checked' : '' }} class="w-5 h-5 rounded border-gray-300 text-blue-900 focus:ring-blue-500">
                                <span class="text-sm font-medium text-gray-700">{{ __t('high_contrast') }}</span>
                            </label>
                        </div>
                    </div>

                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-3 text-xs text-blue-800">
                        <strong>{{ __t('accessibility_privacy_note_title') }}:</strong> {{ __t('accessibility_privacy_note') }}
                    </div>

                    {{-- Accessibility Confirmation --}}
                    <div id="regAccessConfirm" class="hidden mt-4 p-4 bg-amber-50 border border-amber-200 rounded-lg">
                        <label class="flex items-start gap-3 cursor-pointer">
                            <input type="checkbox" name="accessibility_confirmed" value="1" class="mt-1 w-5 h-5 rounded border-gray-300 text-amber-700 focus:ring-amber-500">
                            <div>
                                <span class="text-sm font-medium text-amber-900">{{ __t('accessibility_confirm_label') }}</span>
                                <p class="text-xs text-amber-700 mt-1">{{ __t('accessibility_confirm_text') }}</p>
                            </div>
                        </label>
                    </div>
                </div>
            </div>

            <div class="mb-6">
                <label class="block text-gray-700 text-sm font-bold mb-2">Security Check: {{ session('captcha_question') }} = ?</label>
                <input type="number" name="captcha" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500" required placeholder="Enter the answer">
                @error('captcha') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <button type="submit" class="w-full bg-blue-900 text-white font-bold py-2 px-4 rounded-lg hover:bg-blue-800">{{ __t('register_btn') }}</button>
        </form>
        <p class="text-center text-sm text-gray-600 mt-4">
            {{ __t('already_have_account') }} <a href="{{ route('login') }}" class="text-blue-900 underline">{{ __t('login') }}</a>
        </p>
    </div>
</div>

<script>
(function() {
    var toggle = document.getElementById('regAccessToggle');
    var fields = document.getElementById('regAccessFields');
    var confirmBox = document.getElementById('regAccessConfirm');
    if (toggle && fields) {
        toggle.addEventListener('change', function() {
            fields.classList.toggle('hidden', !this.checked);
            if (confirmBox) confirmBox.classList.toggle('hidden', !this.checked);
        });
    }
})();
</script>
@endsection
